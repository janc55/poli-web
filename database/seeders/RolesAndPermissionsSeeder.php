<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // --- Crear Permisos ---
        // Permisos para Citas
        $citasPermissions = [
            'ver todas las citas',
            'ver propias citas',
            'crear citas',
            'editar cualquier cita',
            'editar propias citas',
            'cancelar citas',
            'eliminar citas',
            'reasignar citas'
        ];

        // Permisos para Pacientes
        $pacientesPermissions = [
            'ver todos los pacientes',
            'ver pacientes asignados',
            'crear pacientes',
            'editar pacientes',
            'eliminar pacientes',
            'exportar datos de pacientes'
        ];

        // Permisos para Historiales Médicos
        $historialPermissions = [
            'ver todos los historiales',
            'ver historiales asignados',
            'crear historiales',
            'editar cualquier historial',
            'editar historiales asignados',
            'eliminar historiales'
        ];

        // Permisos para Usuarios
        $usuariosPermissions = [
            'ver todos los usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'asignar roles'
        ];

        // Permisos para Configuración
        $configPermissions = [
            'gestionar configuración',
            'ver registros de auditoría'
        ];

        // Crear todos los permisos
        $allPermissions = array_merge(
            $citasPermissions,
            $pacientesPermissions,
            $historialPermissions,
            $usuariosPermissions,
            $configPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ==================== ROLES ====================

        // Rol Admin - Acceso total
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        // Rol Doctor
        $doctorRole = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $doctorRole->givePermissionTo([
            'ver pacientes asignados',
            'crear pacientes',
            'editar pacientes',
            'ver propias citas',
            'editar propias citas',
            'cancelar citas',
            'ver historiales asignados',
            'crear historiales',
            'editar historiales asignados'
        ]);

        // Rol Recepcionista (Staff)
        $recepcionistaRole = Role::firstOrCreate(['name' => 'recepcionista', 'guard_name' => 'web']);
        $recepcionistaRole->givePermissionTo([
            'ver todas las citas',
            'crear citas',
            'editar cualquier cita',
            'cancelar citas',
            'reasignar citas',
            'ver todos los pacientes',
            'crear pacientes',
            'editar pacientes'
        ]);

        // Rol Paciente
        $pacienteRole = Role::firstOrCreate(['name' => 'paciente', 'guard_name' => 'web']);
        $pacienteRole->givePermissionTo([
            'ver propias citas',
            'crear citas',
            'cancelar citas'
        ]);
    }
}

