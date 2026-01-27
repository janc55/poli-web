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
use Illuminate\Validation\Rule;

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
                // Sección de Información Personal
                Forms\Components\Section::make('Información Personal')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->label('Nombres')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('last_name')
                                    ->label('Apellidos')
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('ci')
                                    ->label('Cédula de Identidad')
                                    ->required()
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
                                    ->maxDate(now()->subYears(18))
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
                            ->label('Foto del Doctor')
                            ->disk('public')
                            ->directory('avatars/doctors')
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
                        Forms\Components\Textarea::make('address')
                            ->label('Dirección')
                            ->placeholder('Escribe la dirección completa')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Celular')
                                    ->tel()
                                    ->required()
                                    ->mask('999-999-999')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('user.email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->visible(fn (string $operation): bool => $operation === 'create')
                                    ->unique(
                                        table: 'users',
                                        column: 'email',
                                        ignoreRecord: true,
                                    )
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->collapsible()
                    ->columns(1),

                // Sección de Información Profesional
                Forms\Components\Section::make('Información Profesional')
                    ->schema([
                        Forms\Components\TextInput::make('license_number')
                            ->label('Número de Licencia')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('bio')
                            ->label('Biografía')
                            ->placeholder('Escribe una breve biografía del doctor, incluyendo experiencia y especialidades.')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('services')
                            ->label('Servicios que atiende')
                            ->multiple()
                            ->relationship('services', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->placeholder('Selecciona los servicios')
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
                Tables\Columns\ImageColumn::make('avatar')->label('Foto')->circular(),
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
            RelationManagers\SchedulesRelationManager::class,
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
