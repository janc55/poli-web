<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicalRecordResource\Pages;
use App\Filament\Resources\MedicalRecordResource\RelationManagers;
use App\Models\MedicalRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalRecordResource extends Resource
{
    protected static ?string $model = MedicalRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Historiales Clínicos';
    protected static ?string $modelLabel = 'Historial Clínico';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Básica')
                    ->schema([
                        Forms\Components\Select::make('patient_id')
                            ->relationship(
                                name: 'patient',
                                titleAttribute: 'ci',
                                modifyQueryUsing: fn (Builder $query) => $query->select(['id', 'ci', 'first_name', 'last_name']))
                            ->searchable(['first_name', 'last_name', 'ci'])
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                            ->preload()
                            ->required()
                            ->label('Paciente'),
                        Forms\Components\DatePicker::make('date')
                            ->default(now())
                            ->required(),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Detalles Clínicos')
                    ->schema([
                        Forms\Components\Textarea::make('symptoms')
                            ->required()
                            ->label('Síntomas')
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('diagnosis')
                            ->required()
                            ->label('Diagnóstico')
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('treatment')
                            ->required()
                            ->label('Tratamiento')
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas Adicionales')
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Signos Vitales')
                    ->schema([
                        Forms\Components\TextInput::make('vitals.blood_pressure')
                            ->label('Presión Arterial (mmHg)'),
                            
                        Forms\Components\TextInput::make('vitals.heart_rate')
                            ->label('Frecuencia Cardíaca (lpm)'),
                            
                        Forms\Components\TextInput::make('vitals.temperature')
                            ->label('Temperatura (°C)'),
                            
                        Forms\Components\TextInput::make('vitals.weight')
                            ->label('Peso (kg)'),
                            
                        Forms\Components\TextInput::make('vitals.height')
                            ->label('Estatura (cm)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.fullName')
                    ->label('Paciente')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Médico')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('diagnosis')
                    ->label('Diagnóstico')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->diagnosis),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('patient')
                    ->relationship('patient', 'first_name')
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('doctor')
                    ->relationship('doctor', 'name')
                    ->searchable(),
                    
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMedicalRecords::route('/'),
            'create' => Pages\CreateMedicalRecord::route('/create'),
            'edit' => Pages\EditMedicalRecord::route('/{record}/edit'),
        ];
    }
}
