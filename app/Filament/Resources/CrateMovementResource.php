<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CrateMovementResource\Pages;
use App\Models\CrateMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Models\Hub;
use App\Models\User;
use App\Models\Visit;

class CrateMovementResource extends Resource
{
    protected static ?string $model = CrateMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Crate Tracker';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Movement Details')
                    ->schema([
                        Select::make('from_hub_id')
                            ->label('From Hub')
                            ->options(Hub::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Select::make('to_hub_id')
                            ->label('To Hub')
                            ->options(Hub::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        TextInput::make('crate_count')
                            ->label('Number of Crates')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->default(0),
                        Select::make('scale_type')
                            ->label('Scale Type (if moving scales)')
                            ->options([
                                'digital_scale' => 'Digital Scale',
                                'analog_scale' => 'Analog Scale',
                                'hanging_scale' => 'Hanging Scale',
                                'platform_scale' => 'Platform Scale',
                            ])
                            ->nullable()
                            ->placeholder('Select scale type if moving scales'),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Additional notes about the movement...'),
                    ])->columns(2),

                Section::make('User & Visit Information')
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->options(User::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Select::make('visit_id')
                            ->label('Visit (Optional)')
                            ->options(Visit::all()->pluck('id', 'id'))
                            ->nullable()
                            ->searchable(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fromHub.name')
                    ->label('From Hub')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('toHub.name')
                    ->label('To Hub')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('crate_count')
                    ->label('Crates Moved')
                    ->sortable(),
                BadgeColumn::make('scale_type')
                    ->label('Scale Type')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'None';
                        return match($state) {
                            'digital_scale' => 'Digital Scale',
                            'analog_scale' => 'Analog Scale',
                            'hanging_scale' => 'Hanging Scale',
                            'platform_scale' => 'Platform Scale',
                            default => $state
                        };
                    })
                    ->colors([
                        'success' => 'digital_scale',
                        'warning' => 'analog_scale',
                        'info' => 'hanging_scale',
                        'danger' => 'platform_scale',
                        'gray' => null,
                    ]),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('visit.id')
                    ->label('Visit ID')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->notes ? $record->notes : 'No notes provided';
                    }),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('from_hub_id')
                    ->label('From Hub')
                    ->options(Hub::all()->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('to_hub_id')
                    ->label('To Hub')
                    ->options(Hub::all()->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('scale_type')
                    ->label('Scale Type')
                    ->options([
                        'digital_scale' => 'Digital Scale',
                        'analog_scale' => 'Analog Scale',
                        'hanging_scale' => 'Hanging Scale',
                        'platform_scale' => 'Platform Scale',
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
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCrateMovements::route('/'),
            'create' => Pages\CreateCrateMovement::route('/create'),
            'edit' => Pages\EditCrateMovement::route('/{record}/edit'),
        ];
    }
}
