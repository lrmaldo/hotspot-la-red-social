<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Voucher;
use App\Models\Zona;
use Illuminate\Support\Facades\Log;
use RouterOS\Client;
use RouterOS\Query;

class MikrotikService
{
    private const TEMP_PAY_COMMENT_PREFIX = 'PAY-TEMP';

    private function normalizarMac(string $mac): string
    {
        return strtoupper(trim($mac));
    }

    private function resolverIpClienteHotspot(Client $client, ?string $ip, ?string $mac): ?string
    {
        if (! empty($ip)) {
            return $ip;
        }

        if (empty($mac)) {
            return null;
        }

        $query = new Query('/ip/hotspot/host/print');
        $query->where('mac-address', $this->normalizarMac($mac));
        $hosts = $client->query($query)->read();

        if (empty($hosts)) {
            return null;
        }

        foreach ($hosts as $host) {
            $address = $host['address'] ?? null;
            if (! empty($address)) {
                return (string) $address;
            }
        }

        return null;
    }

    private function limpiarAccesosTemporalesExpirados(Client $client): void
    {
        try {
            $query = new Query('/ip/hotspot/ip-binding/print');
            $bindings = $client->query($query)->read();

            foreach ($bindings as $binding) {
                $comment = (string) ($binding['comment'] ?? '');
                if (! str_starts_with($comment, self::TEMP_PAY_COMMENT_PREFIX)) {
                    continue;
                }

                $matches = [];
                if (! preg_match('/\|exp=([^|]+)$/', $comment, $matches)) {
                    continue;
                }

                $expira = \Carbon\Carbon::parse($matches[1]);
                if ($expira->isFuture()) {
                    continue;
                }

                if (! isset($binding['.id'])) {
                    continue;
                }

                $removeQuery = new Query('/ip/hotspot/ip-binding/remove');
                $removeQuery->equal('.id', $binding['.id']);
                $client->query($removeQuery)->read();
            }
        } catch (\Throwable $e) {
            Log::warning('MikroTik: no se pudieron limpiar accesos temporales expirados', [
                'zona_id' => $this->zona->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function __construct(private Zona $zona)
    {
    }

    private function conectar(): Client
    {
        return new Client([
            'host'    => $this->zona->hotspot_host,
            'user'    => $this->zona->mikrotik_user ?? config('mikrotik.user'),
            // La columna mikrotik_password tiene cast 'encrypted' en el modelo Zona,
            // por lo que aqui ya llega desencriptada y no debe desencriptarse de nuevo.
            'pass'    => $this->zona->mikrotik_password ?: config('mikrotik.password'),
            'port'    => (int) ($this->zona->mikrotik_port ?: config('mikrotik.port', 8728)),
            'timeout' => (int) config('mikrotik.timeout', 5),
        ]);
    }

    private function buscarUsuarioPorCodigo(Client $client, string $codigo): ?array
    {
        $query = new Query('/ip/hotspot/user/print');
        $query->where('name', $codigo);
        $users = $client->query($query)->read();

        return empty($users) ? null : $users[0];
    }

    public function crearUsuarioHotspot(Voucher $voucher): bool
    {
        try {
            $client = $this->conectar();
            $existingUser = $this->buscarUsuarioPorCodigo($client, $voucher->codigo);

            $profile = $this->zona->mikrotik_hotspot_profile ?: config('mikrotik.hotspot_profile', 'default');
            $comment = 'Voucher #' . $voucher->id;
            $limitUptime = $voucher->plan->duracion_minutos . 'm';

            if ($existingUser) {
                $setQuery = new Query('/ip/hotspot/user/set');
                $setQuery->equal('.id', $existingUser['.id']);
                $setQuery->equal('password', $voucher->codigo);
                $setQuery->equal('profile', $profile);
                $setQuery->equal('comment', $comment);
                $setQuery->equal('limit-uptime', $limitUptime);

                $client->query($setQuery)->read();

                $voucher->update([
                    'mikrotik_user_id' => (string) $existingUser['.id'],
                ]);

                return true;
            }

            $query = new Query('/ip/hotspot/user/add');
            $query->equal('name', $voucher->codigo);
            $query->equal('password', $voucher->codigo);
            $query->equal('profile', $profile);
            $query->equal('comment', $comment);
            $query->equal('limit-uptime', $limitUptime);

            $client->query($query)->read();

            $createdUser = $this->buscarUsuarioPorCodigo($client, $voucher->codigo);
            if ($createdUser && isset($createdUser['.id'])) {
                $voucher->update([
                    'mikrotik_user_id' => (string) $createdUser['.id'],
                ]);
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('MikroTik: error al crear usuario hotspot', [
                'zona_id'    => $this->zona->id,
                'voucher_id' => $voucher->id,
                'codigo'     => $voucher->codigo,
                'error'      => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function eliminarUsuarioHotspot(string $codigo): bool
    {
        try {
            $client = $this->conectar();

            $user = $this->buscarUsuarioPorCodigo($client, $codigo);
            if (! $user) {
                // Idempotente: si no existe, para efectos de sincronización se considera correcto.
                return true;
            }

            $removeQuery = new Query('/ip/hotspot/user/remove');
            $removeQuery->equal('.id', $user['.id']);
            $client->query($removeQuery)->read();

            return true;
        } catch (\Throwable $e) {
            Log::error('MikroTik: error al eliminar usuario hotspot', [
                'zona_id' => $this->zona->id,
                'codigo'  => $codigo,
                'error'   => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Mide la tasa instantánea de tráfico de una interfaz del router
     * usando /interface/monitor-traffic una sola vez (once).
     *
     * @return array{rx_bps: int, tx_bps: int}|null  null si falla o no hay datos
     */
    public function medirThroughputInterfaz(string $interface): ?array
    {
        try {
            $client = $this->conectar();

            $query = new Query('/interface/monitor-traffic');
            $query->equal('interface', $interface);
            // 'once' hace que devuelva una sola lectura y cierre, en vez de
            // quedarse haciendo streaming continuo.
            $query->equal('once', '');

            $response = $client->query($query)->read();

            if (empty($response[0])) {
                return null;
            }

            $row = $response[0];

            // RouterOS usa guiones; según versión puede venir con o sin sufijo.
            $rx = $row['rx-bits-per-second'] ?? $row['rx-bits-per-second-layer2'] ?? null;
            $tx = $row['tx-bits-per-second'] ?? $row['tx-bits-per-second-layer2'] ?? null;

            if ($rx === null && $tx === null) {
                return null;
            }

            return [
                'rx_bps' => (int) ($rx ?? 0),
                'tx_bps' => (int) ($tx ?? 0),
            ];
        } catch (\Throwable $e) {
            Log::warning('MikroTik: no se pudo medir throughput de interfaz', [
                'zona_id'   => $this->zona->id,
                'interface' => $interface,
                'error'     => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function verificarConexion(): bool
    {
        try {
            $client = $this->conectar();

            $query = new Query('/system/identity/print');
            $response = $client->query($query)->read();

            return ! empty($response);
        } catch (\Throwable $e) {
            Log::error('MikroTik: error al verificar conexión', [
                'zona_id' => $this->zona->id,
                'error'   => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function buscarBindingTemporalPorIp(Client $client, string $ip): ?array
    {
        $query = new Query('/ip/hotspot/ip-binding/print');
        $query->where('address', $ip);
        $bindings = $client->query($query)->read();

        if (empty($bindings)) {
            return null;
        }

        foreach ($bindings as $binding) {
            $comment = (string) ($binding['comment'] ?? '');
            if (str_starts_with($comment, self::TEMP_PAY_COMMENT_PREFIX)) {
                return $binding;
            }
        }

        return null;
    }

    public function habilitarAccesoPagoTemporal(?string $ip, ?string $mac = null, int $minutos = 10): bool
    {
        try {
            $client = $this->conectar();
            $this->limpiarAccesosTemporalesExpirados($client);
            $resolvedIp = $this->resolverIpClienteHotspot($client, $ip, $mac);

            if (! $resolvedIp) {
                return false;
            }

            $expiraEn = now()->addMinutes(max(1, $minutos))->toDateTimeString();
            $comment = sprintf('%s|ip=%s|exp=%s', self::TEMP_PAY_COMMENT_PREFIX, $resolvedIp, $expiraEn);

            $binding = $this->buscarBindingTemporalPorIp($client, $resolvedIp);

            if ($binding) {
                $setQuery = new Query('/ip/hotspot/ip-binding/set');
                $setQuery->equal('.id', $binding['.id']);
                $setQuery->equal('type', 'bypassed');
                $setQuery->equal('comment', $comment);
                if ($mac) {
                    $setQuery->equal('mac-address', $mac);
                }
                $client->query($setQuery)->read();

                return true;
            }

            $addQuery = new Query('/ip/hotspot/ip-binding/add');
            $addQuery->equal('address', $resolvedIp);
            $addQuery->equal('type', 'bypassed');
            $addQuery->equal('comment', $comment);
            if ($mac) {
                $addQuery->equal('mac-address', $this->normalizarMac($mac));
            }

            $client->query($addQuery)->read();

            return true;
        } catch (\Throwable $e) {
            Log::error('MikroTik: error al habilitar acceso temporal de pago', [
                'zona_id' => $this->zona->id,
                'ip' => $ip,
                'mac' => $mac,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function resolverIpParaPagoTemporal(?string $ip, ?string $mac = null): ?string
    {
        try {
            $client = $this->conectar();
            return $this->resolverIpClienteHotspot($client, $ip, $mac);
        } catch (\Throwable $e) {
            Log::warning('MikroTik: no se pudo resolver IP para pago temporal', [
                'zona_id' => $this->zona->id,
                'ip' => $ip,
                'mac' => $mac,
                'error' => $e->getMessage(),
            ]);

            return $ip;
        }
    }

    public function revocarAccesoPagoTemporal(?string $ip): bool
    {
        if (! $ip) {
            return true;
        }

        try {
            $client = $this->conectar();
            $binding = $this->buscarBindingTemporalPorIp($client, $ip);

            if (! $binding) {
                return true;
            }

            $removeQuery = new Query('/ip/hotspot/ip-binding/remove');
            $removeQuery->equal('.id', $binding['.id']);
            $client->query($removeQuery)->read();

            return true;
        } catch (\Throwable $e) {
            Log::error('MikroTik: error al revocar acceso temporal de pago', [
                'zona_id' => $this->zona->id,
                'ip' => $ip,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
