<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatteryResource\Pages;
use App\Filament\Resources\BatteryResource\RelationManagers;
use App\Models\Battery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class BatteryResource extends Resource
{
    protected static ?string $model = Battery::class;

    protected static ?string $navigationIcon = 'heroicon-o-battery-100';

    protected static ?string $navigationGroup = 'Cold Storage Management';

    protected static ?string $modelLabel = 'Battery';

    protected static ?string $pluralModelLabel = 'Batteries';

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
                Section::make('Battery Information')
                    ->schema([
                        TextInput::make('unique_code')
                            ->label('Unique Code')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('cold_storage_unit_id')
                            ->label('Cold Storage Unit')
                            ->options(function () {
                                $units = \App\Models\LeasedUnit::pluck('name', 'id')->toArray();
                                return ['kibiku' => 'KIBIKU'] + $units;
                            })
                            ->searchable()
                            ->required(),
                        Select::make('condition')
                            ->label('Condition')
                            ->options([
                                'excellent' => 'Excellent',
                                'good' => 'Good',
                                'fair' => 'Fair',
                                'poor' => 'Poor',
                                'defective' => 'Defective'
                            ])
                            ->default('good')
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'maintenance' => 'Maintenance',
                                'retired' => 'Retired'
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->maxLength(1000),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('unique_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('coldStorageUnit.name')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('condition')
                    ->label('Condition')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'excellent' => 'success',
                        'good' => 'info',
                        'fair' => 'warning',
                        'poor' => 'danger',
                        'defective' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'maintenance' => 'warning',
                        'retired' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('condition')
                    ->label('Condition')
                    ->options([
                        'excellent' => 'Excellent',
                        'good' => 'Good',
                        'fair' => 'Fair',
                        'poor' => 'Poor',
                        'defective' => 'Defective'
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                        'retired' => 'Retired'
                    ]),
                Tables\Filters\SelectFilter::make('cold_storage_unit_id')
                    ->label('Unit')
                    ->relationship('coldStorageUnit', 'name'),
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
            'index' => Pages\ListBatteries::route('/'),
            'create' => Pages\CreateBattery::route('/create'),
            'edit' => Pages\EditBattery::route('/{record}/edit'),
        ];
    }
}
