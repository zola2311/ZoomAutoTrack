@extends('layouts.portal')

@section('title', 'Service History')

@section('content')
    <h2 class="page-title mb-3 mt-4"><i class="ti ti-history me-2"></i>Service History</h2>

    @if ($jobCards->isEmpty())
        <div class="card">
            <div class="card-body text-center text-secondary py-5">
                <i class="ti ti-clipboard-off fs-1 d-block mb-2"></i>
                No completed service visits yet.
            </div>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Job #</th>
                        <th>Complaint / Work</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($jobCards as $job)
                        <tr>
                            <td class="text-secondary">
                                <i class="ti ti-calendar me-1"></i>{{ $job->completed_at->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge bg-blue-lt">{{ $job->vehicle->plate_number }}</span>
                                {{ $job->vehicle->make }} {{ $job->vehicle->model }}
                            </td>
                            <td><span class="text-secondary">{{ $job->job_number }}</span></td>
                            <td>{{ \Illuminate\Support\Str::limit($job->customer_complaint, 60) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $jobCards->links() }}
        </div>
    @endif
@endsection
