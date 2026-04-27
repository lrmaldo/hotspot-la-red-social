<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@laredsocial.com'],
            [
                'name' => 'Manuel García',
                'password' => Hash::make('password'),
            ]
        );

        // Asignar el rol admin al usuario
        $roleAdmin = Role::where('name', 'admin')->first();
        if ($roleAdmin) {
            $admin->assignRole($roleAdmin);
        }
    }
}
