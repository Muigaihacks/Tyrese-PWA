<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\LeasedUnit;
use App\Models\InventoryAction;

class AdminDashboardStats extends BaseWidget
{
    protected function getCards(): array
    {
        $toolsCheckedOut = InventoryAction::select('inventory_id')
            ->groupBy('inventory_id')
            ->havingRaw("MAX(action_type) = 'checkout'")
            ->count();

        return [
            Card::make('Tools Currently Checked Out', $toolsCheckedOut),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
} 