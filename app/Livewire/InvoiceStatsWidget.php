<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InvoiceStatsWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalOutstanding = Invoice::whereIn('status', ['unpaid', 'partial'])->sum('balance');

        $overdueCount = Invoice::whereIn('status', ['unpaid', 'partial'])
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->count();

        // Now consistently based on payments.paid_at, not invoices.updated_at
        $paidThisMonth = Payment::whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $unpaidCount = Invoice::whereIn('status', ['unpaid', 'partial'])->count();

        $todayPayments = Payment::whereDate('paid_at', today());

        $todayCollected = (clone $todayPayments)->sum('amount');

        $todayCash = (clone $todayPayments)->where('method', 'cash')->sum('amount');

        $todayTransfer = (clone $todayPayments)
            ->whereIn('method', ['bank_transfer', 'telebirr', 'cbe_birr', 'cheque'])
            ->sum('amount');

        return [
            Stat::make('Total Outstanding', number_format($totalOutstanding, 2) . ' ETB')
                ->description($unpaidCount . ' invoice(s) unpaid or partial')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color($totalOutstanding > 0 ? 'warning' : 'success'),

            Stat::make('Overdue', $overdueCount)
                ->description('Past due date, unpaid')
                ->descriptionIcon('heroicon-o-clock')
                ->color($overdueCount > 0 ? 'danger' : 'success'),

            Stat::make('Paid This Month', number_format($paidThisMonth, 2) . ' ETB')
                ->description(now()->format('F Y') . ' — based on payment date')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Unpaid Invoices', $unpaidCount)
                ->description('Awaiting payment')
                ->descriptionIcon('heroicon-o-document-text')
                ->color($unpaidCount > 0 ? 'warning' : 'success'),

            Stat::make('Collected Today', number_format($todayCollected, 2) . ' ETB')
                ->description('All payment methods')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Cash Today', number_format($todayCash, 2) . ' ETB')
                ->description('Cash payments received today')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('info'),

            Stat::make('Transfer Today', number_format($todayTransfer, 2) . ' ETB')
                ->description('Bank, Telebirr, CBE Birr, cheque')
                ->descriptionIcon('heroicon-o-arrow-path-rounded-square')
                ->color('info'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'manager', 'cashier']) ?? false;
    }
}
