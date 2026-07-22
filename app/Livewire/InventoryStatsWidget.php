<?php

namespace App\Livewire;

use App\Models\InventoryItem;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryStatsWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalItems = InventoryItem::where('is_active', true)->count();

        $totalValue = InventoryItem::where('is_active', true)
            ->selectRaw('SUM(quantity_on_hand * unit_cost) as total')
            ->value('total') ?? 0;

        $lowStock = InventoryItem::where('is_active', true)
            ->whereColumn('quantity_on_hand', '<=', 'minimum_stock')
            ->where('quantity_on_hand', '>', 0)
            ->count();

        $outOfStock = InventoryItem::where('is_active', true)
            ->where('quantity_on_hand', '<=', 0)
            ->count();

        return [
            Stat::make('Total Parts', $totalItems)
                ->description('Active inventory items')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('info'),

            Stat::make('Inventory Value', number_format($totalValue, 2) . ' ETB')
                ->description('At cost price')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Low Stock', $lowStock)
                ->description('At or below minimum')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($lowStock > 0 ? 'warning' : 'success'),

            Stat::make('Out of Stock', $outOfStock)
                ->description('Zero quantity remaining')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color($outOfStock > 0 ? 'danger' : 'success'),
        ];
    }
}
