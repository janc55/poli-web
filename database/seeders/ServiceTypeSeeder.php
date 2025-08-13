<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Especialidades Médicas'],
            ['name' => 'Imagenología'],
            ['name' => 'Laboratorio'],
        ];

        foreach ($types as $type) {
            ServiceType::firstOrCreate(['name' => $type['name']]);
        }
    }
}
