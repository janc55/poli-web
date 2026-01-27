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
use Filament\Tables\Columns\ImageColumn;
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
                // Sección de Información Personal
                Forms\Components\Section::make('Información Personal')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->label('Nombres')
                                    ->required()
                                    ->placeholder('Ej: Juan')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('last_name')
                                    ->label('Apellidos')
                                    ->required()
                                    ->placeholder('Ej: Pérez')
                                    ->columnSpan(1),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('ci')
                                    ->label('Cédula de Identidad')
                                    ->required()
                                    ->placeholder('Ej: 12345678')
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('ci_extension')
                                    ->label('Extensión del CI')
                                    ->nullable()
                                    ->placeholder('Ej: La Paz')
                                    ->columnSpan(1),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('birth_date')
                                    ->label('Fecha de Nacimiento')
                                    ->required()
                                    ->placeholder('Seleccione una fecha')
                                    ->displayFormat('d/m/Y')
                                    ->minDate(now()->subYears(100))
                                    ->maxDate(now())
                                    ->columnSpan(1),
                                Forms\Components\Select::make('gender')
                                    ->label('Género')
                                    ->options([
                                        'masculino' => 'Masculino',
                                        'femenino' => 'Femenino',
                                        'otro' => 'Otro',
                                    ])
                                    ->default('masculino')
                                    ->searchable()
                                    ->columnSpan(1),
                            ]),
                        Forms\Components\FileUpload::make('avatar')
                            ->label('Foto del Paciente')
                            ->disk('public')
                            ->directory('avatars/patients')
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columns(1),

                // Sección de Información de Contacto
                Forms\Components\Section::make('Información de Contacto')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Teléfono')
                                    ->tel()
                                    ->required()
                                    ->mask('999-999-999')
                                    ->placeholder('Ej: 777-123-456')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->required()
                                    ->visible(fn (string $operation): bool => $operation === 'create')
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Ej: paciente@ejemplo.com')
                                    ->columnSpan(1),
                            ]),
                        Forms\Components\Textarea::make('address')
                            ->label('Dirección')
                            ->placeholder('Escribe la dirección completa')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')->label('Foto')->circular(),
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
