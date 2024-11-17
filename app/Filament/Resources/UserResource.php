<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\UserRole;
use Filament;
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

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Filament\Forms\Components\TextInput::make('name')
                    ->required(),
                Filament\Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Filament\Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->hiddenOn('edit'),
                Filament\Forms\Components\TextInput::make('confirm_password')
                    ->password()
                    ->required()
                    ->hiddenOn('edit'),
                Filament\Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options(
                        collect(UserRole::cases())
                            ->mapWithKeys(fn(UserRole $status) => [$status->name => $status->value])
                            ->toArray()
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Filament\Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Filament\Tables\Columns\TextColumn::make('email'),
                Filament\Tables\Columns\TextColumn::make('role'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
