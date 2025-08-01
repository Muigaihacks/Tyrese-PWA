<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeasedUnitResource\Pages;
use App\Filament\Resources\LeasedUnitResource\RelationManagers;
use App\Models\LeasedUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class LeasedUnitResource extends Resource
{
    protected static ?string $model = LeasedUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Cold Storage Management';

    protected static ?string $modelLabel = 'Cold Storage Unit';

    protected static ?string $pluralModelLabel = 'Cold Storage Units';

    public static function canViewAny(): bool
    {
        return true; // Temporarily allow all access
        // return auth()->user()?->hasRole('admin');
    }
    public static function canCreate(): bool
    {
        return true; // Temporarily allow all access
        // return auth()->user()?->hasRole('admin');
    }
    public static function canEdit($record): bool
    {
        return true; // Temporarily allow all access
        // return auth()->user()?->hasRole('admin');
    }
    public static function canDelete($record): bool
    {
        return true; // Temporarily allow all access
        // return auth()->user()?->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Unit Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Unit Name')
                            ->required()
                            ->maxLength(255),
                        Select::make('unit_type')
                            ->label('Unit Type')
                            ->options([
                                'cold_storage' => 'Cold Storage Unit',
                                'NTU' => 'NTU (Heavy Duty Freezer)'
                            ])
                            ->default('cold_storage')
                            ->required(),
                        Select::make('ownership_status')
                            ->label('Ownership Status')
                            ->options([
                                'SokoFresh' => 'SokoFresh',
                                'SokoFresh LLP' => 'SokoFresh LLP'
                            ])
                            ->default('SokoFresh')
                            ->required(),
                        Select::make('unit_status')
                            ->label('Unit Status')
                            ->options([
                                'leased' => 'Leased',
                                'lease-to-own' => 'Lease-to-Own',
                                'outright_purchase' => 'Outright Purchase'
                            ])
                            ->default('leased')
                            ->required(),
                        TextInput::make('battery_count')
                            ->label('Number of Batteries')
                            ->numeric()
                            ->default(2)
                            ->minValue(0)
                            ->required(),
                    ])->columns(2),

                Section::make('Battery Assignment')
                    ->schema([
                        Select::make('batteries')
                            ->label('Assign Batteries')
                            ->multiple()
                            ->relationship('batteries', 'unique_code')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select batteries to assign to this unit'),
                    ])->columns(1),

                Section::make('Location Information')
                    ->schema([
                        TextInput::make('address')
                            ->label('Address')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->required()
                            ->numeric()
                            ->step(0.000001),
                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->required()
                            ->numeric()
                            ->step(0.000001),
                    ])->columns(3),

                Section::make('Lessee Information')
                    ->schema([
                        TextInput::make('lessee_name')
                            ->label('Lessee Name')
                            ->maxLength(255),
                        TextInput::make('lessee_contact')
                            ->label('Lessee Contact')
                            ->maxLength(255),
                        TextInput::make('leasing_fee')
                            ->label('Leasing Fee (Ksh)')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->required()
                            ->default(0),
                    ])->columns(3),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->maxLength(1000),
                        Textarea::make('unit_notes')
                            ->label('Unit Notes')
                            ->rows(3)
                            ->maxLength(1000),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Unit Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cold_storage' => 'info',
                        'NTU' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('ownership_status')
                    ->label('Ownership')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'SokoFresh' => 'success',
                        'SokoFresh LLP' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('unit_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'leased' => 'info',
                        'lease-to-own' => 'warning',
                        'outright_purchase' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('address')
                    ->label('Address')
                    ->limit(30)
                    ->searchable(),
                TextColumn::make('lessee_name')
                    ->label('Lessee')
                    ->searchable(),
                TextColumn::make('battery_count')
                    ->label('Batteries')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unit_type')
                    ->label('Unit Type')
                    ->options([
                        'cold_storage' => 'Cold Storage Unit',
                        'NTU' => 'NTU (Heavy Duty Freezer)'
                    ]),
                Tables\Filters\SelectFilter::make('ownership_status')
                    ->label('Ownership')
                    ->options([
                        'SokoFresh' => 'SokoFresh',
                        'SokoFresh LLP' => 'SokoFresh LLP'
                    ]),
                Tables\Filters\SelectFilter::make('unit_status')
                    ->label('Status')
                    ->options([
                        'leased' => 'Leased',
                        'lease-to-own' => 'Lease-to-Own',
                        'outright_purchase' => 'Outright Purchase'
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeasedUnits::route('/'),
            'create' => Pages\CreateLeasedUnit::route('/create'),
            'edit' => Pages\EditLeasedUnit::route('/{record}/edit'),
        ];
    }
}
