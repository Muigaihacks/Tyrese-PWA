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

                    ])->columns(2),


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
