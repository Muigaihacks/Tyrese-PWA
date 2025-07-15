<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitResource\Pages;
use App\Filament\Resources\VisitResource\RelationManagers;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

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
                \Filament\Forms\Components\Select::make('unit_id')
                    ->label('Leased Unit')
                    ->relationship('leasedUnit', 'name')
                    ->required(),
                \Filament\Forms\Components\DatePicker::make('scheduled_for')
                    ->label('Scheduled For')
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                \Filament\Forms\Components\Select::make('scheduled_by')
                    ->label('Scheduled By')
                    ->relationship('scheduler', 'name')
                    ->required(),
                \Filament\Forms\Components\Textarea::make('notes')->label('Notes')->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('leasedUnit.name')->label('Leased Unit')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('scheduled_for')->label('Scheduled For')->date(),
                \Filament\Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
                \Filament\Tables\Columns\TextColumn::make('scheduler.name')->label('Scheduled By'),
                \Filament\Tables\Columns\TextColumn::make('notes')->label('Notes')->limit(30),
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
            'index' => Pages\ListVisits::route('/'),
            'create' => Pages\CreateVisit::route('/create'),
            'edit' => Pages\EditVisit::route('/{record}/edit'),
        ];
    }
}
