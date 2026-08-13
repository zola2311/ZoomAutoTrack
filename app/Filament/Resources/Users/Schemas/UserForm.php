<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Branch;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Abebe Kebede'),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('abebe@garage.com'),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->maxLength(20)
                            ->placeholder('e.g. 0911 123456')
                            ->tel(),

                        Select::make('branch_id')
                            ->label('Branch')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn () => auth()->user()?->can('branches.view_any')),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(fn ($operation) => $operation === 'create')
                            ->minLength(8)
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                            ->helperText('Minimum 8 characters. Leave blank to keep current password (for existing users).'),

                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'manager' => 'Manager',
                                'service_advisor' => 'Service Advisor',
                                'mechanic' => 'Mechanic',
                                'receptionist' => 'Receptionist',
                                'cashier' => 'Cashier',
                                'inventory_manager' => 'Inventory Manager',
                            ])
                            ->required()
                            ->native(false)
                            ->afterStateHydrated(function ($state, $set, $record) {
                                if ($record) {
                                    $set('role', $record->roles->first()?->name);
                                }
                            }),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive users cannot log in'),
                    ]),

                // Direct Permission Overrides
                Section::make('Direct Permission Overrides')
                    ->description('Grant or revoke specific permissions for this individual user beyond their assigned role.')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->relationship('permissions', 'name')
                            ->columns(3)
                            ->gridDirection('row')
                            ->bulkToggleable()
                            ->searchable(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Hidden::make('email_verified_at')
                    ->default(now()),
            ]);
    }
}
