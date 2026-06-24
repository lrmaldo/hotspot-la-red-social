<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar el cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear todos los permisos del catálogo (config/permisos.php).
        $catalogo = collect(config('permisos', []))
            ->flatMap(fn (array $permisos) => array_keys($permisos))
            ->all();

        foreach ($catalogo as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Purgar permisos antiguos que ya no están en el catálogo
        // (esquema previo zonas.ver/crear/... reemplazado por seccion.*).
        Permission::whereNotIn('name', $catalogo)->delete();

        // Roles del sistema: siempre con todos los permisos, no editables
        // ni eliminables desde la UI.
        foreach (['admin', 'super_admin'] as $nombre) {
            Role::firstOrCreate(['name' => $nombre])
                ->syncPermissions(Permission::all());
        }
    }
}
