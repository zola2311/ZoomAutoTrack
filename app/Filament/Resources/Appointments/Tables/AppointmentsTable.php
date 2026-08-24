<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Http\Controllers\Portal\AppointmentController;
use App\Models\JobCard;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('requested_date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('requested_time_slot')
                    ->label('Time')
                    ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : 'Any')
                    ->badge(),
                TextColumn::make('customer.display_name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('vehicle.plate_number')
                    ->label('Vehicle')
                    ->searchable(),
                TextColumn::make('service_types')
                    ->label('Services')
                    ->formatStateUsing(fn ($state) => collect($state ?? [])
                        ->map(fn ($type) => AppointmentController::SERVICE_TYPES[$type] ?? $type)
                        ->join(', ')
                    )
                    ->wrap(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Requested on')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalDescription('This creates a job card pre-filled with this appointment\'s details.')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'confirmed',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);

                        Notification::make()
                            ->title($record->job_card_id
                                ? "Appointment confirmed — job card {$record->jobCard->job_number} created"
                                : 'Appointment confirmed')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'rejected',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Appointment rejected')
                            ->warning()
                            ->send();
                    }),
            ]);
    }
}
