<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\RawJs;
use Filament\Tables\Filters\Filter;


use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                ->label(__('Código'))
                ->autofocus()
                ->helperText('Código del producto')
                ->required()
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'required' => 'Código es requerido',
                    'unique' => 'El código ya existe'
                ]),
                Forms\Components\TextInput::make('name')
                ->label(__('Nombre'))
                ->helperText('Nombre del producto')
                ->required()
                ->validationMessages([
                    'required' => 'Nombre es requerido',
                ]),
                Forms\Components\TextInput::make('price')
                ->label(__('Precio'))
                ->regex('/^([0-9]+(\.[0-9]{0,6})?|\.?[0-9]{1,6})$/')
                ->helperText('Precio del producto')
                ->required()
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->inputMode('decimal')
                ->validationMessages([
                    'required' => 'Precio es requerido',
                    'regex' => 'El precio debe ser un número válido'
                ]),
                Forms\Components\Textarea::make('description')
                ->label(__('Descripción'))
                ->rows(6)
                ->autosize()
                ->minLength(2)
                ->maxLength(65535)
                ->required()
                ->validationMessages([
                    'required' => 'Descripción es requerida',
                    'min' => 'La descripción debe contener al menos 2 caracteres',
                    'max' => 'La descripción no debe exceder los 65535 caracteres',
                ])
                ->columnSpan('full'),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('Código')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Nombre')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price')->label('Precio')
                ->prefix('$')->numeric(decimalPlaces: 2),
            ])
            ->filters([
               
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->icon('heroicon-m-pencil'),
                Tables\Actions\DeleteAction::make()
                ->icon('heroicon-m-trash'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
