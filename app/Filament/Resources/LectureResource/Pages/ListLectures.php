<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use App\LectureStatus;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLectures extends ListRecords
{
    protected static string $resource = LectureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Lectures'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'PENDING')),
            'ongoing' => Tab::make('Ongoing')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'ONGOING')),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'COMPLETED')),
        ];
    }
}
