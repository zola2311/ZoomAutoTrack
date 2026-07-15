<?php

namespace App\Filament\Resources\InspectionItems\Schemas;

use App\Models\InspectionItem;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InspectionItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Inspection Item Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Item Name')
                            ->weight('bold'),

                        TextEntry::make('category')
                            ->label('Category')
                            ->badge()
                            ->color('gray')
                            ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : '—'),

                        TextEntry::make('input_type')
                            ->label('Input Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pass_fail' => 'success',
                                'pass_fail_warning' => 'warning',
                                'numeric' => 'info',
                                'text' => 'primary',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'pass_fail' => '✅ Pass / ❌ Fail',
                                'pass_fail_warning' => '✅ Pass / ⚠️ Warning / ❌ Fail',
                                'numeric' => '🔢 Numeric Value',
                                'text' => '📝 Text Note',
                                default => $state,
                            }),

                        TextEntry::make('sort_order')
                            ->label('Sort Order')
                            ->numeric(),

                        TextEntry::make('requires_photo')
                            ->label('Requires Photo')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? '✅ Yes' : '❌ No')
                            ->color(fn ($state) => $state ? 'success' : 'danger'),

                        TextEntry::make('requires_voice_note')
                            ->label('Requires Voice Note')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? '✅ Yes' : '❌ No')
                            ->color(fn ($state) => $state ? 'success' : 'danger'),

                        TextEntry::make('is_active')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                    ]),

                Section::make('Record Info')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime(),
                    ]),
            ]);
    }
}
