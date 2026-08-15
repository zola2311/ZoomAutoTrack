<?php

namespace App\Filament\Pages;

use App\Models\JobCard;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use UnitEnum;

class MechanicPerformanceReport extends Page
{
    protected string $view = 'filament.pages.mechanic-performance-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;
    protected static string|UnitEnum|null $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Mechanic Performance';
    protected static ?string $title = 'Mechanic Performance Report';

    public ?string $startDate = null;
    public ?string $endDate = null;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->toDateString();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'manager']) ?? false;
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

    public function getMechanicStats()
    {
        [$start, $end] = $this->dateRange();

        $startDt = Carbon::parse($start)->startOfDay();
        $endDt = Carbon::parse($end)->endOfDay();

        $mechanics = User::query()
            ->where(function ($q) {
                $q->whereHas('roles', fn ($r) => $r->where('name', 'mechanic'))
                    ->orWhere('role', 'mechanic');
            })
            ->where('is_active', true)
            ->get();

        return $mechanics->map(function (User $mechanic) use ($startDt, $endDt) {

            $jobs = JobCard::where('mechanic_id', $mechanic->id)
                ->whereBetween('checked_in_at', [$startDt, $endDt])
                ->get();

            $completedJobs = $jobs->where('status', 'completed');

            $avgCompletionHours = null;
            $withBothDates = $completedJobs->filter(fn ($j) => $j->checked_in_at && $j->completed_at);
            if ($withBothDates->count() > 0) {
                $avgCompletionHours = $withBothDates
                    ->avg(fn ($j) => $j->checked_in_at->diffInHours($j->completed_at));
            }

            $laborRevenue = \App\Models\JobService::whereIn('job_card_id', $jobs->pluck('id'))
                ->where('assigned_mechanic_id', $mechanic->id)
                ->sum('labor_cost');

            $partsProfit = \App\Models\PartUsed::whereIn('job_card_id', $jobs->pluck('id'))
                ->get()
                ->sum(fn ($p) => ($p->unit_price - $p->unit_cost) * $p->quantity);

            $vipUrgentHandled = $jobs->whereIn('priority', ['vip', 'urgent'])->count();

            return [
                'mechanic'          => $mechanic,
                'total_jobs'        => $jobs->count(),
                'completed_jobs'    => $completedJobs->count(),
                'avg_completion_hrs'=> $avgCompletionHours,
                'labor_revenue'     => $laborRevenue,
                'parts_profit'      => $partsProfit,
                'vip_urgent_count'  => $vipUrgentHandled,
            ];
        })->sortByDesc('completed_jobs')->values();
    }
}
