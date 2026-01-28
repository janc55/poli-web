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

        // ==================== PERMISOS POR MODELO ====================
        $modelPermissions = [
            // Modelo: Cita (Appointment)
            'cita' => [
                'ver_todas',
                'ver_propias', 
                'crear',
                'editar_todas',
                'editar_propias',
                'cancelar',
                'eliminar',
                'reasignar'
            ],

            // Modelo: Paciente (Patient)
            'paciente' => [
                'ver_todos',
                'ver_asignados',
                'crear',
                'editar',
                'eliminar',
                'exportar'
            ],

            // Modelo: Historial Médico (MedicalRecord)
            'historialmedico' => [
                'ver_todos',
                'ver_asignados',
                'crear',
                'editar_todos',
                'editar_asignados',
                'eliminar'
            ],

            // Modelo: Usuario (User)
            'usuario' => [
                'ver_todos',
                'crear',
                'editar',
                'eliminar',
                'asignar_roles'
            ],

            // Modelo: Servicio (Service)
            'servicio' => [
                'ver_todos',
                'crear',
                'editar',
                'eliminar'
            ],

            // Modelo: Tipo de Servicio (ServiceType)
            'tiposervicio' => [
                'ver_todos',
                'crear',
                'editar',
                'eliminar'
            ],

            // Modelo: Doctor (Doctor)
            'doctor' => [
                'ver_todos',
                'crear',
                'editar',
                'eliminar'
            ],

            // Modelo: Horario Doctor (DoctorSchedule)
            'horariodoctor' => [
                'ver_todos',
                'crear',
                'editar',
                'eliminar'
            ],

            // Modelo: Servicio Doctor (DoctorService)
            'serviciodoctor' => [
                'ver_todos',
                'crear',
                'editar',
                'eliminar'
            ],

            // Permisos generales del sistema
            'configuracion' => [
                'gestionar',
                'ver_auditoria'
            ]
        ];

        // Crear todos los permisos
        foreach ($modelPermissions as $modelo => $acciones) {
            foreach ($acciones as $accion) {
                Permission::firstOrCreate([
                    'name' => "{$modelo}.{$accion}",
                    'guard_name' => 'web'
                ]);
            }
        }

        // ==================== ROLES ====================

        // Rol Admin - Acceso total
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        // Rol Doctor
        $doctorRole = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $doctorRole->givePermissionTo([
            // Pacientes
            'paciente.ver_asignados',
            'paciente.crear',
            'paciente.editar',
            
            // Citas
            'cita.ver_propias',
            'cita.editar_propias',
            'cita.cancelar',
            
            // Historiales Médicos
            'historialmedico.ver_asignados',
            'historialmedico.crear',
            'historialmedico.editar_asignados',
            
            // Propio perfil
            'usuario.editar' // Para editar su propio perfil
        ]);

        // Rol Recepcionista
        $recepcionistaRole = Role::firstOrCreate(['name' => 'recepcionista', 'guard_name' => 'web']);
        $recepcionistaRole->givePermissionTo([
            // Citas
            'cita.ver_todas',
            'cita.crear',
            'cita.editar_todas',
            'cita.cancelar',
            'cita.reasignar',
            
            // Pacientes
            'paciente.ver_todos',
            'paciente.crear',
            'paciente.editar',
            
            // Doctores (para asignar citas)
            'doctor.ver_todos',
            
            // Servicios
            'servicio.ver_todos',
            'tiposervicio.ver_todos',
            
            // Propio perfil
            'usuario.editar'
        ]);

        // Rol Paciente
        $pacienteRole = Role::firstOrCreate(['name' => 'paciente', 'guard_name' => 'web']);
        $pacienteRole->givePermissionTo([
            // Sus propias citas
            'cita.ver_propias',
            'cita.crear',
            'cita.cancelar',
            
            // Su propio historial médico (si decides implementarlo)
            // 'historialmedico.ver_propio',
            
            // Propio perfil
            'usuario.editar'
        ]);

       
    }

}

