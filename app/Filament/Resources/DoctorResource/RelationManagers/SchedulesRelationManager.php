<?php

namespace App\Filament\Resources\DoctorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day_of_week')
                    ->options([
                        'Lunes' => 'Lunes', 
                        'Martes' => 'Martes',
                        'Miércoles' => 'Miércoles',
                        'Jueves' => 'Jueves',
                        'Viernes' => 'Viernes',
                        'Sábado' => 'Sábado',
                        'Domingo' => 'Domingo',
                    ])
                    ->required()
                    ->multiple()
                    ->columnSpanFull(), // Asegura que el selector ocupe todo el ancho
                
                Forms\Components\Select::make('service_id')
                    ->label('Servicio')
                    ->relationship('service', 'name', modifyQueryUsing: function (Builder $query) {
                        // Obtenemos el registro padre (el Doctor)
                        $doctorId = $this->ownerRecord->id; // 👈 Aquí se obtiene el ID del Doctor

                        // Filtramos los servicios:
                        // Asegúrate de que el modelo Service tenga la relación 'doctors'
                        $query->whereHas('doctors', function (Builder $q) use ($doctorId) {
                            $q->where('doctors.id', $doctorId);
                        });
                        
                        // No necesitamos la lógica de 'else { $query->whereNull('id'); }'
                        // porque el $doctorId siempre está disponible en el Relation Manager
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Selecciona un servicio que el doctor pueda atender.'), // Texto de ayuda para el usuario

                Forms\Components\TimePicker::make('start_time')
                    ->label('Hora de inicio')
                    ->seconds(false)
                    ->required(),
                
                Forms\Components\TimePicker::make('end_time')
                    ->label('Hora de finalización')
                    ->seconds(false)
                    ->required(),
                    
                Forms\Components\TextInput::make('appointment_duration')
                    ->label('Duración (minutos)')
                    ->numeric()
                    ->required(),
                    ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('service.name')
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('Día')
                    ->badge()
                    ->sortable(), // No necesitas ->badge() si es un solo día (string)
                    
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Inicio')
                    ->time('H:i'), // Formateo correcto
                    
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Fin')
                    ->time('H:i'), // Formateo correcto
                    
                Tables\Columns\TextColumn::make('appointment_duration')
                    ->label('Duración (min)'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
