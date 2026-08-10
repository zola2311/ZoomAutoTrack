<x-filament-panels::page>

    {{ $this->filtersForm }}

    @php
        $stats = $this->getMechanicStats();
    @endphp

    <div style="border-radius: 0.75rem; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08); margin-top: 1.5rem; overflow: hidden;">

        <div style="padding: 1.5rem 1.5rem 1rem;">
            <div style="font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 0.25rem;">Mechanic Performance</div>
            <div style="font-size: 0.8125rem; color: #6b7280;">Ranked by number of jobs completed in the selected period</div>
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
            <tr style="border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                <th style="text-align: left; padding: 0.75rem 1.5rem; color: #6b7280; font-weight: 500;">Mechanic</th>
                <th style="text-align: center; padding: 0.75rem; color: #6b7280; font-weight: 500;">Total Jobs</th>
                <th style="text-align: center; padding: 0.75rem; color: #6b7280; font-weight: 500;">Completed</th>
                <th style="text-align: center; padding: 0.75rem; color: #6b7280; font-weight: 500;">Avg. Completion</th>
                <th style="text-align: center; padding: 0.75rem; color: #6b7280; font-weight: 500;">VIP / Urgent</th>
                <th style="text-align: right; padding: 0.75rem; color: #6b7280; font-weight: 500;">Labor Revenue</th>
                <th style="text-align: right; padding: 0.75rem 1.5rem; color: #6b7280; font-weight: 500;">Parts Profit</th>
            </tr>
            </thead>
            <tbody>
            @forelse($stats as $row)
                <tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 0.75rem 1.5rem;">
                        <a href="{{ route('filament.admin.pages.mechanic-detail.{mechanicId}', ['mechanicId' => $row['mechanic']->id]) }}"
                           style="color: #0C447C; font-weight: 500; text-decoration: none;">
                            {{ $row['mechanic']->name }} →
                        </a>
                    </td>
                    <td style="padding: 0.75rem; text-align: center; color: #374151;">
                        {{ $row['total_jobs'] }}
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">
                            <span style="padding: 2px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background: #dcfce7; color: #166534;">
                                {{ $row['completed_jobs'] }}
                            </span>
                    </td>
                    <td style="padding: 0.75rem; text-align: center; color: #374151;">
                        @if($row['avg_completion_hrs'] !== null)
                            {{ number_format($row['avg_completion_hrs'], 1) }} hrs
                        @else
                            —
                        @endif
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">
                        @if($row['vip_urgent_count'] > 0)
                            <span style="padding: 2px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background: #fee2e2; color: #991b1b;">
                                    {{ $row['vip_urgent_count'] }}
                                </span>
                        @else
                            <span style="color: #9ca3af;">0</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem; text-align: right; color: #111827; font-weight: 500;">
                        {{ number_format($row['labor_revenue'], 2) }} ETB
                    </td>
                    <td style="padding: 0.75rem 1.5rem; text-align: right; color: #16a34a; font-weight: 600;">
                        {{ number_format($row['parts_profit'], 2) }} ETB
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 2rem 1.5rem; text-align: center; color: #9ca3af;">
                        No mechanic activity in this period.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>

</x-filament-panels::page>
