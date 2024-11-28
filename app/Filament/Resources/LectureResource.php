<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LectureResource\Pages;
use App\Filament\Resources\LectureResource\RelationManagers;
use App\Filament\Resources\LectureResource\RelationManagers\TakeHomeExamRelationManager;
use App\LectureStatus;
use App\Models\Category;
use App\Models\Lecture;
use App\Models\Subject;
use App\Models\Topic;
use Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LectureResource extends Resource
{
    protected static ?string $model = Lecture::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Filament\Forms\Components\Section::make('Lecture Form')
                    ->schema([
                        Filament\Forms\Components\Fieldset::make('Relations')
                            ->schema([
                                Filament\Forms\Components\Select::make('subject_id')
                                    ->label('Lecture Subject')
                                    ->relationship('subject', 'name')
                                    ->live()
                                    ->columnSpanFull()
                                    ->required()
                                    ->afterStateUpdated(fn($set) => $set('category_id', null)),
                                Filament\Forms\Components\Select::make('category_id')
                                    ->label('Lecture Category')
                                    ->createOptionForm(function ($get) {
                                        return [
                                            Forms\Components\Hidden::make('subject_id')
                                                ->default($get('subject_id')),
                                            Forms\Components\TextInput::make('name')
                                                ->required()
                                        ];
                                    })
                                    ->createOptionUsing(function (array $data) {
                                        return Category::create($data)->id;
                                    })
                                    ->createOptionAction(fn($action) => $action->disabled(fn($get) => !$get('subject_id')))
                                    ->options(function ($get) {
                                        $categories = empty($get('subject_id'))
                                            ? Category::all()
                                            : Category::where('subject_id', $get('subject_id'))->get();
                                        return $categories->pluck('name', 'id');
                                    })
                                    ->preload()
                                    ->searchable()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function ($set, $get, $state) {
                                        if (empty($get('subject_id'))) {
                                            $set('subject_id', Category::find($state)->subject_id);
                                        }
                                    }),
                                Filament\Forms\Components\Select::make('topics')
                                    ->multiple()
                                    ->relationship('topics', 'name')
                                    ->createOptionForm(function ($record) {
                                        return [
                                            Forms\Components\Hidden::make('lecture_id')
                                                ->default($record->id),
                                            Forms\Components\TextInput::make('name')
                                                ->required()
                                        ];
                                    })
                                    ->createOptionUsing(function (array $data) {
                                        return Topic::create($data)->id;
                                    })
                                    ->createOptionAction(fn($action) => $action->disabled(fn($record) => is_null($record)))
                                    ->preload()
                                    ->searchable()
                            ]),
                        Filament\Forms\Components\Fieldset::make('Lecture Details')
                            ->schema([
                                Filament\Forms\Components\TextInput::make('name')
                                    ->label('Lecture Name')
                                    ->required(),
                                Filament\Forms\Components\TextInput::make('link_video')
                                    ->label('Lecture Link'),
                                Filament\Forms\Components\TextInput::make('link_pdf')
                                    ->label('Lecture PDF'),
                                Filament\Forms\Components\TextInput::make('pdf_password')
                                    ->label('Lecture Password'),
                            ])
                            ->columns(2),
                        Filament\Forms\Components\Fieldset::make('Statuses')
                            ->schema([
                                Filament\Forms\Components\Select::make('status')
                                    ->label('Lecture Status')
                                    ->options(
                                        collect(LectureStatus::cases())
                                            ->mapWithKeys(fn(LectureStatus $status) => [$status->name => $status->value])
                                            ->toArray()
                                    )
                                    ->required(),
                                Filament\Forms\Components\Toggle::make('is_unlocked')
                                    ->label('PDF Status')
                                    ->helperText('Is the PDF unlocked?')
                                    ->inline(false),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Filament\Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Lecture Name')
                    ->sortable()
                    ->searchable(),
                Filament\Tables\Columns\TextColumn::make('link_video')
                    ->label('Link Video')
                    ->url(fn($record): string => $record->link_video ?? '', true)
                    ->default('No Link')
                    ->limit(20),
                Filament\Tables\Columns\TextColumn::make('link_pdf')
                    ->label('Link PDF')
                    ->url(fn($record): string => $record->link_pdf ?? '', true)
                    ->default('No Link')
                    ->limit(20),
                Filament\Tables\Columns\TextColumn::make('pdf_password')
                    ->label('PDF Password')
                    ->default('No Password'),
                Filament\Tables\Columns\TextColumn::make('is_unlocked')
                    ->label('PDF Status')
                    ->formatStateUsing(function ($state) {
                        if ($state) {
                            return 'Unlocked';
                        }
                        return 'Locked';
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                Tables\Filters\SelectFilter::make('topics')
                    ->relationship('topics', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
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
            TakeHomeExamRelationManager::class,
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
