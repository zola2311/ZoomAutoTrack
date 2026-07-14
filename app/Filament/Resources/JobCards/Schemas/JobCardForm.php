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
use Filament\Schemas\Schema;

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
                            ->options(fn () => Customer::query()
                                ->orderBy('full_name')
                                ->get()
                                ->mapWithKeys(fn ($c) => [$c->id => $c->full_name . ' - ' . $c->phone])
                                ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(), // so the vehicle list below can react to it

                        Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->options(function (\Filament\Schemas\Components\Utilities\Get $get) {
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
                            ->minValue(0),

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

                Section::make('Status & Priority')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->required()
                            ->default('pending')
                            ->options([
                                'pending'       => 'Pending',
                                'in_progress'   => 'In Progress',
                                'quality_check' => 'Quality Check',
                                'completed'     => 'Completed',
                                'cancelled'     => 'Cancelled',
                            ]),

                        Select::make('priority')
                            ->required()
                            ->default('normal')
                            ->options([
                                'normal' => 'Normal',
                                'urgent' => 'Urgent',
                                'vip'    => 'VIP',
                            ]),
                    ]),

                Section::make('Completion')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('estimated_completion_at'),
                        DateTimePicker::make('completed_at'),
                        DateTimePicker::make('delivered_at'),
                    ]),
            ]);
    }
}
