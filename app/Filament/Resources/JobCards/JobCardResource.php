<?php

namespace App\Filament\Resources\JobCards;

use App\Filament\Resources\JobCards\Pages\CreateJobCard;
use App\Filament\Resources\JobCards\Pages\EditJobCard;
use App\Filament\Resources\JobCards\Pages\ListJobCards;
use App\Filament\Resources\JobCards\Pages\ViewJobCard;
use App\Filament\Resources\JobCards\Schemas\JobCardForm;
use App\Filament\Resources\JobCards\Schemas\JobCardInfolist;
use App\Filament\Resources\JobCards\Tables\JobCardsTable;
use App\Models\JobCard;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JobCards\RelationManagers\JobServicesRelationManager;
use App\Filament\Resources\JobCards\RelationManagers\PartsUsedRelationManager;
use App\Filament\Resources\JobCards\RelationManagers\VehicleInspectionsRelationManager;
use UnitEnum;
class JobCardResource extends Resource
{
    protected static ?string $model = JobCard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string|UnitEnum|null $navigationGroup = 'Workshop';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'job_number';

    public static function form(Schema $schema): Schema
    {
        return JobCardForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JobCardInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobCardsTable::configure($table);
    }

    // ✅ Add the relation managers
    public static function getRelations(): array
    {
        return [
            JobServicesRelationManager::class,
            PartsUsedRelationManager::class,
            VehicleInspectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobCards::route('/'),
            'create' => CreateJobCard::route('/create'),
            'view' => ViewJobCard::route('/{record}'),
            'edit' => EditJobCard::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $user = Filament::auth()->user();

        if ($user && ! $user->hasRole(['admin', 'manager'])) {
            if ($user->branch_id) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        return $query;
    }


}
