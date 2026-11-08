<?php

namespace App\Filament\Resources\Vehicles\Pages;

use App\Filament\Resources\Vehicles\VehicleResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewVehicle extends ViewRecord
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewPassport')
                ->label('View Digital Passport')
                ->icon('heroicon-o-globe-alt')
                ->color('info')
                ->visible(fn () => filled($this->record->qr_code))
                ->url(fn () => route('passport.show', $this->record->qr_code))
                ->openUrlInNewTab(),
            Action::make('printInspectionCertificate')
                ->label('Inspection Certificate')
                ->icon('heroicon-o-document-check')
                ->color('success')
                ->visible(fn () => filled($this->record->qr_code))
                ->url(fn () => route('passport.certificate', $this->record->qr_code))
                ->openUrlInNewTab(),
            Action::make('printQrSticker')
                ->label('Print QR Sticker')
                ->icon(Heroicon::OutlinedQrCode)
                ->color('gray')
                ->visible(fn () => filled($this->record->qr_code))
                ->url(fn () => route('vehicles.qr-sticker', $this->record))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
