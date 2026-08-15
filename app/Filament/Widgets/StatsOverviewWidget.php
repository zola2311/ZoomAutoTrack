<?php

namespace App\Filament\Widgets;

use App\Models\InventoryItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobCard;
use App\Models\PartUsed;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function calculatePartsProfit(Carbon $start, Carbon $end): float
    {
        // Source 1: Parts used via job cards
        $jobCardParts = PartUsed::whereHas('jobCard', fn ($q) => $q->whereBetween('created_at', [$start, $end]))
            ->get();

        $jobCardRevenue = $jobCardParts->sum(fn ($p) => $p->unit_price * $p->quantity);
        $jobCardCost = $jobCardParts->sum(fn ($p) => $p->unit_cost * $p->quantity);

        // Source 2: Parts sold via standalone invoices (no job card)
        $standaloneParts = InvoiceItem::where('item_type', 'part')
            ->whereNotNull('inventory_item_id')
            ->whereHas('invoice', fn ($q) => $q->whereBetween('created_at', [$start, $end]))
            ->with('inventoryItem')
            ->get();

        $standaloneRevenue = $standaloneParts->sum(fn ($i) => $i->unit_price * $i->quantity);
        $standaloneCost = $standaloneParts->sum(fn ($i) => ($i->inventoryItem?->unit_cost ?? 0) * $i->quantity);

        $revenue = $jobCardRevenue + $standaloneRevenue;
        $cost = $jobCardCost + $standaloneCost;

        return $revenue - $cost;
    }

    protected function getStats(): array
    {
        $todayJobs = JobCard::whereDate('checked_in_at', today())->count();

        $inProgress = JobCard::whereIn('status', ['pending', 'in_progress', 'quality_check'])->count();

        $todayRevenue = Payment::whereDate('paid_at', today())->sum('amount');

        $lowStock = InventoryItem::where('is_active', true)
            ->whereColumn('quantity_on_hand', '<=', 'minimum_stock')
            ->count();

        $outOfStock = InventoryItem::where('is_active', true)
            ->where('quantity_on_hand', '<=', 0)
            ->count();

        $totalUnpaid = Invoice::whereIn('status', ['unpaid', 'partial'])->sum('balance');

        $overdueCount = Invoice::whereIn('status', ['unpaid', 'partial'])
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->count();

        $readyForPickup = JobCard::where('status', 'completed')
            ->whereNull('delivered_at')
            ->count();

        $monthRevenue = Payment::whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $partsProfitToday = $this->calculatePartsProfit(today()->startOfDay(), today()->endOfDay());

        $partsProfitMonth = $this->calculatePartsProfit(now()->startOfMonth(), now()->endOfMonth());

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
                ->description('Payments received today')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Revenue This Month', number_format($monthRevenue, 2) . ' ETB')
                ->description(now()->format('F Y'))
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),

            Stat::make('Total Unpaid', number_format($totalUnpaid, 2) . ' ETB')
                ->description($overdueCount > 0 ? "{$overdueCount} overdue invoice(s)" : 'No overdue invoices')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color($overdueCount > 0 ? 'danger' : ($totalUnpaid > 0 ? 'warning' : 'success')),

            Stat::make('Ready for Pickup', $readyForPickup)
                ->description('Completed, not delivered')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color($readyForPickup > 0 ? 'warning' : 'success'),

            Stat::make('Low Stock Alerts', $lowStock)
                ->description('Parts below minimum')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($lowStock > 0 ? 'danger' : 'success'),

            Stat::make('Out of Stock', $outOfStock)
                ->description('Zero quantity remaining')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color($outOfStock > 0 ? 'danger' : 'success'),

            Stat::make('Parts Profit Today', number_format($partsProfitToday, 2) . ' ETB')
                ->description('Margin from parts sold today')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('success'),

            Stat::make('Parts Profit This Month', number_format($partsProfitMonth, 2) . ' ETB')
                ->description(now()->format('F Y'))
                ->descriptionIcon('heroicon-o-chart-bar-square')
                ->color('success'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->can('reports.view') ?? false;
    }
}
