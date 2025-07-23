<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class LatestPatientsTable extends BaseWidget
{
    protected static ?string $heading = 'Pacientes Recientes';

    public static function canView(): bool
    {
        return Auth::user()?->hasRole('admin');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Patient::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('Apellido')
                    ->searchable(),

                Tables\Columns\TextColumn::make('ci')
                    ->label('CI')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado')
                    ->since(),
            ]);
    }
}
