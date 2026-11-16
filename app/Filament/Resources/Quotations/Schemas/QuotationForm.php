<?php

namespace App\Filament\Resources\Quotations\Schemas;

use App\Models\Customer;
use App\Models\Vehicle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class QuotationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Customer & Vehicle Information
                Section::make('Customer & Vehicle Details')
                    ->columns(3)
                    ->collapsible()
                    ->schema([
                        TextInput::make('quotation_number')
                            ->label('Quotation #')
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn(['edit', 'view']),

                        TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required(),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->helperText('Quotation report will be dispatched to this address'),

                        TextInput::make('city')
                            ->label('Location / City')
                            ->default('Addis Ababa'),

                        Select::make('branch_id')
                            ->label('Branch')
                            ->relationship('branch', 'name')
                            ->default(1)
                            ->required(),

                        TextInput::make('make')
                            ->label('Vehicle Make')
                            ->required(),

                        TextInput::make('model')
                            ->label('Vehicle Model')
                            ->required(),

                        TextInput::make('year')
                            ->label('Year')
                            ->numeric()
                            ->minValue(1960)
                            ->maxValue(date('Y') + 1),

                        TextInput::make('plate_number')
                            ->label('Plate Number'),

                        TextInput::make('mileage')
                            ->label('Recorded Mileage (km)')
                            ->numeric()
                            ->suffix('km'),

                        Select::make('transmission')
                            ->options([
                                'Automatic' => 'Automatic',
                                'Manual'    => 'Manual',
                                'CVT'       => 'CVT',
                                'Other'     => 'Other',
                            ]),

                        Select::make('fuel_type')
                            ->options([
                                'Petrol'   => 'Petrol / Benzine',
                                'Diesel'   => 'Diesel',
                                'Hybrid'   => 'Hybrid',
                                'Electric' => 'Electric (EV)',
                            ]),

                        TextInput::make('vin')
                            ->label('VIN / Chassis Number'),
                    ]),

                // 2. Defect Diagnostic Findings & Questionnaire Answers
                Section::make('Defect Questionnaire & Symptoms (Submitted by Customer)')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TextInput::make('service_category')
                            ->label('Requested Category')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),

                        Select::make('drivable_status')
                            ->label('Drivable Condition')
                            ->options([
                                'drivable'           => 'Fully Drivable',
                                'drivable_with_care' => 'Drives with Care / Limited',
                                'not_drivable'       => 'Not Drivable (Towing Required)',
                            ])
                            ->columnSpan(1),

                        Select::make('urgency')
                            ->label('Urgency Level')
                            ->options([
                                'urgent_asap' => 'Urgent (ASAP)',
                                'within_week' => 'Within This Week',
                                'flexible'    => 'Flexible / Inquiring',
                            ])
                            ->columnSpan(1),

                        DatePicker::make('preferred_date')
                            ->label('Customer Preferred Date')
                            ->columnSpan(1),

                        TagsInput::make('symptoms')
                            ->label('Reported Symptoms')
                            ->placeholder('No symptoms reported')
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Customer Problem Description')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Exact description provided by customer during questionnaire submission'),
                    ]),

                // 3. Official Admin Assessment & Scope of Works
                Section::make('🛠️ Workshop Assessment & Scope of Work ("What will be done")')
                    ->description('Provide the diagnostic findings, required repairs, inspection findings, and recommendations.')
                    ->columns(1)
                    ->schema([
                        Textarea::make('diagnostic_assessment')
                            ->label('Diagnostic Assessment & Scope of Work')
                            ->placeholder("e.g. \n1. Comprehensive OBD-II computer diagnostic scan & sensor inspection.\n2. Replacement of front brake pads with OEM ceramic pads.\n3. Machining front brake disc rotors.\n4. Fluid bleeding and electronic calibration.\n5. Road test verification.")
                            ->rows(6)
                            ->required()
                            ->columnSpanFull()
                            ->helperText('This text will be prominently featured in the customer quotation report email.'),

                        TextInput::make('estimated_duration')
                            ->label('Estimated Turnaround Time')
                            ->placeholder('e.g. 1-2 Business Days / 4 Hours')
                            ->default('1-2 Business Days'),
                    ]),

                // 4. Official Cost Estimation (Itemized Labor & Parts)
                Section::make('💰 Cost Estimation & Itemized Breakdown')
                    ->description('Add itemized labor services and spare parts with estimated costs.')
                    ->columns(2)
                    ->schema([
                        // Labor Repeater
                        Repeater::make('labor_items')
                            ->label('Estimated Labor & Services')
                            ->columns(3)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('description')
                                    ->label('Service / Labor Description')
                                    ->placeholder('e.g. Brake Pad Replacement & Caliper Overhaul')
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('estimated_cost')
                                    ->label('Estimated Cost (ETB)')
                                    ->numeric()
                                    ->prefix('ETB')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        static::recalculateTotals($get, $set);
                                    })
                                    ->columnSpan(1),
                            ]),

                        // Parts Repeater
                        Repeater::make('parts_items')
                            ->label('Estimated Spare Parts & Materials')
                            ->columns(4)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('part_name')
                                    ->label('Part Name / Description')
                                    ->placeholder('e.g. Front Brake Pad Set (OEM)')
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $qty = (float)($get('quantity') ?? 1);
                                        $unit = (float)($get('estimated_cost') ?? 0);
                                        $set('total', $qty * $unit);
                                        static::recalculateTotals($get, $set);
                                    })
                                    ->columnSpan(1),

                                TextInput::make('estimated_cost')
                                    ->label('Unit Price (ETB)')
                                    ->numeric()
                                    ->prefix('ETB')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $qty = (float)($get('quantity') ?? 1);
                                        $unit = (float)($get('estimated_cost') ?? 0);
                                        $set('total', $qty * $unit);
                                        static::recalculateTotals($get, $set);
                                    })
                                    ->columnSpan(1),

                                Hidden::make('total')->default(0),
                            ]),

                        // Financial Summary
                        TextInput::make('estimated_labor_total')
                            ->label('Labor Total (ETB)')
                            ->numeric()
                            ->prefix('ETB')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => static::recalculateGrandTotal($get, $set)),

                        TextInput::make('estimated_parts_total')
                            ->label('Parts Total (ETB)')
                            ->numeric()
                            ->prefix('ETB')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => static::recalculateGrandTotal($get, $set)),

                        TextInput::make('estimated_tax')
                            ->label('Estimated Tax / VAT (ETB)')
                            ->numeric()
                            ->default(0)
                            ->prefix('ETB')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => static::recalculateGrandTotal($get, $set)),

                        TextInput::make('estimated_discount')
                            ->label('Estimated Discount (ETB)')
                            ->numeric()
                            ->default(0)
                            ->prefix('ETB')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => static::recalculateGrandTotal($get, $set)),

                        TextInput::make('estimated_total')
                            ->label('Grand Total Estimate (ETB)')
                            ->numeric()
                            ->prefix('ETB')
                            ->required()
                            ->columnSpanFull()
                            ->extraAttributes(['style' => 'font-size: 16px; font-weight: bold;']),

                        DatePicker::make('valid_until')
                            ->label('Quotation Valid Until')
                            ->default(now()->addDays(14)),

                        Select::make('status')
                            ->label('Quotation Status')
                            ->options([
                                'pending'          => 'Pending Assessment',
                                'in_review'        => 'In Review / Diagnostics',
                                'quoted'           => 'Quotation Sent to Client',
                                'accepted'         => 'Accepted by Client',
                                'declined'         => 'Declined by Client',
                                'converted_to_job' => 'Converted to Work Order',
                            ])
                            ->default('pending')
                            ->required(),

                        Textarea::make('admin_notes')
                            ->label('Internal Workshop Notes (Not visible to customer)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function recalculateTotals(Get $get, Set $set): void
    {
        $laborItems = $get('labor_items') ?? [];
        $laborTotal = 0;
        foreach ($laborItems as $item) {
            $laborTotal += (float)($item['estimated_cost'] ?? 0);
        }
        $set('estimated_labor_total', $laborTotal);

        $partsItems = $get('parts_items') ?? [];
        $partsTotal = 0;
        foreach ($partsItems as $item) {
            $qty = (float)($item['quantity'] ?? 1);
            $unit = (float)($item['estimated_cost'] ?? 0);
            $partsTotal += ($qty * $unit);
        }
        $set('estimated_parts_total', $partsTotal);

        $tax = (float)($get('estimated_tax') ?? 0);
        $discount = (float)($get('estimated_discount') ?? 0);
        $grandTotal = $laborTotal + $partsTotal + $tax - $discount;
        $set('estimated_total', max(0, $grandTotal));
    }

    public static function recalculateGrandTotal(Get $get, Set $set): void
    {
        $labor = (float)($get('estimated_labor_total') ?? 0);
        $parts = (float)($get('estimated_parts_total') ?? 0);
        $tax = (float)($get('estimated_tax') ?? 0);
        $discount = (float)($get('estimated_discount') ?? 0);
        $grandTotal = $labor + $parts + $tax - $discount;
        $set('estimated_total', max(0, $grandTotal));
    }
}
