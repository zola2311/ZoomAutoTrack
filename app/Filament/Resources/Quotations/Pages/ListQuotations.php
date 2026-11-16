<?php

namespace App\Filament\Resources\Quotations\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use App\Models\Quotation;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListQuotations extends ListRecords
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Quotation Inquiry'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Inquiries')
                ->badge(Quotation::count()),

            'pending' => Tab::make('Pending Assessment')
                ->badge(Quotation::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),

            'in_review' => Tab::make('In Assessment')
                ->badge(Quotation::where('status', 'in_review')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_review')),

            'quoted' => Tab::make('Quotations Sent')
                ->badge(Quotation::where('status', 'quoted')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'quoted')),

            'converted' => Tab::make('Converted to Work Orders')
                ->badge(Quotation::where('status', 'converted_to_job')->count())
                ->badgeColor('purple')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'converted_to_job')),
        ];
    }
}
