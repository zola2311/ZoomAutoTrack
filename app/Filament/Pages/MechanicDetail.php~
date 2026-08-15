<?php

namespace App\Filament\Pages;

use App\Models\JobCard;
use App\Models\PartUsed;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;


class MechanicDetail extends Page
{
    protected string $view = 'filament.pages.mechanic-detail';

    protected static bool $shouldRegisterNavigation = false;

    public ?string $mechanicId = null;
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function mount(?string $mechanicId = null): void
    {
        $this->mechanicId = $mechanicId;
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->toDateString();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'manager']) ?? false;
    }

    public function getTitle(): string
    {
        return 'Mechanic Detail — ' . ($this->mechanic()?->name ?? 'Unknown');
    }

    public function mechanic(): ?User
    {
        return $this->mechanicId ? User::find($this->mechanicId) : null;
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
            Carbon::parse($this->startDate ?? now()->startOfMonth())->startOfDay(),
            Carbon::parse($this->endDate ?? now())->endOfDay(),
        ];
    }

    public function getJobsInPeriod()
    {
        [$start, $end] = $this->dateRange();

        return JobCard::where('mechanic_id', $this->mechanicId)
            ->whereBetween('checked_in_at', [$start, $end])
            ->with(['vehicle', 'customer', 'services', 'partsUsed.inventoryItem'])
            ->orderByDesc('checked_in_at')
            ->get();
    }

    public function getSummaryStats(): array
    {
        $jobs = $this->getJobsInPeriod();
        $completed = $jobs->where('status', 'completed');

        $partsUsed = $jobs->flatMap->partsUsed;

        return [
            'total_jobs'     => $jobs->count(),
            'completed_jobs' => $completed->count(),
            'total_parts'    => $partsUsed->sum('quantity'),
            'unique_parts'   => $partsUsed->pluck('inventory_item_id')->unique()->count(),
            'vip_urgent'     => $jobs->whereIn('priority', ['vip', 'urgent'])->count(),
            'avg_completion_hrs' => $completed
                ->filter(fn ($j) => $j->checked_in_at && $j->completed_at)
                ->avg(fn ($j) => $j->checked_in_at->diffInHours($j->completed_at)),
        ];
    }
    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'mechanic-detail/{mechanicId}';
    }
    public function getTimeline(): array
    {
        $jobs = $this->getJobsInPeriod();
        $events = [];

        foreach ($jobs as $job) {
            $events[] = [
                'type'  => 'checkin',
                'date'  => $job->checked_in_at,
                'job'   => $job,
                'label' => "Checked in {$job->vehicle?->plate_number}",
            ];

            foreach ($job->services as $service) {
                if ($service->completed_at) {
                    $events[] = [
                        'type'  => 'service',
                        'date'  => $service->completed_at,
                        'job'   => $job,
                        'label' => "Completed service: {$service->description}",
                    ];
                }
            }

            if ($job->completed_at) {
                $events[] = [
                    'type'  => 'completed',
                    'date'  => $job->completed_at,
                    'job'   => $job,
                    'label' => "Finished job {$job->job_number}",
                ];
            }
        }

        usort($events, fn ($a, $b) => $b['date'] <=> $a['date']);

        return $events;
    }
}
