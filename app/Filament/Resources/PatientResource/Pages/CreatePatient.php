<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Primero creamos el usuario
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['ci']), // CI como contraseña inicial
        ]);

        // Asignamos el rol usando Spatie Permission
        $user->assignRole('paciente');

        // Agregamos el user_id al array de datos del paciente
        $data['user_id'] = $user->id;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Eliminamos los campos que no pertenecen al paciente
        unset($data['email']); // El email ya está en el usuario
        
        return static::getModel()::create($data);
    }
}
