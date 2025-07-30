<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InsuranceResource\Pages;
use App\Filament\Resources\InsuranceResource\RelationManagers;
use App\Models\Insurance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InsuranceResource extends Resource
{
    protected static ?string $model = Insurance::class;

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

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')->label('Name')->required(),
                \Filament\Forms\Components\TextInput::make('id_number')->label('ID Number')->required(),
                \Filament\Forms\Components\TextInput::make('phone_number')->label('Phone Number')->required(),
                \Filament\Forms\Components\FileUpload::make('insurance_copy')->label('Insurance Copy'),
                \Filament\Forms\Components\DatePicker::make('cover_expiry')->label('Cover Expiry')->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->color(fn ($record) => !$record->active ? 'gray' : (\Carbon\Carbon::parse($record->cover_expiry)->isPast() ? 'danger' : 'default'))
                    ->weight(fn ($record) => !$record->active ? 'normal' : 'bold'),
                TextColumn::make('id_number')
                    ->label('ID Number')
                    ->color(fn ($record) => !$record->active ? 'gray' : (\Carbon\Carbon::parse($record->cover_expiry)->isPast() ? 'danger' : 'default')),
                TextColumn::make('phone_number')
                    ->label('Phone Number')
                    ->color(fn ($record) => !$record->active ? 'gray' : (\Carbon\Carbon::parse($record->cover_expiry)->isPast() ? 'danger' : 'default')),
                TextColumn::make('insurance_copy')
                    ->label('Insurance copy')
                    ->color(fn ($record) => !$record->active ? 'gray' : (\Carbon\Carbon::parse($record->cover_expiry)->isPast() ? 'danger' : 'default')),
                BadgeColumn::make('cover_expiry')
                    ->label('Expiry Date')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->isPast() ? 'Expired' : 'Valid')
                    ->color(fn ($state) => \Carbon\Carbon::parse($state)->isPast() ? 'danger' : 'success'),
                ToggleColumn::make('active')
                    ->label('Active')
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('active')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('cover_expiry')
                    ->label('Expiry Status')
                    ->options([
                        'valid' => 'Valid',
                        'expired' => 'Expired',
                    ])
                    ->query(function ($query, array $data) {
                        if (($data['value'] ?? null) === 'valid') {
                            $query->where('cover_expiry', '>=', now());
                        } elseif (($data['value'] ?? null) === 'expired') {
                            $query->where('cover_expiry', '<', now());
                        }
                    }),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListInsurances::route('/'),
            'create' => Pages\CreateInsurance::route('/create'),
            'edit' => Pages\EditInsurance::route('/{record}/edit'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return 'Insurance';
    }

    public static function getLabel(): string
    {
        return 'Insurance';
    }
}
