<?php

namespace App\Livewire;

use App\Filament\Resources\JobCards\JobCardResource;
use App\Models\JobCard;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class JobCardStatsWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        $pending = JobCard::where('status', 'pending')->count();

        $inProgress = JobCard::where('status', 'in_progress')->count();

        $qualityCheck = JobCard::where('status', 'quality_check')->count();

        $readyForPickup = JobCard::where('status', 'completed')
            ->whereNull('delivered_at')
            ->count();

        $vipUrgent = JobCard::whereIn('priority', ['vip', 'urgent'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        $completedToday = JobCard::where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();

        return [
            Stat::make('Pending', $pending)
                ->description('Awaiting assignment')
                ->descriptionIcon('heroicon-o-clock')
                ->color($pending > 0 ? 'warning' : 'success')
                ->url(JobCardResource::getUrl('index', [
                    'tableFilters' => ['status' => ['value' => 'pending']],
                ])),

            Stat::make('In Progress', $inProgress)
                ->description('Currently being worked on')
                ->descriptionIcon('heroicon-o-wrench-screwdriver')
                ->color('info')
                ->url(JobCardResource::getUrl('index', [
                    'tableFilters' => ['status' => ['value' => 'in_progress']],
                ])),

            Stat::make('Quality Check', $qualityCheck)
                ->description('Awaiting final inspection')
                ->descriptionIcon('heroicon-o-magnifying-glass')
                ->color('info')
                ->url(JobCardResource::getUrl('index', [
                    'tableFilters' => ['status' => ['value' => 'quality_check']],
                ])),

            Stat::make('Ready for Pickup', $readyForPickup)
                ->description('Completed, not delivered')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color($readyForPickup > 0 ? 'warning' : 'success')
                ->url(JobCardResource::getUrl('index', [
                    'tableFilters' => ['ready_for_pickup' => ['isActive' => true]],
                ])),

            Stat::make('VIP / Urgent Active', $vipUrgent)
                ->description('Needs priority attention')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($vipUrgent > 0 ? 'danger' : 'success')
                ->url(JobCardResource::getUrl('index', [
                    'tableFilters' => ['priority_active' => ['isActive' => true]],
                ])),

            Stat::make('Completed Today', $completedToday)
                ->description('Finished today')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->url(JobCardResource::getUrl('index', [
                    'tableFilters' => ['completed_today' => ['isActive' => true]],
                ])),
        ];
    }
}
