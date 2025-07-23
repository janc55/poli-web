<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;
        
        if ($user->hasRole('paciente')) {
            // Verificar que el usuario tenga un paciente asociado
            if (!$user->patient) {
                throw new \Exception('El usuario paciente no tiene un perfil de paciente asociado.');
            }
            
            $data['patient_id'] = $user->patient->id;
            $data['status'] = 'pending';
            
            // También puedes agregar lógica adicional aquí:
            // - Validar horarios disponibles
            // - Verificar límite de citas
            // - Asignar doctor automáticamente si corresponde
        }
        
        return $data;
        
    }
}
