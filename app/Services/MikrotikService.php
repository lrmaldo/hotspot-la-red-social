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
            'pass'    => $this->zona->mikrotik_password
                ? decrypt($this->zona->mikrotik_password)
                : config('mikrotik.password'),
            'port'    => (int) config('mikrotik.port', 8728),
            'timeout' => (int) config('mikrotik.timeout', 5),
        ]);
    }

    public function crearUsuarioHotspot(Voucher $voucher): bool
    {
        try {
            $client = $this->conectar();

            $query = new Query('/ip/hotspot/user/add');
            $query->equal('name', $voucher->codigo);
            $query->equal('password', $voucher->codigo);
            $query->equal('profile', 'default');
            $query->equal('comment', 'Voucher #' . $voucher->id);
            $query->equal('limit-uptime', $voucher->plan->duracion_minutos . 'm');

            $client->query($query)->read();

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

            $query = new Query('/ip/hotspot/user/print');
            $query->where('name', $codigo);
            $users = $client->query($query)->read();

            if (empty($users)) {
                return false;
            }

            $removeQuery = new Query('/ip/hotspot/user/remove');
            $removeQuery->equal('.id', $users[0]['.id']);
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
