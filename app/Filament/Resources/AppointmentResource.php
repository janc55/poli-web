<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Service;
use App\Models\ServiceType;
use Carbon\CarbonPeriod;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Citas';
    protected static ?string $pluralModelLabel = 'Citas';
    protected static ?string $modelLabel = 'Cita';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'confirmed')
            ->whereDate('scheduled_at', Carbon::today())
            ->count();
    }

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('patient_id')
                    ->label('Paciente')
                    ->relationship(
                        name: 'patient',
                        titleAttribute: 'ci',
                        modifyQueryUsing: fn (Builder $query) => $query->select(['id', 'ci', 'first_name', 'last_name'])
                    )
                    ->searchable(['first_name', 'last_name', 'ci'])
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                    ->required()
                    ->preload()
                    ->searchDebounce(500)
                    ->native(false)
                    ->optionsLimit(100)
                    ->loadingMessage('Buscando pacientes...')
                    ->noSearchResultsMessage('No se encontraron pacientes')
                    ->searchPrompt('Buscar por nombre, apellido o CI')
                    ->hidden(fn () => auth()->user()->hasRole('paciente')),

                Select::make('serviceType')
                    ->label('Tipo de Servicio')
                    ->options(ServiceType::pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->reactive(), // Necesario para que se actualicen los demás campos

                Select::make('service_id')
                    ->label('Servicio')
                    ->relationship('service', 'name', modifyQueryUsing: function (Builder $query, callable $get) {
                        $serviceTypeId = $get('serviceType');
                        if ($serviceTypeId) {
                            $query->where('service_type_id', $serviceTypeId);
                        }
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                    ->preload()
                    ->searchable()
                    ->reactive() // Hacemos reactivo para filtrar doctores
                    ->required(),

                // Campo para seleccionar al doctor (visible solo para 'Especialidades Médicas')
                Select::make('doctor_id')
                    ->label('Doctor')
                    ->relationship('doctor', 'first_name', modifyQueryUsing: function (Builder $query, callable $get) {
                        $serviceId = $get('service_id');
                        if ($serviceId) {
                            // Filtramos doctores que atienden el servicio seleccionado
                            $query->whereHas('services', function (Builder $query) use ($serviceId) {
                                $query->where('services.id', $serviceId);
                            });
                        }
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                    ->preload()
                    ->searchable()
                    ->reactive() // Reactivo para habilitar el selector de horarios
                    ->required()
                    ->visible(fn (callable $get) => 
                        ServiceType::find($get('serviceType'))?->name === 'Especialidades Médicas'),

                // Campo para elegir el horario disponible (dinámico)
                Select::make('scheduled_at')
                    ->label('Fecha y Hora')
                    ->options(function (callable $get) {
                        $doctorId = $get('doctor_id');
                        $serviceId = $get('service_id');

                        if (!$doctorId || !$serviceId) {
                            return [];
                        }

                        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
                            ->where('service_id', $serviceId)
                            ->get();

                        $availableTimes = collect();

                        foreach ($schedules as $schedule) {
                            $start = Carbon::parse($schedule->start_time);
                            $end = Carbon::parse($schedule->end_time);
                            $duration = $schedule->appointment_duration;

                            $slots = CarbonPeriod::create($start, $duration . ' minutes', $end);

                            foreach ($slots as $slot) {
                                // No queremos slots al final si duran menos que la duración completa
                                if ($slot->copy()->addMinutes($duration)->gt($end)) {
                                    continue;
                                }

                                // Verificar si el slot ya está ocupado
                                $isOccupied = Appointment::where('doctor_id', $doctorId)
                                    ->where('service_id', $serviceId)
                                    ->whereBetween('scheduled_at', [
                                        $slot,
                                        $slot->copy()->addMinutes($duration)->subMinute()
                                    ])
                                    ->exists();

                                if (!$isOccupied) {
                                    // Agregamos el slot si está disponible
                                    $availableTimes->push([
                                        'time' => $slot->format('H:i'),
                                        'label' => $slot->format('H:i') . ' - ' . $slot->copy()->addMinutes($duration)->format('H:i'),
                                    ]);
                                }
                            }
                        }

                        // Preparamos las opciones para el Select
                        return $availableTimes->pluck('label', 'time');

                    })
                    ->required()
                    ->placeholder('Selecciona un doctor para ver los horarios'),

                // Campo de estado y notas
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                    ])
                    ->default('pending')
                    ->required()
                    ->hidden(fn () => auth()->user()->hasRole('paciente')),

                Textarea::make('notes')
                    ->label('Notas'),
            ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.fullName')->label('Paciente')->searchable(),
                TextColumn::make('service.name')->label('Servicio'),
                TextColumn::make('scheduled_at')->label('Fecha')->dateTime(),
                TextColumn::make('status')->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                    })
            ])
            ->defaultSort('scheduled_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('confirmar')
                ->label('Confirmar Cita')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->hidden(fn (Appointment $record): bool => $record->status !== 'pending') // Solo visible si está pendiente
                ->action(function (Appointment $record) {
                    $record->update(['status' => 'confirmed']);

                    Notification::make()
                        ->title('Cita Confirmada')
                        ->body('La cita ha sido confirmada exitosamente.')
                        ->success()
                        ->send();
                }),
                Action::make('cancelar')
                ->label('Cancelar Cita')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation() // Pide confirmación antes de ejecutar
                ->hidden(fn (Appointment $record): bool => $record->status === 'cancelled') // Oculta si ya está cancelada
                ->action(function (Appointment $record) {
                    $record->update(['status' => 'cancelled']);
                    // Borra el caché del widget para que se actualice inmediatamente
                    Cache::forget('today-appointments');

                    Notification::make()
                        ->title('Cita Cancelada')
                        ->body('La cita ha sido cancelada exitosamente.')
                        ->success()
                        ->send();
                }),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
