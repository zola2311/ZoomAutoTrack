<?php

namespace App\Filament\Resources\JobCards\Schemas;

use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use App\Filament\Forms\Components\VoiceRecorder;
use Filament\Forms\Components\Repeater;

class JobCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer & Vehicle')
                    ->columns(2)
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search) => Customer::query()
                                ->where('full_name', 'like', "%{$search}%")
                                ->orWhere('company_name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn ($c) => [$c->id => $c->display_name . ' - ' . $c->phone])
                            )
                            ->getOptionLabelUsing(fn ($value) => Customer::find($value)?->display_name)
                            ->required()
                            ->live(),

                        Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->options(function (Get $get) {
                                $customerId = $get('customer_id');
                                if (! $customerId) {
                                    return [];
                                }
                                return Vehicle::query()
                                    ->where('customer_id', $customerId)
                                    ->get()
                                    ->mapWithKeys(fn ($v) => [$v->id => "{$v->plate_number} — {$v->make} {$v->model}"])
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select a customer first'),
                    ]),

                Section::make('Staff')
                    ->columns(2)
                    ->schema([
                        Select::make('service_advisor_id')
                            ->label('Service Advisor')
                            ->options(fn () => User::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray()
                            )
                            ->searchable()
                            ->preload(),

                        Select::make('mechanic_id')
                            ->label('Mechanic')
                            ->options(fn () => User::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray()
                            )
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Check-in Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('mileage_at_checkin')
                            ->label('Mileage at Check-in')
                            ->numeric()
                            ->minValue(function (Get $get, $record) {
                                $vehicleId = $get('vehicle_id');
                                if (! $vehicleId) return 0;

                                $previous = \App\Models\JobCard::where('vehicle_id', $vehicleId)
                                    ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                                    ->orderByDesc('checked_in_at')
                                    ->first();

                                return $previous?->mileage_at_checkin ?? 0;
                            })
                            ->helperText(function (Get $get, $record) {
                                $vehicleId = $get('vehicle_id');
                                if (! $vehicleId) return null;

                                $previous = \App\Models\JobCard::where('vehicle_id', $vehicleId)
                                    ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                                    ->orderByDesc('checked_in_at')
                                    ->first();

                                return $previous
                                    ? "Previous visit: {$previous->mileage_at_checkin} km"
                                    : null;
                            }),

                        TextInput::make('fuel_level')
                            ->maxLength(50),

                        DateTimePicker::make('checked_in_at')
                            ->default(now()),
                    ]),

                Section::make('Complaint & Notes')
                    ->schema([
                        Textarea::make('customer_complaint')
                            ->columnSpanFull(),

                        Textarea::make('mechanic_notes')
                            ->columnSpanFull(),
                    ]),

                Section::make('Photos')
                    ->description('Upload photos at each stage of the job')
                    ->columns(1)
                    ->schema([
                        FileUpload::make('checkin_photos')
                            ->label('Check-in Condition')
                            ->helperText('Overall condition of the vehicle at intake')
                            ->image()
                            ->multiple()
                            ->disk('public_uploads')
                            ->directory('job-cards/checkin')
                            ->visibility('public')
                            ->previewable(false)
                            ->maxSize(5120)
                            ->reorderable()
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record) {
                                    $component->state($record->getMediaCollectionPaths('checkin_photos'));
                                }
                            })
                            ->dehydrated(false),

                        FileUpload::make('damage_photos')
                            ->label('Damage / Problem Area')
                            ->helperText('Photos of the specific issue found during diagnosis')
                            ->image()
                            ->multiple()
                            ->disk('public_uploads')
                            ->directory('job-cards/damage')
                            ->visibility('public')
                            ->previewable(false)
                            ->maxSize(5120)
                            ->reorderable()
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record) {
                                    $component->state($record->getMediaCollectionPaths('damage_photos'));
                                }
                            })
                            ->dehydrated(false),

                        FileUpload::make('before_photos')
                            ->label('Before Repair')
                            ->image()
                            ->multiple()
                            ->disk('public_uploads')
                            ->directory('job-cards/before')
                            ->visibility('public')
                            ->previewable(false)
                            ->maxSize(5120)
                            ->reorderable()
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record) {
                                    $component->state($record->getMediaCollectionPaths('before_photos'));
                                }
                            })
                            ->dehydrated(false),

                        FileUpload::make('after_photos')
                            ->label('After Repair')
                            ->helperText('Proof of completed work')
                            ->image()
                            ->multiple()
                            ->disk('public_uploads')
                            ->directory('job-cards/after')
                            ->visibility('public')
                            ->previewable(false)
                            ->maxSize(5120)
                            ->reorderable()
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record) {
                                    $component->state($record->getMediaCollectionPaths('after_photos'));
                                }
                            })
                            ->dehydrated(false),

                        FileUpload::make('checkout_photos')
                            ->label('Check-out Condition')
                            ->helperText('Final condition before returning to customer')
                            ->image()
                            ->multiple()
                            ->disk('public_uploads')
                            ->directory('job-cards/checkout')
                            ->visibility('public')
                            ->previewable(false)
                            ->maxSize(5120)
                            ->reorderable()
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record) {
                                    $component->state($record->getMediaCollectionPaths('checkout_photos'));
                                }
                            })
                            ->dehydrated(false),
                    ]),



                Section::make('Voice Notes')
                    ->description('Record one or more voice notes')
                    ->schema([

                        Repeater::make('customer_complaint_voices')
                            ->label('Customer Complaint (Voice Notes)')
                            ->itemLabel(fn (): string => 'Voice Note')
                            ->schema([
                                VoiceRecorder::make('path')
                                    ->label('')
                                    ->dehydrated(),
                            ])
                            ->addActionLabel('+ Add another recording')
                            ->reorderable(false)
                            ->columnSpanFull()
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record) {
                                    $paths = $record->getMediaCollectionPaths('customer_complaint_voices');
                                    $component->state(collect($paths)->map(fn ($p) => ['path' => $p])->toArray());
                                }
                            })
                            ->dehydrated(true),

                        Repeater::make('mechanic_notes_voices')
                            ->label('Mechanic Notes (Voice Notes)')
                            ->itemLabel(fn (): string => 'Voice Note')
                            ->schema([
                                VoiceRecorder::make('path')
                                    ->label('')
                                    ->dehydrated(),
                            ])
                            ->addActionLabel('+ Add another recording')
                            ->reorderable(false)
                            ->columnSpanFull()
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record) {
                                    $paths = $record->getMediaCollectionPaths('mechanic_notes_voices');
                                    $component->state(collect($paths)->map(fn ($p) => ['path' => $p])->toArray());
                                }
                            })
                            ->dehydrated(true),

                    ]),
                Section::make('Status & Priority')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->required()
                            ->default('pending')
                            ->live()
                            ->options([
                                'pending'       => 'Pending',
                                'in_progress'   => 'In Progress',
                                'quality_check' => 'Quality Check',
                                'completed'     => 'Completed',
                                'cancelled'     => 'Cancelled',
                            ])
                            ->disableOptionWhen(function (string $value, Get $get) {
                                if (! auth()->user()->can('job_cards.update_all')) {
                                    return $value === 'completed';
                                }
                                return false;
                            })
                            ->helperText(fn () => ! auth()->user()->can('job_cards.update_all')
                                ? 'Mechanics can move jobs to Quality Check — only a manager or advisor can mark a job Completed.'
                                : null
                            ),
                        Select::make('priority')
                            ->required()
                            ->default('normal')
                            ->options([
                                'normal' => 'Normal',
                                'urgent' => 'Urgent',
                                'vip'    => 'VIP',
                            ]),
                    ]),

                Section::make('Timeline')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('estimated_completion_at')
                            ->label('Estimated Completion')
                            ->helperText('When do you expect this job to be done?'),

                        DateTimePicker::make('completed_at')
                            ->label('Actual Completion Date')
                            ->required(fn (Get $get) => $get('status') === 'completed')
                            ->helperText(fn (Get $get) =>
                            $get('status') === 'completed'
                                ? '⚠️ Required when status is Completed'
                                : 'Set this when the job is actually completed'
                            ),

                        DateTimePicker::make('delivered_at')
                            ->label('Delivered At')
                            ->helperText('When the vehicle was returned to the customer'),
                    ]),
            ]);
    }
}
