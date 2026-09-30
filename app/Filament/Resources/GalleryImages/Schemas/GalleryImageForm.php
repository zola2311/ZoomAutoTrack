<?php

namespace App\Filament\Resources\GalleryImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Schema;

class GalleryImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaSection::make('Gallery Image')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Photo')
                            ->image()
                            ->required()
                            ->disk('public_direct')
                            ->directory('gallery')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpan(2)
                            ->helperText('Upload a workshop photo. Larger images look better in the lightbox.'),

                        TextInput::make('caption')
                            ->label('Caption')
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->placeholder('e.g. Brake repair in progress'),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Lower numbers appear first.'),

                        Toggle::make('is_active')
                            ->label('Visible on the website')
                            ->default(true),
                    ]),
            ]);
    }
}
