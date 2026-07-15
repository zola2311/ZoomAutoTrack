<?php

namespace App\Filament\Widgets;

use App\Models\InventoryItem;
use App\Models\Invoice;
use App\Models\JobCard;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $todayJobs = JobCard::whereDate('checked_in_at', today())->count();

        $inProgress = JobCard::whereIn('status', ['pending', 'in_progress', 'quality_check'])->count();

        $todayRevenue = Invoice::whereDate('created_at', today())
            ->where('status', '!=', 'void')
            ->sum('total');

        $lowStock = InventoryItem::where('is_active', true)
            ->whereColumn('quantity_on_hand', '<=', 'minimum_stock')
            ->count();

        return [
            Stat::make('Vehicles Today', $todayJobs)
                ->description('Checked in today')
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),

            Stat::make('Jobs In Progress', $inProgress)
                ->description('Pending, in progress, QC')
                ->descriptionIcon('heroicon-o-wrench-screwdriver')
                ->color('warning'),

            Stat::make('Revenue Today', number_format($todayRevenue, 2) . ' ETB')
                ->description('From invoices today')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Low Stock Alerts', $lowStock)
                ->description('Parts below minimum')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($lowStock > 0 ? 'danger' : 'success'),
        ];
    }
}
