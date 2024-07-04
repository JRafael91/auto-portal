<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicResource\Pages;
use App\Filament\Resources\TechnicResource\RelationManagers;
use App\Models\Technic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TechnicResource extends Resource
{
    protected static ?string $model = Technic::class;

    protected static ?string $modelLabel = 'Técnico';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label(__('Nombre'))
                ->autofocus()
                ->required()
                ->validationMessages([
                    'required' => 'Nombre del técnico es requerido',
                ]),
                Forms\Components\TextInput::make('username')
                ->label(__('Nombre de usuario'))
                ->required()
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'required' => 'Nombre de usuario es requerido',
                    'unique' => 'El usuario :input ya existe'
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre'),
                Tables\Columns\TextColumn::make('username')->label('Nombre de usuario'),
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
            'index' => Pages\ListTechnics::route('/'),
            'create' => Pages\CreateTechnic::route('/create'),
            'edit' => Pages\EditTechnic::route('/{record}/edit'),
        ];
    }
}
