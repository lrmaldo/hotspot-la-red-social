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

        // Crear permisos
        $permissions = [
            'zonas.ver',
            'zonas.crear',
            'zonas.editar',
            'zonas.eliminar',
            'campanas.ver',
            'campanas.crear',
            'campanas.editar',
            'campanas.eliminar',
            'configuracion.editar',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear rol admin y asignar todos los permisos
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions(Permission::all());
    }
}
