<?php

namespace App\Filament\Resources\InspectionItems\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Schema;

class InspectionItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaSection::make('Inspection Item Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Item Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Brake Pad Thickness, Engine Oil Level')
                            ->columnSpan(2),

                        Select::make('category')
                            ->label('Category')
                            ->options([
                                'engine' => 'Engine',
                                'brakes' => 'Brakes',
                                'suspension' => 'Suspension',
                                'electrical' => 'Electrical',
                                'body' => 'Body',
                                'interior' => 'Interior',
                                'exterior' => 'Exterior',
                                'tires' => 'Tires & Wheels',
                                'fluids' => 'Fluids & Lubricants',
                                'cooling' => 'Cooling System',
                                'exhaust' => 'Exhaust System',
                                'transmission' => 'Transmission',
                                'steering' => 'Steering',
                                'battery' => 'Battery',
                                'lighting' => 'Lighting',
                                'other' => 'Other',
                            ])
                            ->searchable()
                            ->nullable(),

                        Select::make('input_type')
                            ->label('Input Type')
                            ->options([
                                'pass_fail' => '✅ Pass / ❌ Fail',
                                'pass_fail_warning' => '✅ Pass / ⚠️ Warning / ❌ Fail',
                                'numeric' => '🔢 Numeric Value',
                                'text' => '📝 Text Note',
                            ])
                            ->required()
                            ->default('pass_fail'),

                        Toggle::make('requires_photo')
                            ->label('Requires Photo')
                            ->helperText('Mechanic must take a photo for this item'),

                        Toggle::make('requires_voice_note')
                            ->label('Requires Voice Note')
                            ->helperText('Mechanic can record a voice note for this item'),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Items with lower numbers appear first in the checklist'),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive items will not appear in inspection checklists'),
                    ]),
            ]);
    }
}
