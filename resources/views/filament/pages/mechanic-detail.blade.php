<x-filament-panels::page>

    @php
        $mechanic = $this->mechanic();
        $stats = $this->getSummaryStats();
        $timeline = $this->getTimeline();
    @endphp

    @if(! $mechanic)
        <div style="border-radius: 0.75rem; background: white; padding: 2rem; text-align: center; color: #9ca3af; border: 1px solid rgba(0,0,0,0.08);">
            Mechanic not found.
        </div>
    @else

        <!-- Mechanic header card -->
        <div style="display: flex; align-items: center; gap: 1rem; border-radius: 0.75rem; background: white; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08); margin-bottom: 1.5rem;">
            <div style="width: 56px; height: 56px; border-radius: 9999px; background: #0C447C; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 600; flex-shrink: 0;">
                {{ collect(explode(' ', $mechanic->name))->map(fn($p) => strtoupper(substr($p, 0, 1)))->take(2)->join('') }}
            </div>
            <div>
                <div style="font-size: 1.25rem; font-weight: 600; color: #111827;">{{ $mechanic->name }}</div>
                <div style="font-size: 0.875rem; color: #6b7280;">{{ $mechanic->phone ?? 'No phone on file' }} &middot; {{ $mechanic->branch?->name ?? 'No branch' }}</div>
            </div>
        </div>

        {{ $this->filtersForm }}

        <!-- Summary stats -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 1.5rem; margin-bottom: 1.5rem;">

            <div style="border-radius: 0.75rem; background: white; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
                <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Jobs Handled</div>
                <div style="margin-top: 0.5rem; font-size: 1.875rem; font-weight: 600; color: #111827;">{{ $stats['total_jobs'] }}</div>
                <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;">{{ $stats['completed_jobs'] }} completed in this period</div>
            </div>

            <div style="border-radius: 0.75rem; background: white; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
                <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Parts Used</div>
                <div style="margin-top: 0.5rem; font-size: 1.875rem; font-weight: 600; color: #111827;">{{ (int) $stats['total_parts'] }}</div>
                <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;">{{ $stats['unique_parts'] }} unique part(s) used</div>
            </div>

            <div style="border-radius: 0.75rem; background: white; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
                <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Avg. Completion Time</div>
                <div style="margin-top: 0.5rem; font-size: 1.875rem; font-weight: 600; color: #111827;">
                    @if($stats['avg_completion_hrs'] !== null)
                        {{ number_format($stats['avg_completion_hrs'], 1) }} hrs
                    @else
                        —
                    @endif
                </div>
                <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;">{{ $stats['vip_urgent'] }} VIP/urgent job(s) handled</div>
            </div>

        </div>

        <!-- Timeline -->
        <div style="border-radius: 0.75rem; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08); padding: 1.5rem;">
            <div style="font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 0.25rem;">Activity Timeline</div>
            <div style="font-size: 0.8125rem; color: #6b7280; margin-bottom: 1.25rem;">Check-ins, completed services, and finished jobs in this period</div>

            @forelse($timeline as $event)
                @php
                    $iconBg = match($event['type']) {
                        'checkin' => '#dbeafe',
                        'service' => '#fef3c7',
                        'completed' => '#dcfce7',
                        default => '#f3f4f6',
                    };
                    $iconColor = match($event['type']) {
                        'checkin' => '#1e40af',
                        'service' => '#92400e',
                        'completed' => '#166534',
                        default => '#374151',
                    };
                    $icon = match($event['type']) {
                        'checkin' => '🚗',
                        'service' => '🔧',
                        'completed' => '✅',
                        default => '•',
                    };
                @endphp
                <div style="display: flex; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                    <div style="width: 32px; height: 32px; border-radius: 9999px; background: {{ $iconBg }}; color: {{ $iconColor }}; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; flex-shrink: 0;">
                        {{ $icon }}
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 0.875rem; color: #111827; font-weight: 500;">{{ $event['label'] }}</div>
                        <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.125rem;">
                            {{ $event['job']->job_number }} &middot; {{ $event['job']->customer?->display_name }} &middot; {{ $event['date']->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding: 2rem; text-align: center; color: #9ca3af; font-size: 0.875rem;">
                    No activity recorded in this period.
                </div>
            @endforelse
        </div>

    @endif

</x-filament-panels::page>
