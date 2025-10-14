<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Filament\Resources\PromotionResource\RelationManagers;
use App\Models\Promotion;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Promociones';
    protected static ?string $pluralLabel = 'Promociones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Sección: Identificación y Tipo
                Forms\Components\Section::make('Identificación y Tipo')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'promotion' => 'Promoción Simple',
                                'campaign' => 'Campaña Compleja',
                            ])
                            ->default('promotion')
                            ->required()
                            ->label('Tipo'),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(150)
                            ->label('Título')
                            ->reactive()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Slug (URL)')
                    ])
                    ->columns(2),

                // Sección: Contenido Principal
                Forms\Components\Section::make('Contenido Principal')
                    ->schema([
                        TextInput::make('short_description')
                            ->maxLength(255)
                            ->label('Descripción Corta (para cards)'),
                        RichEditor::make('full_description')
                            ->label('Descripción Completa (para página dedicada)')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'blockquote', 'orderedList', 'unorderedList',
                                'h2', 'h3', 'link', 'image',
                            ]),
                        FileUpload::make('image_url')
                            ->label('Imagen Principal/Banner')
                            ->image()
                            ->directory('promotions')
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->nullable(),
                    ])
                    ->columns(1),

                // Sección: Detalle de la Oferta y Validez
                Forms\Components\Section::make('Detalle de la Oferta y Validez')
                    ->schema([
                        TextInput::make('discount_percentage')
                            ->numeric()
                            ->maxValue(100)
                            ->default(0)
                            ->label('Porcentaje de Descuento (%)')
                            ->suffix('%')
                            ->nullable(),
                        Textarea::make('discount_details')
                            ->label('Detalles del Descuento (ej: "2x1", "Consulta gratis")')
                            ->rows(2)
                            ->nullable(),
                        DatePicker::make('start_date')
                            ->required()
                            ->label('Fecha de Inicio')
                            ->displayFormat('d/m/Y')
                            ->minDate(now()),
                        DatePicker::make('end_date')
                            ->label('Fecha de Fin')
                            ->displayFormat('d/m/Y')
                            ->after('start_date')
                            ->nullable(),
                        Textarea::make('validity_notes')
                            ->label('Notas de Validez (ej: "Cupos limitados")')
                            ->rows(2)
                            ->nullable(),
                    ])
                    ->columns(2),

                // Sección: Estado
                Forms\Components\Section::make('Estado')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Activa')
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('Destacada (en landing principal)'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'promotion',
                        'warning' => 'campaign',
                    ])
                    ->label('Tipo'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('short_description')
                    ->label('Descripción Corta')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('discount_percentage')
                    ->label('Descuento (%)')
                    ->money('USD') // O ajusta a tu moneda; usa prefix/suffix si prefieres '%'
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('discount_details')
                    ->label('Detalles Descuento')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('start_date')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('validity_notes')
                    ->label('Notas Validez')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Activa'),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Destacada'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}
