<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use App\Models\Zona;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class VpnL2tpService
{
    /**
     * Configuración del servidor VPN (leída de settings con valores por defecto
     * que coinciden con el VPS de producción).
     *
     * @return array{public_ip:string,psk:string,server_ip:string,pool_start:string,pool_end:string}
     */
    public function serverConfig(): array
    {
        return [
            'public_ip' => (string) Setting::getValue('vpn_public_ip', '165.22.182.9'),
            'psk' => (string) Setting::getValue('vpn_psk', ''),
            'server_ip' => (string) Setting::getValue('vpn_server_ip', '10.200.0.1'),
            'pool_start' => (string) Setting::getValue('vpn_pool_start', '10.200.0.10'),
            'pool_end' => (string) Setting::getValue('vpn_pool_end', '10.200.0.100'),
        ];
    }

    public function saveServerConfig(string $publicIp, string $psk, string $serverIp, string $poolStart, string $poolEnd): void
    {
        Setting::updateOrCreate(['key' => 'vpn_public_ip'], ['value' => $publicIp]);
        Setting::updateOrCreate(['key' => 'vpn_psk'], ['value' => $psk]);
        Setting::updateOrCreate(['key' => 'vpn_server_ip'], ['value' => $serverIp]);
        Setting::updateOrCreate(['key' => 'vpn_pool_start'], ['value' => $poolStart]);
        Setting::updateOrCreate(['key' => 'vpn_pool_end'], ['value' => $poolEnd]);
    }

    /**
     * Devuelve la siguiente IP libre del pool que no esté usada por otra zona.
     */
    public function nextFreeIp(): string
    {
        $cfg = $this->serverConfig();
        $start = ip2long($cfg['pool_start']);
        $end = ip2long($cfg['pool_end']);

        if ($start === false || $end === false || $start > $end) {
            throw new RuntimeException('Rango de IPs del pool VPN inválido.');
        }

        $usadas = Zona::whereNotNull('vpn_tunnel_ip')
            ->pluck('vpn_tunnel_ip')
            ->map(fn ($ip) => ip2long((string) $ip))
            ->filter()
            ->all();

        for ($ip = $start; $ip <= $end; $ip++) {
            if (! in_array($ip, $usadas, true)) {
                return long2ip($ip);
            }
        }

        throw new RuntimeException('No hay IPs libres en el pool de la VPN.');
    }

    /**
     * Genera el usuario L2TP a partir del identificador de la zona.
     */
    public function buildUsername(Zona $zona): string
    {
        $base = Str::slug((string) $zona->id_personalizado, '-');
        $base = preg_replace('/[^A-Za-z0-9._-]/', '', $base) ?: 'zona';

        return 'z-'.Str::limit($base, 50, '');
    }

    /**
     * Provisiona (o re-provisiona) la cuenta L2TP de la zona en el VPS y la
     * persiste. Asigna también el hotspot_host a la IP del túnel.
     */
    public function provision(Zona $zona): void
    {
        $user = $zona->vpn_l2tp_user ?: $this->buildUsername($zona);
        $password = $zona->vpn_l2tp_password ?: Str::password(20, symbols: false);
        $ip = $zona->vpn_tunnel_ip ?: $this->nextFreeIp();

        $this->runProvision('add', $user, $password, $ip);

        $zona->forceFill([
            'vpn_l2tp_user' => $user,
            'vpn_l2tp_password' => $password,
            'vpn_tunnel_ip' => $ip,
            'vpn_provisioned_at' => now(),
            'hotspot_host' => $ip,
        ])->save();
    }

    /**
     * Elimina la cuenta L2TP de la zona en el VPS y limpia los campos.
     */
    public function deprovision(Zona $zona): void
    {
        if ($zona->vpn_l2tp_user) {
            $this->runProvision('remove', $zona->vpn_l2tp_user);
        }

        $zona->forceFill([
            'vpn_l2tp_user' => null,
            'vpn_l2tp_password' => null,
            'vpn_tunnel_ip' => null,
            'vpn_provisioned_at' => null,
        ])->save();
    }

    /**
     * Ejecuta el script de provisión con sudo (sin shell, argumentos como array).
     */
    private function runProvision(string $action, string $user, ?string $password = null, ?string $ip = null): void
    {
        $cmd = ['sudo', '/usr/local/bin/lrs-l2tp-provision', $action, $user];

        if ($action === 'add') {
            $cmd[] = (string) $password;
            $cmd[] = (string) $ip;
        }

        $process = new Process($cmd);
        $process->setTimeout(20);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                'Fallo al provisionar L2TP en el VPS: '.trim($process->getErrorOutput() ?: $process->getOutput())
            );
        }
    }

    /**
     * Genera los comandos RouterOS para copiar/pegar en la terminal del MikroTik.
     */
    public function mikrotikScript(Zona $zona): string
    {
        $cfg = $this->serverConfig();
        $user = $zona->vpn_l2tp_user ?? '<sin-provisionar>';
        $pass = $zona->vpn_l2tp_password ?? '<sin-provisionar>';
        $psk = $cfg['psk'] !== '' ? $cfg['psk'] : '<configura-el-PSK>';
        $pubip = $cfg['public_ip'];
        $serverIp = $cfg['server_ip'];

        $apiUser = $zona->mikrotik_user ?: 'apiuser';
        $apiPass = $zona->mikrotik_password ?: '<define-una-clave>';
        $apiPort = $zona->mikrotik_port ?: 8728;

        return <<<RSC
# ===== VPN L2TP/IPsec hacia el VPS lrs-wifi ({$zona->nombre}) =====
# Pega TODO este bloque en la terminal del MikroTik.

# 1) Cliente L2TP/IPsec hacia el VPS
/interface l2tp-client
add name=vpn-lrswifi connect-to={$pubip} user={$user} password="{$pass}" \\
    use-ipsec=yes ipsec-secret="{$psk}" add-default-route=no \\
    disabled=no comment="VPN portal lrs-wifi"

# 2) Usuario para el API (lo usa el portal para crear vouchers, etc.)
/user add name={$apiUser} password="{$apiPass}" group=full \\
    comment="API portal lrs-wifi"

# 3) Habilitar el servicio API SOLO desde el VPS (por el tunel)
/ip service set api address={$serverIp}/32 port={$apiPort} disabled=no

# 4) Firewall: aceptar el API unicamente desde el VPS
/ip firewall filter
add chain=input action=accept protocol=tcp dst-port={$apiPort} \\
    src-address={$serverIp} comment="API desde VPS lrs-wifi" place-before=0

# ===== Listo. El portal contactara al router por {$zona->vpn_tunnel_ip} =====
RSC;
    }
}
