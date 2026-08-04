<?php

namespace App\Filament\Pages;

use App\Models\Payment;
use App\Models\PartUsed;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use UnitEnum;

class DailyRevenueReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.daily-revenue-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static string|UnitEnum|null $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Daily Revenue Report';
    protected static ?string $title = 'Daily Revenue Report';

    public ?string $startDate = null;
    public ?string $endDate = null;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->toDateString();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['admin', 'manager']) ?? false;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Date Range')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('From')
                            ->default(now()->startOfMonth())
                            ->live()
                            ->afterStateUpdated(fn ($state) => $this->startDate = $state),

                        DatePicker::make('endDate')
                            ->label('To')
                            ->default(now())
                            ->live()
                            ->afterStateUpdated(fn ($state) => $this->endDate = $state),
                    ]),
            ]);
    }

    protected function dateRange(): array
    {
        return [
            $this->startDate ?? now()->startOfMonth()->toDateString(),
            $this->endDate ?? now()->toDateString(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        [$start, $end] = $this->dateRange();

        return Payment::query()
            ->selectRaw('DATE(paid_at) as payment_date')
            ->selectRaw("SUM(amount) as total_collected")
            ->selectRaw("SUM(CASE WHEN method = 'cash' THEN amount ELSE 0 END) as cash_total")
            ->selectRaw("SUM(CASE WHEN method != 'cash' THEN amount ELSE 0 END) as transfer_total")
            ->selectRaw('COUNT(*) as payment_count')
            ->whereBetween('paid_at', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay(),
            ])
            ->groupBy('payment_date')
            ->orderByDesc('payment_date');
    }

    public function getTableRecordKey($record): string
    {
        return (string) $record->payment_date;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('payment_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('total_collected')
                    ->label('Total Collected')
                    ->money('ETB')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('cash_total')
                    ->label('Cash')
                    ->money('ETB')
                    ->sortable(),

                TextColumn::make('transfer_total')
                    ->label('Transfer / Digital')
                    ->money('ETB')
                    ->sortable(),

                TextColumn::make('payment_count')
                    ->label('Payments')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->defaultSort('payment_date', 'desc')
            ->paginated([10, 25, 50]);
    }

    public function getTotalForPeriod(): array
    {
        [$start, $end] = $this->dateRange();

        $payments = Payment::whereBetween('paid_at', [
            Carbon::parse($start)->startOfDay(),
            Carbon::parse($end)->endOfDay(),
        ]);

        return [
            'total'    => (clone $payments)->sum('amount'),
            'cash'     => (clone $payments)->where('method', 'cash')->sum('amount'),
            'transfer' => (clone $payments)->where('method', '!=', 'cash')->sum('amount'),
            'count'    => (clone $payments)->count(),
        ];
    }

    public function getPartsRevenueForPeriod(): array
    {
        [$start, $end] = $this->dateRange();

        $startDt = \Illuminate\Support\Carbon::parse($start)->startOfDay();
        $endDt = \Illuminate\Support\Carbon::parse($end)->endOfDay();

        // Source 1: Parts used via job cards
        $jobCardParts = \App\Models\PartUsed::whereHas('jobCard', fn ($q) => $q->whereBetween('created_at', [$startDt, $endDt]))
            ->get();

        $jobCardRevenue = $jobCardParts->sum(fn ($p) => $p->unit_price * $p->quantity);
        $jobCardCost = $jobCardParts->sum(fn ($p) => $p->unit_cost * $p->quantity);
        $jobCardCount = $jobCardParts->count();

        // Source 2: Parts sold via standalone invoices (no job card)
        $standaloneParts = \App\Models\InvoiceItem::where('item_type', 'part')
            ->whereNotNull('inventory_item_id')
            ->whereHas('invoice', fn ($q) => $q->whereBetween('created_at', [$startDt, $endDt]))
            ->with('inventoryItem')
            ->get();

        $standaloneRevenue = $standaloneParts->sum(fn ($i) => $i->unit_price * $i->quantity);
        $standaloneCost = $standaloneParts->sum(fn ($i) => ($i->inventoryItem?->unit_cost ?? 0) * $i->quantity);
        $standaloneCount = $standaloneParts->count();

        $revenue = $jobCardRevenue + $standaloneRevenue;
        $cost = $jobCardCost + $standaloneCost;

        return [
            'revenue' => $revenue,
            'cost'    => $cost,
            'profit'  => $revenue - $cost,
            'count'   => $jobCardCount + $standaloneCount,
        ];
    }
    public function getLineItemBreakdown()
    {
        [$start, $end] = $this->dateRange();

        return \App\Models\InvoiceItem::query()
            ->whereHas('invoice', fn ($q) => $q->whereBetween('created_at', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay(),
            ]))
            ->with('invoice')
            ->latest('created_at')
            ->limit(50)
            ->get();
    }
    public int $reportPage = 1;
    protected int $daysPerPage = 5;

    public function nextReportPage(): void
    {
        $this->reportPage++;
    }

    public function previousReportPage(): void
    {
        if ($this->reportPage > 1) {
            $this->reportPage--;
        }
    }

    public function getPaginatedDailyTotals()
    {
        $all = collect($this->getTableQuery()->get());

        $totalPages = (int) ceil($all->count() / $this->daysPerPage);

        if ($this->reportPage > $totalPages && $totalPages > 0) {
            $this->reportPage = $totalPages;
        }

        return [
            'items' => $all->forPage($this->reportPage, $this->daysPerPage)->values(),
            'currentPage' => $this->reportPage,
            'totalPages' => max(1, $totalPages),
            'totalDays' => $all->count(),
        ];
    }
}
