<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HubResource\Pages;
use App\Models\Hub;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;

class HubResource extends Resource
{
    protected static ?string $model = Hub::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Crate Tracker';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Hub Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('location')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('crate_count')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->default(0),
                        TextInput::make('scale_count')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->default(0),
                        Toggle::make('is_kibiku')
                            ->label('Is Kibiku Hub')
                            ->default(false),
                    ])->columns(2),

                Section::make('Cold Storage Units (Kibiku Only)')
                    ->schema([
                        Repeater::make('coldStorageUnits')
                            ->relationship('coldStorageUnits')
                            ->schema([
                                TextInput::make('unit_id')
                                    ->label('Unit ID')
                                    ->required(),
                                TextInput::make('crate_count')
                                    ->label('Crate Count')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->default(0),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(2),
                            ])
                            ->columns(3)
                            ->visible(fn ($record) => $record?->is_kibiku ?? false)
                            ->visibleOn('edit')
                            ->visibleOn('create')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('crate_count')
                    ->label('Crates')
                    ->sortable(),
                TextColumn::make('scale_count')
                    ->label('Scales')
                    ->sortable(),
                BadgeColumn::make('is_kibiku')
                    ->label('Type')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Kibiku' : 'Hub')
                    ->colors([
                        'success' => true,
                        'gray' => false,
                    ]),
                TextColumn::make('coldStorageUnits_count')
                    ->label('Cold Storage Units')
                    ->counts('coldStorageUnits')
                    ->visible(fn ($record) => $record->is_kibiku),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_kibiku')
                    ->label('Hub Type')
                    ->options([
                        true => 'Kibiku',
                        false => 'Regular Hub',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHubs::route('/'),
            'create' => Pages\CreateHub::route('/create'),
            'edit' => Pages\EditHub::route('/{record}/edit'),
        ];
    }
}
