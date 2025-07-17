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

class LeasedUnitResource extends Resource
{
    protected static ?string $model = LeasedUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Map & Field Operations';

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
                TextInput::make('name')->label('Unit Name')->required(),
                TextInput::make('address')->label('Address'),
                TextInput::make('latitude')->label('Latitude')->required(),
                TextInput::make('longitude')->label('Longitude')->required(),
                TextInput::make('lessee_name')->label('Lessee Name'),
                TextInput::make('lessee_contact')->label('Lessee Contact'),
                Textarea::make('notes')->label('Notes')->rows(3),
                TextInput::make('leasing_fee')
                    ->label('Leasing Fee (Ksh)')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->step(0.01),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->label('Unit Name')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('address')->label('Address')->limit(30),
                \Filament\Tables\Columns\TextColumn::make('lessee_name')->label('Lessee')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('lessee_contact')->label('Contact'),
                \Filament\Tables\Columns\TextColumn::make('latitude')->label('Lat'),
                \Filament\Tables\Columns\TextColumn::make('longitude')->label('Lng'),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
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
