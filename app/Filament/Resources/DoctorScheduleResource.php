<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorScheduleResource\Pages;
use App\Filament\Resources\DoctorScheduleResource\RelationManagers;
use App\Models\DoctorSchedule;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DoctorScheduleResource extends Resource
{
    protected static ?string $model = DoctorSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Horarios de Doctores';
    protected static ?string $navigationGroup = 'Gestión Médica';
    protected static ?string $modelLabel = 'Horario de Doctor';
    protected static ?string $pluralModelLabel = 'Horarios de Doctores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('doctor_id')
                    ->label('Doctor')
                    ->relationship('doctor', 'first_name')
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->preload(),
                
                Select::make('service_id')
                    ->label('Servicio')
                    ->relationship('service', 'name', modifyQueryUsing: function (Builder $query, callable $get) {
                        $doctorId = $get('doctor_id');
                        if ($doctorId) {
                            // Filtramos los servicios para mostrar solo los asignados a este doctor
                            $query->whereHas('doctors', function (Builder $query) use ($doctorId) {
                                $query->where('doctors.id', $doctorId);
                            });
                        } else {
                            // Ocultamos las opciones si no hay doctor seleccionado
                            $query->whereNull('id');
                        }
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Selecciona un servicio que el doctor pueda atender.'), // Texto de ayuda para el usuario
                
                Select::make('day_of_week')
                    ->label('Día de la semana')
                    ->options([
                        'lunes' => 'Lunes',
                        'martes' => 'Martes',
                        'miércoles' => 'Miércoles',
                        'jueves' => 'Jueves',
                        'viernes' => 'Viernes',
                        'sábado' => 'Sábado',
                        'domingo' => 'Domingo',
                    ])
                    ->multiple()
                    ->required(),
                
                TimePicker::make('start_time')
                    ->label('Hora de inicio')
                    ->required(),
                
                TimePicker::make('end_time')
                    ->label('Hora de finalización')
                    ->required(),

                TextInput::make('appointment_duration')
                    ->label('Duración de la cita (minutos)')
                    ->numeric()
                    ->required()
                    ->default(30),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doctor.first_name')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('Día')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Inicio')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Fin')
                    ->sortable(),

                Tables\Columns\TextColumn::make('appointment_duration')
                    ->label('Duración')
                    ->sortable(),
            ])
            ->groups([
            'doctor.first_name',
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctorSchedules::route('/'),
            'create' => Pages\CreateDoctorSchedule::route('/create'),
            'edit' => Pages\EditDoctorSchedule::route('/{record}/edit'),
        ];
    }
}
