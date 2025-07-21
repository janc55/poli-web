<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // --- Crear Permisos ---
        $permissions = [
            'crear citas',
            'ver propias citas',
            'gestionar citas',
            'gestionar pacientes',
            'gestionar usuarios',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- Crear Roles ---
        $adminRole   = Role::firstOrCreate(['name' => 'admin']);
        $staffRole   = Role::firstOrCreate(['name' => 'staff']);
        $patientRole = Role::firstOrCreate(['name' => 'patient']);

        // --- Asignar Permisos a Roles ---
        $adminRole->givePermissionTo(Permission::all());

        $staffRole->givePermissionTo([
            'gestionar citas',
            'gestionar pacientes',
        ]);

        $patientRole->givePermissionTo([
            'crear citas',
            'ver propias citas',
        ]);
    }
}

