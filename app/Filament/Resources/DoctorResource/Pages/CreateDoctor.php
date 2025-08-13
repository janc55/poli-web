<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use App\Filament\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDoctor extends CreateRecord
{
    protected static string $resource = DoctorResource::class;

        
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Validar email único
        if (User::where('email', $data['email'])->exists()) {
            throw new \Exception('El correo electrónico ya está registrado');
        }

        // Crear usuario
        $user = User::create([
            'name' => trim($data['first_name'] . ' ' . $data['last_name']),
            'email' => $data['email'],
            'password' => bcrypt($data['ci']),
        ]);
        
        $user->assignRole('doctor');
        $data['user_id'] = $user->id;
        
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
    

        unset($data['email']);       // Eliminado porque no pertenece al modelo Doctor
        unset($data['specialties']); // Eliminado porque se maneja por relación

        return static::getModel()::create($data);
    }
}
