<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class PatientsCount extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $count = Cache::remember('patients-count', now()->addHours(1), function () {
            return Patient::count();
        });

        $chartData = Cache::remember('patients-chart-data', now()->addDay(), function () {
            return Patient::query()
                ->selectRaw('count(*) as count, DATE_FORMAT(created_at, "%Y-%m") as month')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count')
                ->toArray();
        });

        return [
            Stat::make('Pacientes Registrados', $count)
                ->description('Total en el sistema')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary')
                ->chart($chartData)
                ->url(route('filament.admin.resources.patients.index'))
                ->extraAttributes(['class' => 'cursor-pointer hover:bg-gray-50']),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
