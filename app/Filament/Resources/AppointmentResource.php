<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

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
                    ->searchDebounce(500) // Reduce las consultas durante la escritura
                    ->native(false) // Mejor rendimiento en el cliente
                    ->optionsLimit(100) // Limita el número de opciones cargadas inicialmente
                    ->loadingMessage('Buscando pacientes...')
                    ->noSearchResultsMessage('No se encontraron pacientes')
                    ->searchPrompt('Buscar por nombre, apellido o CI'),

                Select::make('service')
                    ->label('Servicio')
                    ->options([
                        'Pediatría' => 'Pediatría',
                        'Ginecología' => 'Ginecología',
                        'Medicina General' => 'Medicina General',
                        'Medicina Interna' => 'Medicina Interna',
                        'Laboratorio' => 'Laboratorio',
                        'Ecografía' => 'Ecografía',
                        'Rayos X' => 'Rayos X',
                        'Tomografía' => 'Tomografía',
                    ])
                    ->required(),

                DateTimePicker::make('scheduled_at')
                    ->label('Fecha y Hora')
                    ->required(),

                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                    ])
                    ->default('pendiente')
                    ->required(),

                Textarea::make('notes')
                    ->label('Notas'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.fullName')->label('Paciente')->searchable(),
                TextColumn::make('service')->label('Servicio'),
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
