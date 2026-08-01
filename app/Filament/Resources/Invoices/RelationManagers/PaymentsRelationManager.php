<?php

namespace App\Filament\Resources\Invoices\RelationManagers;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'reference_number';

    protected static ?string $title = 'Payments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Record Payment')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Amount (ETB)')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->maxValue(fn () => $this->getOwnerRecord()->balance)
                            ->prefix('ETB')
                            ->step(0.01)
                            ->helperText(fn () => 'Remaining balance: ETB ' . number_format($this->getOwnerRecord()->balance, 2))
                            ->columnSpan(1),

                        Select::make('method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'bank_transfer' => 'Bank Transfer',
                                'telebirr' => 'Telebirr',
                                'cbe_birr' => 'CBE Birr',
                                'cheque' => 'Cheque',
                            ])
                            ->required()
                            ->searchable()
                            ->columnSpan(1),

                        TextInput::make('reference_number')
                            ->label('Reference Number')
                            ->maxLength(100)
                            ->placeholder('e.g. Transaction ID, Cheque No.')
                            ->helperText('Optional reference for tracking')
                            ->columnSpan(2),
//                        FileUpload::make('proof_path')
//                            ->label('Payment Screenshot')
//                            ->image()
//                            ->disk('public_uploads')
//                            ->directory('payment-proofs')
//                            ->visibility('public')
//                            ->maxSize(5120)
//                            ->imageResizeTargetWidth('800')
//                            ->imageResizeTargetHeight('600')
//                            ->imageResizeMode('cover')
//                            ->openable()
//                            ->downloadable()
//                            ->previewable(false)
//                            ->helperText('Upload a screenshot for bank transfer, Telebirr, or CBE Birr payments')
//                            ->columnSpan(2),
                        FileUpload::make('proof_path')
                            ->label('Payment Screenshot')
                            ->image()
                            ->disk('public_uploads')
                            ->directory('payment-proofs')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('600')
                            ->imageResizeMode('cover')
                            ->helperText('Upload a screenshot for bank transfer, Telebirr, or CBE Birr payments')
                            ->columnSpan(2),
                        DateTimePicker::make('paid_at')
                            ->label('Payment Date')
                            ->default(now())
                            ->required()
                            ->columnSpan(1),

                        Select::make('received_by')
                            ->label('Received By')
                            ->options(fn () => User::query()
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                            )
                            ->default(fn () => auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->nullable()
                            ->placeholder('Additional notes about this payment...')
                            ->columnSpan(2),
                    ])
                    ->compact()
                    ->collapsible(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('ETB')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'bank_transfer' => 'info',
                        'telebirr' => 'primary',
                        'cbe_birr' => 'warning',
                        'cheque' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('proof_path')
                    ->label('Proof')
                    ->state(function ($record) {
                        if (! $record->proof_path) {
                            return '—';
                        }
                        $url = route('payment-proofs.show', $record);
                        return "<img src=\"{$url}\" style=\"width:40px;height:40px;object-fit:cover;border-radius:4px;\">";
                    })
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('reference_number')
                    ->label('Reference')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('receiver.name')
                    ->label('Received By')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('paid_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Recorded')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('notes')
                    ->label('Notes')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('paid_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('method')
                    ->options([
                        'cash' => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                        'telebirr' => 'Telebirr',
                        'cbe_birr' => 'CBE Birr',
                        'cheque' => 'Cheque',
                    ]),
            ])
//            ->recordActions([
//                \Filament\Actions\EditAction::make(),
//                \Filament\Actions\DeleteAction::make(),
//            ])
            ->recordActions([
                \Filament\Actions\Action::make('viewProof')
                    ->label('View Proof')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->visible(fn ($record) => (bool) $record->proof_path)
                    ->url(fn ($record) => route('payment-proofs.show', $record))
                    ->openUrlInNewTab(),

                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Record Payment'),
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
