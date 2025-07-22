<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PatientsCount extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pacientes Registrados', Patient::count())
                ->description('Total de pacientes en el sistema')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary')
                ->url(route('filament.admin.resources.patients.index'))
                //->chart([7, 2, 10, 3, 15, 4, 17]) // Datos de ejemplo para gráfico
                ->extraAttributes([
                    'class' => 'cursor-pointer', // Opcional: para hacer clickeable
                ]),
        ];
    }
}
