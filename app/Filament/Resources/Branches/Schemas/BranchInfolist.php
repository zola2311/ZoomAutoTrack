<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BranchInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Branch Details')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('name')
                            ->label('Branch Name')
                            ->weight('bold'),

                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),

                        TextEntry::make('phone')
                            ->label('Phone')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('address')
                            ->placeholder('-')
                            ->columnSpanFull(),

                    ]),

                Section::make('Record Info')
                    ->columns(2)
                    ->collapsed()
                    ->schema([

                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
