<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LectureResource\Pages;
use App\Filament\Resources\LectureResource\RelationManagers;
use App\LectureStatus;
use App\Models\Lecture;
use Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LectureResource extends Resource
{
    protected static ?string $model = Lecture::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Filament\Forms\Components\Select::make('category')
                    ->label('Category')
                    ->relationship('category', 'name'),
                Filament\Forms\Components\TextInput::make('name')
                    ->label('Lecture Name'),
                Filament\Forms\Components\TextInput::make('link_video')
                    ->label('Lecture Link'),
                Filament\Forms\Components\TextInput::make('link_pdf')
                    ->label('Lecture PDF'),
                Filament\Forms\Components\TextInput::make('pdf_password')
                    ->label('Lecture Password'),
                Filament\Forms\Components\Select::make('status')
                    ->label('Lecture Status')
                    ->options(
                        collect(LectureStatus::cases())
                            ->mapWithKeys(fn(LectureStatus $status) => [$status->name => $status->value])
                            ->toArray()
                    ),
                Filament\Forms\Components\Toggle::make('is_unlocked')
                    ->label('PDF Status')
                    ->helperText('Is the PDF unlocked?')
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Filament\Tables\Columns\TextColumn::make('category.name'),
                Filament\Tables\Columns\TextColumn::make('name'),
                Filament\Tables\Columns\TextColumn::make('link_video'),
                Filament\Tables\Columns\TextColumn::make('link_pdf'),
                Filament\Tables\Columns\TextColumn::make('pdf_password'),
                Filament\Tables\Columns\TextColumn::make('is_unlocked'),
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
            'index' => Pages\ListLectures::route('/'),
            'create' => Pages\CreateLecture::route('/create'),
            'edit' => Pages\EditLecture::route('/{record}/edit'),
        ];
    }
}
