<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Branch;
use Filament\Forms\Components\Grid;
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

                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([

//                        Hidden::make('branch_id')
//                            ->default(fn () => auth()->user()->branch_id)
//                            ->dehydrated()
//                            ->required(),

                        Select::make('type')
                            ->label('Customer Type')
                            ->options([
                                'individual' => 'Individual',
                                'company'    => 'Company',
                            ])
                            ->default('individual')
                            ->required()
                            ->live(),

                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required(fn (Get $get) => $get('type') === 'individual')
                            ->maxLength(150),

                        TextInput::make('company_name')
                            ->label('Company Name')
                            ->required(fn (Get $get) => $get('type') === 'company')
                            ->maxLength(150)
                            ->visible(fn (Get $get) => $get('type') === 'company'),
                    ]),

                Section::make('Contact Details')
                    ->columns(2)
                    ->schema([

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        TextInput::make('secondary_phone')
                            ->label('Secondary Phone')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(150),

                        TextInput::make('tin_number')
                            ->label('TIN Number')
                            ->maxLength(30)
                            ->visible(fn (Get $get) => $get('type') === 'company'),

                        Textarea::make('address')
                            ->label('Address')
                            ->rows(2)
                            ->columnSpanFull(),

                    ]),

                Section::make('Preferences')
                    ->columns(2)
                    ->schema([

                        Select::make('preferred_language')
                            ->label('Preferred Language')
                            ->options([
                                'am' => 'Amharic',
                                'en' => 'English',
                                'om' => 'Afaan Oromo',
                                'ti' => 'Tigrinya',
                            ])
                            ->default('am')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->columnSpanFull(),

                    ]),

            ]);
    }
}
