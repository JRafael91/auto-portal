<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label(__('Nombre'))
                ->autofocus()
                ->helperText('Nombre del usuario')
                ->required(),
                Forms\Components\TextInput::make('email')
                ->label(__('Correo electrónico'))
                ->helperText('Correo electrónico del usuario')
                ->required()
                ->email()
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'required' => 'Correo electrónico es requerido',
                    'email' => 'Correo electrónico es inválido',
                    'unique' => 'El correo :input ya existe'
                ]),
                Forms\Components\Select::make('role')
                ->label(__('Rol'))
                ->helperText('Rol del usuario')
                ->options([
                    'ADMIN' => 'ADMINISTRADOR',
                    'USER' => 'USUARIO',
                ])
                ->native(false)
                ->placeholder('Seleccione un Rol')
                ->required()
                ->validationMessages([
                    'required' => 'Rol es requerido',
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre'),
                Tables\Columns\TextColumn::make('email')->label('Correo electrónico')
                ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('role'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withOutAdmin();
    }
}
