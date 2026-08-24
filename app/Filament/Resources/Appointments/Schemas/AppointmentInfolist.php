<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Http\Controllers\Portal\AppointmentController;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Appointment')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer.display_name')->label('Customer'),
                        TextEntry::make('vehicle.plate_number')->label('Vehicle')
                            ->formatStateUsing(fn ($record) => $record->vehicle
                                ? "{$record->vehicle->plate_number} — {$record->vehicle->make} {$record->vehicle->model}"
                                : '—'),

                        TextEntry::make('requested_date')->label('Date')->date('d M Y'),
                        TextEntry::make('requested_time_slot')->label('Time')
                            ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : 'Any'),

                        TextEntry::make('service_types')
                            ->label('Services')
                            ->formatStateUsing(fn ($state) => collect($state ?? [])
                                ->map(fn ($t) => AppointmentController::SERVICE_TYPES[$t] ?? $t)
                                ->join(', ')
                            )
                            ->columnSpan(2),

                        TextEntry::make('other_service_description')
                            ->label('Other service details')
                            ->visible(fn ($record) => filled($record->other_service_description))
                            ->columnSpan(2),

                        TextEntry::make('notes')
                            ->visible(fn ($record) => filled($record->notes))
                            ->columnSpan(2),

                        TextEntry::make('status')->badge()
                            ->color(fn (string $state) => match ($state) {
                                'pending' => 'warning',
                                'confirmed' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('source')->badge(),

                        TextEntry::make('reviewer.name')->label('Reviewed by')
                            ->visible(fn ($record) => filled($record->reviewed_by)),
                        TextEntry::make('reviewed_at')->dateTime('d M Y H:i')
                            ->visible(fn ($record) => filled($record->reviewed_at)),

                        TextEntry::make('jobCard.job_number')->label('Job card')
                            ->visible(fn ($record) => filled($record->job_card_id))
                            ->url(fn ($record) => $record->job_card_id
                                ? route('filament.admin.resources.job-cards.view', ['record' => $record->job_card_id])
                                : null),
                    ]),
            ]);
    }
}
