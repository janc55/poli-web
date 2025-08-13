<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
        {
            // Obtenemos los tipos de servicio por nombre
            $especialidadesId = ServiceType::where('name', 'Especialidades Médicas')->value('id');
            $imagenologiaId   = ServiceType::where('name', 'Imagenología')->value('id');
            $laboratorioId    = ServiceType::where('name', 'Laboratorio')->value('id');

            // Servicios de Especialidades Médicas
            $especialidades = [
                'Cardiología',
                'Pediatría',
                'Neurología',
            ];

            foreach ($especialidades as $name) {
                Service::firstOrCreate([
                    'name' => $name,
                    'service_type_id' => $especialidadesId,
                ]);
            }

            // Servicios de Imagenología
            $imagenologia = [
                'Radiografía',
                'Resonancia Magnética',
                'Tomografía Computarizada',
            ];

            foreach ($imagenologia as $name) {
                Service::firstOrCreate([
                    'name' => $name,
                    'service_type_id' => $imagenologiaId,
                ]);
            }

            // Servicios de Laboratorio
            $laboratorio = [
                'Laboratorio',
            ];

            foreach ($laboratorio as $name) {
                Service::firstOrCreate([
                    'name' => $name,
                    'service_type_id' => $laboratorioId,
                ]);
            }
        }
}
