<?php

namespace App\Filament\Resources\DoctorScheduleResource\Pages;

use App\Filament\Resources\DoctorScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDoctorSchedule extends CreateRecord
{
    protected static string $resource = DoctorScheduleResource::class;

    // protected function handleRecordCreation(array $data): Model
    // {
    //     // 1. Obtener el array de días de la data
    //     $daysOfWeek = $data['day_of_week'];

    //     // 2. Eliminar el array de días para evitar el error de base de datos
    //     unset($data['day_of_week']);

    //     // 3. Iterar sobre cada día y crear un nuevo registro
    //     $records = collect($daysOfWeek)->map(function ($day) use ($data) {
    //         $data['day_of_week'] = $day;
    //         return static::getModel()::create($data);
    //     });

    //     // 4. Devolver el primer registro creado (o el que consideres principal)
    //     return $records->first();
    // }
}
