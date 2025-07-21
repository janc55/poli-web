<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Citas';
    protected static ?string $pluralModelLabel = 'Citas';
    protected static ?string $modelLabel = 'Cita';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('patient_id')
                    ->label('Paciente')
                    ->relationship('patient', 'ci') // o fullName() si lo agregas como accessor
                    ->searchable()
                    ->required(),

                Select::make('user_id')
                    ->label('Registrado por')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

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
                TextColumn::make('fullName')->label('Paciente')->searchable(),
                TextColumn::make('service')->label('Servicio'),
                TextColumn::make('scheduled_at')->label('Fecha')->dateTime(),
                TextColumn::make('status')->label('Estado')->badge(),
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
