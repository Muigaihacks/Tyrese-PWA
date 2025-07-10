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

class LeasedUnitResource extends Resource
{
    protected static ?string $model = LeasedUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Map & Field Operations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')->label('Unit Name')->required(),
                \Filament\Forms\Components\TextInput::make('address')->label('Address'),
                \Filament\Forms\Components\TextInput::make('latitude')->label('Latitude')->required(),
                \Filament\Forms\Components\TextInput::make('longitude')->label('Longitude')->required(),
                \Filament\Forms\Components\TextInput::make('lessee_name')->label('Lessee Name'),
                \Filament\Forms\Components\TextInput::make('lessee_contact')->label('Lessee Contact'),
                \Filament\Forms\Components\Textarea::make('notes')->label('Notes')->rows(3),
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
