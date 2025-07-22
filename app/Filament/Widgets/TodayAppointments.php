<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodayAppointments extends BaseWidget
{
    protected function getStats(): array
    {
        $todayCount = Appointment::whereDate('scheduled_at', today())->count();
        $confirmedCount = Appointment::whereDate('scheduled_at', today())
            ->where('status', 'confirmed')
            ->count();

        return [
            Stat::make('Citas Hoy', $todayCount)
                ->description('Total de citas programadas hoy')
                ->descriptionIcon('heroicon-o-calendar')
                ->url(route('filament.admin.resources.appointments.index'))
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer', // Opcional: para hacer clickeable
                ]),
                
            Stat::make('Citas Confirmadas', $confirmedCount)
                ->description('Confirmadas para hoy')
                ->descriptionIcon('heroicon-o-check-badge')
                ->url(route('filament.admin.resources.appointments.index'))
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer', // Opcional: para hacer clickeable
                ]),
        ];
    }
}
