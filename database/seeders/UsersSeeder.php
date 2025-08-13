<?php

namespace Database\Seeders;

use App\Models\Doctor;
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
        $doctorUser = User::firstOrCreate(
            ['email' => 'doctor@unior.test'],
            [
                'name' => 'Dr. Ana María Gómez',
                'password' => Hash::make('password'),
            ]
        );
        $doctorUser->assignRole($doctorRole); // o crear role 'doctor' más adelante

        Doctor::firstOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'first_name'     => 'Ana María',
                'last_name'      => 'Gómez',
                'ci'             => '7654321',
                'ci_extension'   => 'LP',
                'birth_date'     => '1985-05-15',
                'gender'         => 'Femenino',
                'address'        => 'Calle Principal 123',
                'phone'          => '71111111',
                'license_number' => 'MED-0012345',
                'bio'            => 'Especialista en cardiología con 10 años de experiencia.',
            ]
        );

        

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
