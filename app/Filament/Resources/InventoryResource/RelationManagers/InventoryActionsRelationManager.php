<?php

namespace App\Filament\Resources\InventoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryActionsRelationManager extends RelationManager
{
    protected static string $relationship = 'inventoryActions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('action_type')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('action_type')
                    ->label('Action')
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'checkout' => 'Checkout',
                            'return' => 'Return',
                            'tools' => 'Checkout', // Legacy support
                            'batteries' => 'Checkout', // Legacy support
                            default => ucfirst($state)
                        };
                    })
                    ->badge()
                    ->color(function ($state) {
                        return match($state) {
                            'checkout', 'tools', 'batteries' => 'success',
                            'return' => 'warning',
                            default => 'gray'
                        };
                    }),
                \Filament\Tables\Columns\TextColumn::make('quantity')->label('Quantity'),
                \Filament\Tables\Columns\TextColumn::make('location_display')
                    ->label('Location')
                    ->getStateUsing(function ($record) {
                        // For return actions, always show KIBIKU
                        if (in_array($record->action_type, ['return'])) {
                            return 'KIBIKU';
                        }
                        
                        // For checkout actions (checkout, tools, batteries), get location from visit
                        if (in_array($record->action_type, ['checkout', 'tools', 'batteries'])) {
                            if ($record->visit && $record->visit->leasedUnit) {
                                return $record->visit->leasedUnit->name;
                            }
                        }
                        
                        return 'N/A';
                    }),
                \Filament\Tables\Columns\TextColumn::make('user.name')->label('User'),
                \Filament\Tables\Columns\TextColumn::make('visit.id')->label('Visit ID'),
                \Filament\Tables\Columns\TextColumn::make('condition_before')->label('Condition Before'),
                \Filament\Tables\Columns\TextColumn::make('condition_after')->label('Condition After'),
                \Filament\Tables\Columns\TextColumn::make('items_data')
                    ->label('Items Checked Out')
                    ->formatStateUsing(function ($record) {
                        if ($record->items_data && is_array($record->items_data)) {
                            $items = [];
                            foreach ($record->items_data as $item) {
                                $inventory = \App\Models\Inventory::find($item['inventory_id']);
                                $productName = $inventory ? $inventory->product : 'Unknown Item';
                                $items[] = "{$productName} (Qty: {$item['quantity']}, Condition: {$item['condition']})";
                            }
                            return implode('; ', $items);
                        }
                        return 'Single item checkout';
                    })
                    ->limit(100)
                    ->tooltip(function ($record) {
                        if ($record->items_data && is_array($record->items_data)) {
                            $items = [];
                            foreach ($record->items_data as $item) {
                                $inventory = \App\Models\Inventory::find($item['inventory_id']);
                                $productName = $inventory ? $inventory->product : 'Unknown Item';
                                $items[] = "{$productName} (Qty: {$item['quantity']}, Condition: {$item['condition']})";
                            }
                            return implode("\n", $items);
                        }
                        return 'Single item checkout';
                    }),
                \Filament\Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->notes ? $record->notes : 'No notes provided';
                    }),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime(),
            ]);
    }

    public function canCreate(): bool { return false; }
    public function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
    public function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
}
