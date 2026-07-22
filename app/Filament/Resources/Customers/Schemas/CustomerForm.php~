<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Branch;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Create Customer')
                    ->columnSpanFull() // 🌟 FIX: Spans the section across the entire modal width
                    ->columns(2)       // Keeps your form fields structured nicely inside
                    ->schema([

                        // Row 1: Customer Type + Full Name / Company Name
                        Select::make('type')
                            ->label('Customer Type')
                            ->options([
                                'individual' => 'Individual',
                                'company'    => 'Company',
                            ])
                            ->default('individual')
                            ->required()
                            ->live()
                            ->columnSpan(1),

                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required(fn (Get $get) => $get('type') === 'individual')
                            ->maxLength(150)
                            ->columnSpan(1)
                            ->visible(fn (Get $get) => $get('type') === 'individual'),

                        TextInput::make('company_name')
                            ->label('Company Name')
                            ->required(fn (Get $get) => $get('type') === 'company')
                            ->maxLength(150)
                            ->columnSpan(1)
                            ->visible(fn (Get $get) => $get('type') === 'company'),

                        // Row 2: Phone + Secondary Phone
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->unique(table: 'customers', column: 'phone', ignoreRecord: true)
                            ->columnSpan(1),

                        TextInput::make('secondary_phone')
                            ->label('Secondary Phone')
                            ->tel()
                            ->maxLength(20)
                            ->columnSpan(1),

                        // Row 3: Email + TIN (full width)
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(150)
                            ->unique(table: 'customers', column: 'email', ignoreRecord: true)
                            ->columnSpan(1),

                        TextInput::make('tin_number')
                            ->label('TIN Number')
                            ->maxLength(30)
                            ->columnSpan(1)
                            ->visible(fn (Get $get) => $get('type') === 'company'),

                        // Row 4: Address - FULL WIDTH (spans both columns)
                        Textarea::make('address')
                            ->label('Address')
                            ->rows(2)
                            ->columnSpan(2),

                        // Row 5: Language + Notes
                        Select::make('preferred_language')
                            ->label('Preferred Language')
                            ->options([
                                'am' => 'Amharic',
                                'en' => 'English',
                                'om' => 'Afaan Oromo',
                                'ti' => 'Tigrinya',
                            ])
                            ->default('am')
                            ->required()
                            ->columnSpan(1),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->columnSpan(1),

                        // Hidden fields
                        Hidden::make('branch_id')
                            ->default(fn () => auth()->user()->branch_id),

                        Hidden::make('customer_code')
                            ->default(fn () => 'CUST-' . str_pad(
                                    \App\Models\Customer::withTrashed()->count() + 1,
                                    4, '0', STR_PAD_LEFT
                                )),
                    ]),
            ]);
    }
}
