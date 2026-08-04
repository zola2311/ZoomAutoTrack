<x-filament-panels::page>

    {{ $this->filtersForm }}

    @php
        $totals = $this->getTotalForPeriod();
        $parts = $this->getPartsRevenueForPeriod();
        $breakdown = $this->getLineItemBreakdown();
    @endphp

        <!-- Top summary cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 1.5rem; margin-bottom: 1.5rem;">

        <div style="border-radius: 0.75rem; background: white; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
            <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Total Collected</div>
            <div style="margin-top: 0.5rem; font-size: 1.875rem; font-weight: 600; color: #16a34a;">
                {{ number_format($totals['total'], 2) }} ETB
            </div>
            <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;">All payment methods</div>
        </div>

        <div style="border-radius: 0.75rem; background: white; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
            <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Cash</div>
            <div style="margin-top: 0.5rem; font-size: 1.875rem; font-weight: 600; color: #111827;">
                {{ number_format($totals['cash'], 2) }} ETB
            </div>
            <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;">Cash payments received</div>
        </div>

        <div style="border-radius: 0.75rem; background: white; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
            <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Transfer / Digital</div>
            <div style="margin-top: 0.5rem; font-size: 1.875rem; font-weight: 600; color: #111827;">
                {{ number_format($totals['transfer'], 2) }} ETB
            </div>
            <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;">Bank, Telebirr, CBE Birr, cheque</div>
        </div>

        <div style="border-radius: 0.75rem; background: white; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08);">
            <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Total Payments</div>
            <div style="margin-top: 0.5rem; font-size: 1.875rem; font-weight: 600; color: #111827;">
                {{ $totals['count'] }}
            </div>
            <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;">Number of transactions</div>
        </div>

    </div>

    <!-- Parts profit summary -->
    <div style="border-radius: 0.75rem; background: white; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08); margin-bottom: 1.5rem;">
        <div style="font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 0.25rem;">Revenue from Parts Sold</div>
        <div style="font-size: 0.8125rem; color: #6b7280; margin-bottom: 1.25rem;">Parts sold via job cards and direct invoices during this period</div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
            <div>
                <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Parts Revenue</div>
                <div style="margin-top: 0.25rem; font-size: 1.5rem; font-weight: 600; color: #111827;">
                    {{ number_format($parts['revenue'], 2) }} ETB
                </div>
            </div>
            <div>
                <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Parts Cost</div>
                <div style="margin-top: 0.25rem; font-size: 1.5rem; font-weight: 600; color: #111827;">
                    {{ number_format($parts['cost'], 2) }} ETB
                </div>
            </div>
            <div>
                <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">Profit Margin</div>
                <div style="margin-top: 0.25rem; font-size: 1.5rem; font-weight: 600; color: #16a34a;">
                    {{ number_format($parts['profit'], 2) }} ETB
                </div>
            </div>
        </div>

        <div style="margin-top: 0.75rem; font-size: 0.75rem; color: #6b7280;">
            {{ $parts['count'] }} part line item(s) in this period
        </div>
    </div>

    <!-- Merged: daily totals with detail per day, paginated -->
    <div style="border-radius: 0.75rem; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.08); margin-bottom: 1.5rem; overflow: hidden;">
        <div style="padding: 1.5rem 1.5rem 1rem;">
            <div style="font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 0.25rem;">Daily Revenue & Transaction Detail</div>
            <div style="font-size: 0.8125rem; color: #6b7280;">Each day's total collected, split by payment method, with the underlying services and parts sold</div>
        </div>

        @php
            $groupedByDate = $breakdown->groupBy(fn ($item) => $item->invoice?->created_at?->format('Y-m-d'));
            $pagination = $this->getPaginatedDailyTotals();
        @endphp

        @forelse($pagination['items'] as $day)
            @php
                $dateKey = \Illuminate\Support\Carbon::parse($day->payment_date)->format('Y-m-d');
                $dayItems = $groupedByDate->get($dateKey, collect());
            @endphp
            <div style="border-top: 1px solid #e5e7eb;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; background: #f9fafb;">
                    <div style="font-weight: 600; color: #111827;">
                        {{ \Illuminate\Support\Carbon::parse($day->payment_date)->format('d M Y') }}
                    </div>
                    <div style="display: flex; gap: 2rem; font-size: 0.875rem;">
                        <div><span style="color: #6b7280;">Cash:</span> <span style="font-weight: 500;">{{ number_format($day->cash_total, 2) }} ETB</span></div>
                        <div><span style="color: #6b7280;">Transfer:</span> <span style="font-weight: 500;">{{ number_format($day->transfer_total, 2) }} ETB</span></div>
                        <div><span style="color: #6b7280;">Payments:</span> <span style="font-weight: 500;">{{ $day->payment_count }}</span></div>
                        <div style="font-weight: 700; color: #16a34a;">{{ number_format($day->total_collected, 2) }} ETB</div>
                    </div>
                </div>

                @if($dayItems->isNotEmpty())
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.8125rem;">
                        <tbody>
                        @foreach($dayItems as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 0.5rem 1.5rem; color: #6b7280; width: 110px;">{{ $item->invoice?->invoice_number }}</td>
                                <td style="padding: 0.5rem;">
                                        <span style="padding: 2px 8px; border-radius: 9999px; font-size: 0.7rem; font-weight: 500;
                                            background: {{ $item->item_type === 'part' ? '#dbeafe' : '#fef3c7' }};
                                            color: {{ $item->item_type === 'part' ? '#1e40af' : '#92400e' }};">
                                            {{ ucfirst($item->item_type) }}
                                        </span>
                                </td>
                                <td style="padding: 0.5rem; color: #374151;">{{ $item->description }}</td>
                                <td style="padding: 0.5rem; text-align: right; color: #6b7280;">×{{ rtrim(rtrim($item->quantity, '0'), '.') }}</td>
                                <td style="padding: 0.5rem; text-align: right; color: #111827; font-weight: 500; width: 120px;">{{ number_format($item->total, 2) }} ETB</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @empty
            <div style="padding: 2rem 1.5rem; text-align: center; color: #9ca3af; font-size: 0.875rem;">
                No revenue recorded in this period.
            </div>
        @endforelse

        @if($pagination['totalPages'] > 1)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                <div style="font-size: 0.8125rem; color: #6b7280;">
                    Showing page {{ $pagination['currentPage'] }} of {{ $pagination['totalPages'] }} ({{ $pagination['totalDays'] }} day(s) total)
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button
                        type="button"
                        wire:click="previousReportPage"
                        @disabled($pagination['currentPage'] <= 1)
                        style="padding: 0.4rem 0.9rem; border-radius: 0.5rem; border: 1px solid #d1d5db; background: white; font-size: 0.8125rem; cursor: pointer; opacity: {{ $pagination['currentPage'] <= 1 ? '0.5' : '1' }};"
                    >
                        &larr; Previous
                    </button>
                    <button
                        type="button"
                        wire:click="nextReportPage"
                        @disabled($pagination['currentPage'] >= $pagination['totalPages'])
                        style="padding: 0.4rem 0.9rem; border-radius: 0.5rem; border: 1px solid #d1d5db; background: white; font-size: 0.8125rem; cursor: pointer; opacity: {{ $pagination['currentPage'] >= $pagination['totalPages'] ? '0.5' : '1' }};"
                    >
                        Next &rarr;
                    </button>
                </div>
            </div>
        @endif
    </div>

</x-filament-panels::page>
