<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaSection::make('Service Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Service Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if (blank($get('slug'))) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->placeholder('e.g. Brake Repair'),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Appears in the address bar: /services/your-slug'),

                        Textarea::make('overview')
                            ->label('Service Overview')
                            ->rows(5)
                            ->columnSpan(2)
                            ->helperText('The main description shown on the service page.'),

                        Repeater::make('points')
                            ->label("What's Included")
                            ->simple(
                                TextInput::make('point')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Pad and disc thickness measured'),
                            )
                            ->addActionLabel('Add a point')
                            ->reorderable()
                            ->columnSpan(2),

                        FileUpload::make('image')
                            ->label('Service Photo')
                            ->image()
                            ->disk('public_direct')
                            ->directory('services')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpan(2)
                            ->helperText('Shown on the services listing and the service page. Around 390x260 works well.'),

                        TextInput::make('booking_key')
                            ->label('Booking Form Key')
                            ->maxLength(255)
                            ->helperText('Optional. Matches a checkbox key on the booking form, e.g. brake_repair'),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Lower numbers appear first.'),

                        Toggle::make('is_active')
                            ->label('Visible on the website')
                            ->default(true)
                            ->helperText('Turn off to hide this service without deleting it.'),
                    ]),
            ]);
    }
}
