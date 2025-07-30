<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatteryMovementResource\Pages;
use App\Filament\Resources\BatteryMovementResource\RelationManagers;
use App\Models\BatteryMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class BatteryMovementResource extends Resource
{
    protected static ?string $model = BatteryMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Cold Storage Management';

    protected static ?string $modelLabel = 'Battery Movement';

    protected static ?string $pluralModelLabel = 'Battery Movements';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
    public static function canEdit($record): bool
    {
        return auth()->user()?->hasRole('admin');
    }
    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Movement Information')
                    ->schema([
                        Select::make('battery_id')
                            ->label('Battery')
                            ->relationship('battery', 'unique_code')
                            ->searchable()
                            ->required(),
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('movement_type')
                            ->label('Movement Type')
                            ->options([
                                'checkout' => 'Checkout',
                                'return' => 'Return',
                                'swap' => 'Swap'
                            ])
                            ->required(),
                        Select::make('from_unit_id')
                            ->label('From Unit')
                            ->relationship('fromUnit', 'name')
                            ->searchable(),
                        Select::make('to_unit_id')
                            ->label('To Unit')
                            ->relationship('toUnit', 'name')
                            ->searchable(),
                    ])->columns(2),

                Section::make('Condition Tracking')
                    ->schema([
                        Select::make('condition_before')
                            ->label('Condition Before')
                            ->options([
                                'excellent' => 'Excellent',
                                'good' => 'Good',
                                'fair' => 'Fair',
                                'poor' => 'Poor',
                                'defective' => 'Defective'
                            ]),
                        Select::make('condition_after')
                            ->label('Condition After')
                            ->options([
                                'excellent' => 'Excellent',
                                'good' => 'Good',
                                'fair' => 'Fair',
                                'poor' => 'Poor',
                                'defective' => 'Defective'
                            ]),
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
                TextColumn::make('battery.unique_code')
                    ->label('Battery Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('movement_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'checkout' => 'info',
                        'return' => 'success',
                        'swap' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('fromUnit.name')
                    ->label('From Unit')
                    ->searchable(),
                TextColumn::make('toUnit.name')
                    ->label('To Unit')
                    ->searchable(),
                TextColumn::make('condition_before')
                    ->label('Before')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'excellent' => 'success',
                        'good' => 'info',
                        'fair' => 'warning',
                        'poor' => 'danger',
                        'defective' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('condition_after')
                    ->label('After')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'excellent' => 'success',
                        'good' => 'info',
                        'fair' => 'warning',
                        'poor' => 'danger',
                        'defective' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('movement_type')
                    ->label('Movement Type')
                    ->options([
                        'checkout' => 'Checkout',
                        'return' => 'Return',
                        'swap' => 'Swap'
                    ]),
                Tables\Filters\SelectFilter::make('battery_id')
                    ->label('Battery')
                    ->relationship('battery', 'unique_code'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBatteryMovements::route('/'),
            'create' => Pages\CreateBatteryMovement::route('/create'),
            'edit' => Pages\EditBatteryMovement::route('/{record}/edit'),
        ];
    }
}
