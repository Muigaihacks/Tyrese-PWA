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
            ->latest('created_at')
            ->groupBy('inventory_id')
            ->havingRaw("MAX(action_type) = 'checkout'")
            ->count();

        $leasedUnitsRevenue = LeasedUnit::sum('leasing_fee');

        return [
            Card::make('Tools Currently Checked Out', $toolsCheckedOut),
            Card::make('Total Leased Units Revenue', 'Ksh ' . number_format($leasedUnitsRevenue)),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
} 