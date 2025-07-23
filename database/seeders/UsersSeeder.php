<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Patient;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Roles (debieron existir por RolesAndPermissionsSeeder; aseguramos con firstOrCreate)
        $adminRole   = Role::firstOrCreate(['name' => 'admin']);
        $doctorRole   = Role::firstOrCreate(['name' => 'doctor']);
        $staffRole   = Role::firstOrCreate(['name' => 'recepcionista']);
        $patientRole = Role::firstOrCreate(['name' => 'paciente']);

        // --- Admin ---
        $admin = User::firstOrCreate(
            ['email' => 'admin@unior.test'],
            [
                'name' => 'Administrador UNIOR',
                'password' => Hash::make('password'), // cambia en prod
            ]
        );
        $admin->assignRole($adminRole);

        // --- Staff (recepción) ---
        $staff = User::firstOrCreate(
            ['email' => 'recepcion@unior.test'],
            [
                'name' => 'Recepción UNIOR',
                'password' => Hash::make('password'),
            ]
        );
        $staff->assignRole($staffRole);

        // --- Médico / Staff clínico ---
        $doctor = User::firstOrCreate(
            ['email' => 'medico@unior.test'],
            [
                'name' => 'Dr. Demo UNIOR',
                'password' => Hash::make('password'),
            ]
        );
        $doctor->assignRole($doctorRole); // o crear role 'doctor' más adelante

        // --- Paciente con cuenta ---
        $patientUser = User::firstOrCreate(
            ['email' => 'paciente@unior.test'],
            [
                'name' => 'Paciente Demo',
                'password' => Hash::make('password'),
            ]
        );
        $patientUser->assignRole($patientRole);

        // Crear registro Patient vinculado
        Patient::firstOrCreate(
            ['user_id' => $patientUser->id],
            [
                'first_name' => 'Paciente',
                'last_name'  => 'Demo',
                'ci'         => '1234567',
                'birth_date' => '1990-01-01',
                'gender'     => 'Masculino',
                'address'    => 'Dirección demo',
                'phone'      => '70000000',
            ]
        );
    }
}
