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
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventory Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Item Information')
                    ->schema([
                        TextInput::make('product')
                            ->label('Product Name')
                            ->required()
                            ->maxLength(255),
                        Select::make('item_type')
                            ->label('Item Type')
                            ->options([
                                'tool' => 'Tool',
                                'spare_part' => 'Spare Part'
                            ])
                            ->default('tool')
                            ->required(),
                        Select::make('condition')
                            ->label('Condition')
                            ->options([
                                'New' => 'New',
                                'Good' => 'Good',
                                'Fair' => 'Fair',
                                'Poor' => 'Poor',
                            ])
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Quantity at Kibiku')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->default(0),
                        Forms\Components\DatePicker::make('date_added')
                            ->label('Date Added')
                            ->required()
                            ->default(now()),
                    ])->columns(2),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('Inventory create data:', $data);
        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('item_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tool' => 'blue',
                        'spare_part' => 'green',
                        default => 'gray',
                    }),
                TextColumn::make('quantity')
                    ->label('Quantity at Kibiku')
                    ->sortable(),
                TextColumn::make('condition')
                    ->label('Condition')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'New' => 'success',
                        'Good' => 'info',
                        'Fair' => 'warning',
                        'Poor' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('date_added')
                    ->label('Date Added')
                    ->date()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('item_type')
                    ->label('Item Type')
                    ->options([
                        'tool' => 'Tool',
                        'spare_part' => 'Spare Part'
                    ]),
                Tables\Filters\SelectFilter::make('condition')
                    ->label('Condition')
                    ->options([
                        'New' => 'New',
                        'Good' => 'Good',
                        'Fair' => 'Fair',
                        'Poor' => 'Poor',
                    ]),
                Tables\Filters\SelectFilter::make('stock_level')
                    ->label('Stock Level')
                    ->options([
                        'In Stock' => 'In Stock',
                        'Low Stock' => 'Low Stock',
                        'Out of Stock' => 'Out of Stock',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
        return 'Inventory';
    }

    public static function getLabel(): string
    {
        return 'Inventory';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('inventoryLocations.location');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }
}
