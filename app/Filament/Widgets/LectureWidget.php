<?php

namespace App\Filament\Widgets;

use App\Models\Lecture;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LectureWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Lectures', 'Qty: ' . Lecture::count())
                ->description('Total Number of Lectures'),
            Stat::make('Unwatched', 'Qty: ' . Lecture::where('status', 'PENDING')->count())
                ->description("Lectures you haven't watched"),
            Stat::make('Watched', 'Qty: ' . Lecture::where('status', 'COMPLETED')->count())
                ->description("Lectures you have watched"),
        ];
    }
}
