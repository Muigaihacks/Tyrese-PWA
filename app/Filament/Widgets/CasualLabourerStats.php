<?php

namespace App\Filament\Widgets;

use App\Models\CasualLabourer;
use App\Models\CasualLabourerAttendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CasualLabourerStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalLabourers = CasualLabourer::count();
        $activeLabourers = CasualLabourer::where('status', 'active')->count();
        $compliantLabourers = CasualLabourer::where('status', 'active')
            ->where('health_declaration', true)
            ->where('skills_confirmation', true)
            ->where('ppe_provided', true)
            ->where('safety_briefing', true)
            ->where('tool_safety_agreement', true)
            ->where('accident_cover_enrolled', true)
            ->where('data_consent', true)
            ->count();
        
        $todayAttendance = CasualLabourerAttendance::where('work_date', today())->count();
        $todayActive = CasualLabourerAttendance::where('work_date', today())
            ->whereNotNull('time_in')
            ->whereNull('time_out')
            ->count();

        return [
            Stat::make('Total Labourers', $totalLabourers)
                ->description('All registered casual labourers')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Active Labourers', $activeLabourers)
                ->description('Currently active workers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Fully Compliant', $compliantLabourers)
                ->description('All safety requirements met')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),

            Stat::make('Today\'s Attendance', $todayAttendance)
                ->description('Workers present today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Currently Working', $todayActive)
                ->description('Workers on site now')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
