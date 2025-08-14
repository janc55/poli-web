<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Pacientes';
    protected static ?string $navigationLabel = 'Pacientes';
    protected static ?string $pluralModelLabel = 'Pacientes';
    protected static ?string $modelLabel = 'Paciente';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')->label('Nombre')->required(),
                TextInput::make('last_name')->label('Apellidos')->required(),
                TextInput::make('ci')->label('CI')->required(),
                TextInput::make('ci_extension')->label('Extensión del CI'),
                TextInput::make('phone')->label('Teléfono'),
                TextInput::make('email')->label('Email')->email(),
                TextInput::make('address')->label('Dirección'),
                Select::make('gender')
                    ->label('Género')
                    ->options([
                        'Masculino' => 'Masculino',
                        'Femenino' => 'Femenino',
                        'Otro' => 'Otro',
                    ]),
                DatePicker::make('birth_date')->label('Fecha de Nacimiento'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fullName')->label('Paciente')->searchable(['first_name', 'last_name']),
                TextColumn::make('ci')->label('CI')->sortable(),
                TextColumn::make('phone')->label('Teléfono'),
                TextColumn::make('gender')->label('Género'),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->options([
                        'Masculino' => 'Masculino',
                        'Femenino' => 'Femenino',
                        'Otro' => 'Otro',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(
                fn (Model $record): string => route('filament.admin.resources.patients.view', ['record' => $record]),
            );
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'view' => Pages\ViewPatient::route('/{record}'), // Agrega esta ruta
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
