<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Filament\Resources\DoctorResource\RelationManagers;
use App\Models\Doctor;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestión Médica';
    protected static ?string $navigationLabel = 'Doctores';
    protected static ?string $modelLabel = 'Doctor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')->label('Nombres')->required(),
                Forms\Components\TextInput::make('last_name')->label('Apellidos')->required(),
                Forms\Components\TextInput::make('ci')->label('Cédula de Identidad')->required(),
                Forms\Components\TextInput::make('ci_extension')->label('Extensión del CI')->nullable(),
                Forms\Components\DatePicker::make('birth_date')->label('Fecha de Nacimiento')
                    ->required()
                    ->placeholder('Seleccione una fecha'),
                Forms\Components\Select::make('gender')
                    ->label('Género')
                    ->options([
                        'Masculino' => 'Masculino',
                        'Femenino' => 'Femenino',
                        'Otro' => 'Otro',
                    ]),
                Forms\Components\Textarea::make('address')->label('Dirección'),
                Forms\Components\TextInput::make('phone')->label('Celular'),
                Forms\Components\TextInput::make('email')->label('Correo'),
                Forms\Components\TextInput::make('license_number')->label('Número de Licencia')->required()->unique(),
                Forms\Components\Textarea::make('bio')->label('Biografía')
                    ->placeholder('Escribe una breve biografía del doctor'),
                 // Campo para asignar servicios
                Select::make('services')
                    ->label('Servicios que atiende')
                    ->multiple()
                    ->relationship('services', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->searchable(),
                Tables\Columns\TextColumn::make('last_name')->searchable(),
                Tables\Columns\TextColumn::make('license_number'),
                Tables\Columns\TextColumn::make('user.email')->label('Usuario'),
                Tables\Columns\TextColumn::make('services.name')->label('Servicios')->badge(),

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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
