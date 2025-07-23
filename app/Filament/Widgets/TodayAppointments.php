<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class TodayAppointments extends BaseWidget
{
    protected function getStats(): array
    {
        $todayData = Cache::remember('today-appointments', now()->addMinutes(15), function () {
            return [
                'total' => Appointment::whereDate('scheduled_at', today())->count(),
                'confirmed' => Appointment::whereDate('scheduled_at', today())
                    ->where('status', 'confirmed')->count(),
                'cancelled' => Appointment::whereDate('scheduled_at', today())
                    ->where('status', 'cancelled')->count(),
            ];
        });

        return [
            Stat::make('Citas Hoy', $todayData['total'])
                ->description('Total programadas')
                ->descriptionIcon('heroicon-o-calendar')
                ->url(route('filament.admin.resources.appointments.index'))
                ->color('primary'),
                
            Stat::make('Confirmadas', $todayData['confirmed'])
                ->description('Citas confirmadas')
                ->descriptionIcon('heroicon-o-check')
                ->url(route('filament.admin.resources.appointments.index', [
                    'tableFilters[status][value]' => 'confirmed'
                ]))
                ->color('success'),
                
            Stat::make('Canceladas', $todayData['cancelled'])
                ->description('Citas canceladas')
                ->descriptionIcon('heroicon-o-x-mark')
                ->url(route('filament.admin.resources.appointments.index', [
                    'tableFilters[status][value]' => 'cancelled'
                ]))
                ->color('danger'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'doctor', 'recepcionista']);
    }
}
