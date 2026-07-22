<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('customer_code')
                            ->label('Customer Code')
                            ->copyable(),

                        TextEntry::make('type')
                            ->label('Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'company'    => 'warning',
                                'individual' => 'success',
                                default      => 'gray',
                            }),

                        TextEntry::make('full_name')
                            ->label('Full Name'),

                        TextEntry::make('company_name')
                            ->label('Company Name')
                            ->placeholder('—'),

                        TextEntry::make('branch.name')
                            ->label('Branch'),

                        TextEntry::make('preferred_language')
                            ->label('Preferred Language')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'am' => 'Amharic',
                                'en' => 'English',
                                'om' => 'Afaan Oromo',
                                'ti' => 'Tigrinya',
                                default => $state,
                            }),

                    ]),

                Section::make('Contact Details')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('phone')
                            ->label('Phone')
                            ->copyable(),

                        TextEntry::make('secondary_phone')
                            ->label('Secondary Phone')
                            ->placeholder('—'),

                        TextEntry::make('email')
                            ->label('Email Address')
                            ->placeholder('—')
                            ->copyable(),

                        TextEntry::make('tin_number')
                            ->label('TIN Number')
                            ->placeholder('—'),

                        TextEntry::make('address')
                            ->label('Address')
                            ->placeholder('—')
                            ->columnSpanFull(),

                    ]),

                Section::make('Account')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('loyalty_points')
                            ->label('Loyalty Points')
                            ->numeric(),

                        TextEntry::make('created_at')
                            ->label('Customer Since')
                            ->date(),

                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('—')
                            ->columnSpanFull(),

                    ]),

            ]);
    }
}
