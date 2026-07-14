<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;  // ✅ CORRECT import
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supplier Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Supplier Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Addis Auto Parts PLC')
                            ->columnSpan(2),

                        TextInput::make('contact_person')
                            ->label('Contact Person')
                            ->maxLength(255)
                            ->placeholder('e.g. Abebe Kebede'),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('e.g. 0911 123456')
                            ->tel(),

                        TextInput::make('secondary_phone')
                            ->label('Secondary Phone')
                            ->maxLength(20)
                            ->placeholder('e.g. 0911 789012')
                            ->tel(),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('contact@supplier.com'),
                    ]),

                Section::make('Address & Location')
                    ->columns(2)
                    ->schema([
                        TextInput::make('city')
                            ->maxLength(255)
                            ->placeholder('e.g. Addis Ababa'),

                        TextInput::make('area')
                            ->maxLength(255)
                            ->placeholder('e.g. Bole, Kazanchis'),

                        Textarea::make('address')
                            ->label('Full Address')
                            ->rows(2)
                            ->placeholder('e.g. Bole Road, Near Atlas Building')
                            ->columnSpan(2),

                        TextInput::make('map_link')
                            ->label('Google Maps Link')
                            ->url()
                            ->maxLength(500)
                            ->placeholder('https://maps.google.com/...')
                            ->helperText('Paste Google Maps URL for location'),
                    ]),

                Section::make('Business Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('tin_number')
                            ->label('TIN Number')
                            ->maxLength(50)
                            ->placeholder('e.g. 1234567890'),

                        Textarea::make('notes')
                            ->label('Additional Notes')
                            ->rows(3)
                            ->placeholder('Payment terms, delivery preferences, special notes...')
                            ->columnSpan(2),
                    ]),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive suppliers will not appear in purchase order dropdowns'),
                    ]),

                Hidden::make('branch_id')
                    ->default(fn () => auth()->user()->branch_id),
            ]);
    }
}
