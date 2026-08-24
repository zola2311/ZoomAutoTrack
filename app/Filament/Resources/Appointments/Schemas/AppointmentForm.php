<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Http\Controllers\Portal\AppointmentController;
use App\Models\Customer;
use App\Models\Vehicle;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Book Appointment')
                    ->columns(2)
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(fn () => Customer::query()
                                ->orderByRaw('COALESCE(company_name, full_name)')
                                ->get()
                                ->mapWithKeys(fn ($c) => [$c->id => $c->display_name.' - '.$c->phone])
                                ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->createOptionForm([
                                TextInput::make('full_name')->required()->maxLength(150),
                                TextInput::make('phone')->required()->tel()->maxLength(20)
                                    ->unique(table: 'customers', column: 'phone', modifyRuleUsing: fn ($rule) => $rule->whereNull('deleted_at')),
                                TextInput::make('email')->email()->maxLength(150)
                                    ->unique(table: 'customers', column: 'email', modifyRuleUsing: fn ($rule) => $rule->whereNull('deleted_at')),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $data['type'] = 'individual';
                                $data['branch_id'] = auth()->user()->branch_id ?? 1;

                                return Customer::create($data)->id;
                            })
                            ->columnSpan(2),

                        Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->options(fn (Get $get) => Vehicle::query()
                                ->where('customer_id', $get('customer_id'))
                                ->get()
                                ->mapWithKeys(fn ($v) => [$v->id => $v->plate_number.' — '.$v->make.' '.$v->model])
                                ->toArray()
                            )
                            ->searchable()
                            ->required()
                            ->disabled(fn (Get $get) => blank($get('customer_id')))
                            ->createOptionForm([
                                TextInput::make('plate_number')->required()->maxLength(20),
                                TextInput::make('make')->required()->maxLength(100),
                                TextInput::make('model')->required()->maxLength(100),
                                TextInput::make('year')->numeric(),
                            ])
                            ->createOptionUsing(function (array $data, Get $get) {
                                $data['customer_id'] = $get('customer_id');
                                $data['branch_id'] = auth()->user()->branch_id ?? 1;

                                return Vehicle::create($data)->id;
                            })
                            ->columnSpan(2),

                        DatePicker::make('requested_date')
                            ->label('Date')
                            ->required()
                            ->minDate(now()),

                        Select::make('requested_time_slot')
                            ->label('Time')
                            ->options([
                                'morning' => 'Morning',
                                'afternoon' => 'Afternoon',
                            ]),

                        CheckboxList::make('service_types')
                            ->label('Services')
                            ->options(AppointmentController::SERVICE_TYPES)
                            ->columns(2)
                            ->required()
                            ->live()
                            ->columnSpan(2),

                        Textarea::make('other_service_description')
                            ->label('Describe the "Other" service')
                            ->visible(fn (Get $get) => in_array('other', $get('service_types') ?? []))
                            ->columnSpan(2),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->columnSpan(2),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'rejected' => 'Rejected',
                            ])
                            ->default('confirmed')
                            ->required()
                            ->helperText('Phone bookings can be confirmed right away.'),

                        Hidden::make('source')->default('phone'),
                        Hidden::make('branch_id')->default(fn () => auth()->user()->branch_id ?? 1),
                    ]),
            ]);
    }
}
