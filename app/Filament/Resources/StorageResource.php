<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StorageResource\Pages;
use App\Filament\Resources\StorageResource\RelationManagers;
use App\Models\Storage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StorageResource extends Resource
{
    protected static ?string $model = Storage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                \Filament\Forms\Components\TextInput::make('client_name')->label('Client Name')->required(),
                \Filament\Forms\Components\TextInput::make('phone_number')->label('Phone Number')->required(),
                \Filament\Forms\Components\TextInput::make('product_name')->label('Product Name')->required(),
                \Filament\Forms\Components\TextInput::make('quantity')->label('Quantity')->numeric()->required(),
                \Filament\Forms\Components\DatePicker::make('date')->label('Date')->required(),
                \Filament\Forms\Components\TextInput::make('fee')->label('Fee')->numeric()->required(),
                \Filament\Forms\Components\Select::make('unit')
                    ->options([
                        'KG' => 'KG',
                        'Tonne' => 'Tonne',
                        'Crates' => 'Crates',
                        'Boxes' => 'Boxes',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('client_name')->label('Client Name')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('phone_number')->label('Phone Number'),
                \Filament\Tables\Columns\TextColumn::make('product_name')->label('Product Name'),
                \Filament\Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->formatStateUsing(fn ($record) => $record->quantity . ' ' . $record->unit)
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('date')->label('Date')->date(),
                \Filament\Tables\Columns\TextColumn::make('fee')->label('Fee')->money('KES'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Optionally add EditAction here if you want editing
            ])
            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make()->label('Clear Record'),
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
            'index' => Pages\ListStorages::route('/'),
            'create' => Pages\CreateStorage::route('/create'),
            'edit' => Pages\EditStorage::route('/{record}/edit'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return 'Storage'; // or 'Storage', 'Map'
    }

    public static function getLabel(): string
    {
        return 'Storage'; // or 'Storage', 'Map'
    }
}