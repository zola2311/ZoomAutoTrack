<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supplier Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Supplier Name')
                            ->weight('bold')
                            ->copyable(),

                        TextEntry::make('contact_person')
                            ->label('Contact Person')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('phone')
                            ->label('Phone')
                            ->copyable()
                            ->url(fn ($record) => 'tel:' . $record->phone)
                            ->openUrlInNewTab(),

                        TextEntry::make('secondary_phone')
                            ->label('Secondary Phone')
                            ->placeholder('-')
                            ->copyable()
                            ->url(fn ($record) => $record->secondary_phone ? 'tel:' . $record->secondary_phone : null)
                            ->openUrlInNewTab(),

                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('-')
                            ->copyable()
                            ->url(fn ($record) => $record->email ? 'mailto:' . $record->email : null)
                            ->openUrlInNewTab(),
                    ]),

                Section::make('Location')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('city')
                            ->label('City')
                            ->placeholder('-'),

                        TextEntry::make('area')
                            ->label('Area')
                            ->placeholder('-'),

                        TextEntry::make('address')
                            ->label('Full Address')
                            ->placeholder('-')
                            ->columnSpan(2),

                        TextEntry::make('map_link')
                            ->label('Map Link')
                            ->placeholder('-')
                            ->url(fn ($record) => $record->map_link)
                            ->openUrlInNewTab()
                            ->formatStateUsing(fn ($state) => $state ? 'View on Google Maps' : '-')
                            ->columnSpan(2),
                    ]),

                Section::make('Business Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('tin_number')
                            ->label('TIN Number')
                            ->placeholder('-'),

                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('No additional notes')
                            ->columnSpan(2),
                    ]),

                Section::make('Status')
                    ->schema([
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
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('deleted_at')
                            ->label('Deleted At')
                            ->dateTime()
                            ->visible(fn (Supplier $record): bool => $record->trashed()),
                    ]),
            ]);
    }
}
