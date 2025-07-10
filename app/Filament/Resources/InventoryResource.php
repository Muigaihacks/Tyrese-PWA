<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Location;
use Illuminate\Support\Facades\Log;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product')->label('Product'),
                Forms\Components\Select::make('condition')
                    ->label('Condition')
                    ->options([
                        'New' => 'New',
                        'Good' => 'Good',
                        'Fair' => 'Fair',
                        'Poor' => 'Poor',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('date_added')
                    ->label('Date Added')
                    ->required()
                    ->default(now()),
                Repeater::make('inventoryLocations')
                    ->relationship('inventoryLocations')
                    ->schema([
                        Select::make('location_id')
                            ->label('Location')
                            ->options(Location::pluck('name', 'id'))
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
                    ->label('Locations & Quantities')
                    ->createItemButtonLabel('Add Location')
                    ->columns(2),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('Inventory create data:', $data);
        return $data;
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('product')->label('Product'),
                TextColumn::make('date_added')->label('Date Added'),
                TextColumn::make('locations_and_quantities')
                    ->label('Locations')
                    ->getStateUsing(function ($record) {
                        // $record is the Inventory model
                        return $record->inventoryLocations;
                    })
                    ->formatStateUsing(function ($state) {
                        if (is_iterable($state)) {
                            return collect($state)->map(function ($invLoc) {
                                return $invLoc->location->name . ' (' . $invLoc->quantity . ')';
                            })->join(', ');
                        }
                        return '';
                    }),
                BadgeColumn::make('stock_level')
                    ->label('Stock Level')
                    ->getStateUsing(fn ($record) => $record->stock_level)
                    ->colors([
                        'success' => 'In Stock',
                        'warning' => 'Low Stock',
                        'danger' => 'Out of Stock',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('stock_level')
                    ->label('Stock Level')
                    ->options([
                        'Normal' => 'Normal',
                        'Critical' => 'Critical',
                    ]),
                Tables\Filters\Filter::make('min_quantity')
                    ->label('Min Quantity')
                    ->form([
                        Forms\Components\TextInput::make('min')->numeric()->label('Minimum'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['min']) {
                            $query->where('quantity', '>=', $data['min']);
                        }
                    }),
                Tables\Filters\Filter::make('max_quantity')
                    ->label('Max Quantity')
                    ->form([
                        Forms\Components\TextInput::make('max')->numeric()->label('Maximum'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['max']) {
                            $query->where('quantity', '<=', $data['max']);
                        }
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\InventoryResource\RelationManagers\InventoryActionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return 'Inventory'; // or 'Storage', 'Map'
    }

    public static function getLabel(): string
    {
        return 'Inventory'; // or 'Storage', 'Map'
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('inventoryLocations.location');
    }
}
