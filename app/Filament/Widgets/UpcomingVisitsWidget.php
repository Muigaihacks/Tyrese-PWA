<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Tables\Columns\TextColumn;

class UpcomingVisitsWidget extends TableWidget
{
    protected static ?string $heading = 'Upcoming Scheduled Visits';

    protected function getTableQuery(): Builder|Relation|null
    {
        return Visit::query()
            ->where('scheduled_for', '>=', now())
            ->orderBy('scheduled_for')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('scheduled_for')->label('Date')->dateTime(),
            TextColumn::make('location')->label('Location'),
            TextColumn::make('scheduler.name')->label('Responsible'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
} 