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
}
