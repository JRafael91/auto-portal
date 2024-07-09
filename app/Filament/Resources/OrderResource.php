<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Models\Technic;
use App\Enums\OrderStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Repeater;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Components\Tab;
use Filament\Forms\Components\Actions\Action;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $modelLabel = 'Orden';

    protected static ?string $pluralModelLabel = 'Órdenes';

    protected static ?string $recordTitleAttribute = 'uid';

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getDetailsFormSchema())
                            ->columns(3),
                        Forms\Components\Section::make('Artículos')
                            ->headerActions([
                                Action::make('Eliminar todos los artículos')
                                    ->icon('heroicon-m-trash')
                                    ->modalHeading('¿Estás seguro?')
                                    ->modalDescription('Todo los artículos existentes serán removidos de la orden')
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->action(fn (Forms\Set $set) => $set('items', [])),
                            ])
                            ->schema([
                                static::getItemsRepeater(),
                            ])
                    ])
                    ->columnSpan(['lg' => fn (?Order $record) => $record === null ? 3 : 2]),
            ])->columns(null);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uid')->label('UID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('info_date')->label('Fecha')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('customer')->label('Cliente')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('total')->label('Total')
                    ->prefix('$')->numeric(decimalPlaces: 2),
                Tables\Columns\TextColumn::make('user.name')->label('Usuario'),
                Tables\Columns\TextColumn::make('technic.name')->label('Técnico'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('uid')
                ->default('OR-'.static::countOrder())
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(ignoreRecord: true)
                ->columnSpan(1),
            Forms\Components\ToggleButtons::make('status')
                ->inline()
                ->options(OrderStatus::class)
                ->required()
                ->columnSpan(2),
            Forms\Components\TextInput::make('customer')
                ->label('Nombre de cliente')
                ->required()
                ->maxLength(100)
                ->validationMessages([
                    'required' => 'El nombre del cliente es requerido',
                    'max' => 'El nombre del cliente no puede tener más de 100 caracteres'
                ])->columnSpan(3),
            Forms\Components\TextInput::make('brand')
                ->label('Marca del auto')
                ->required()
                ->maxLength(50)
                ->validationMessages([
                    'required' => 'Marca del auto es requerido',
                    'max' => 'La marca del auto no debe exceder los 50 caracteres'
                ]),
            Forms\Components\TextInput::make('model')
                ->label('Modelo del auto')
                ->required()
                ->maxLength(50)
                ->validationMessages([
                    'required' => 'Modelo del auto es requerido',
                    'max' => 'El modelo del auto no debe exceder los 50 caracteres'
                ]),
            Forms\Components\TextInput::make('year')
                ->label('Año del auto')
                ->numeric()
                ->minValue(1900)
                ->maxValue(2030)
                ->required()
                 ->validationMessages([
                    'required' => 'Año del auto es requerido',
                    'min' => 'El año del auto no debe ser menor a 1900',
                    'max' => 'El año del auto no debe exceder del 2030'
                ]),
            Forms\Components\Select::make('vehicle')
                ->label(__('Tipo de vehículo'))
                ->options([
                    'SEDAN' => 'Sedan',
                    'TRUCK' => 'Pick-Up',
                ])
                ->native(false)
                ->placeholder('Seleccione un tipo de vehículo')
                ->required()
                ->validationMessages([
                    'required' => 'Tipo de vehículo',
                ]),
            Forms\Components\Select::make('technic_id')
                ->label('Técnico')
                ->options(Technic::all()->pluck('name', 'id'))
                ->native(false)
                ->placeholder('Seleccionat técnico')
                ->required()
                ->searchable()
                ->validationMessages([
                    'required' => 'Técnico es requerido',
                ])->columnSpan(1),
            Forms\Components\Textarea::make('comments')
                ->label(__('Comentarios'))
                ->rows(6)
                ->autosize()
                ->maxLength(65535)
                ->validationMessages([
                    'max' => 'La descripción no debe exceder los 65535 caracteres',
                ])
                ->columnSpan('full')
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('items')
            ->label('Artículos')
            ->relationship()
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Producto')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('price', Product::find($state)?->price ?? 0))
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->searchable()
                    ->validationMessages([
                        'required' => 'Producto es requerido',
                        'distinct' => 'El producto ya ha sido seleccionado',
                    ])
                    ->columnSpan([
                        'md' => 5,
                    ]),
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->minValue(1)
                    ->validationMessages([
                        'required' => 'Cantidad es requerida',
                        'min' => 'La cantidad mínima es 1'
                    ])
                    ->columnSpan([
                        'md' => 2,
                    ]),
                Forms\Components\TextInput::make('price')
                    ->label('Precio')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required()
                    ->validationMessages([
                        'required' => 'Precio es requerido',
                    ])
                    ->columnSpan([
                        'md' => 3,
                    ]),
            ])
            ->defaultItems(1)
            ->columns([
                'md' => 10,
            ])
            ->required()
            ->validationMessages([
                'required' => 'Productos son requeridos',
            ]);
    }

    private static function countOrder(): string
    {
        $count = Order::query()->withTrashed()->count() + 1;
        return str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderDesc();
    }
}
