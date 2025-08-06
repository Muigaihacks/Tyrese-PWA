<?php

namespace App\Filament\Resources\CasualLabourerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class AttendanceRelationManager extends RelationManager
{
    protected static string $relationship = 'attendance';

    protected static ?string $recordTitleAttribute = 'work_date';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('work_date')
                    ->required(),
                Forms\Components\TimePicker::make('time_in')
                    ->seconds(false),
                Forms\Components\TimePicker::make('time_out')
                    ->seconds(false),
                Forms\Components\TextInput::make('job_description')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(1000),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('work_date')
            ->columns([
                TextColumn::make('work_date')->label('Date')->date()->sortable(),
                TextColumn::make('time_in')->label('Time In')->time()->sortable(),
                TextColumn::make('time_out')->label('Time Out')->time()->sortable(),
                TextColumn::make('job_description')->label('Job Description')->limit(30),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if (!$record->time_in) { return 'Not Started'; }
                        if ($record->time_in && !$record->time_out) { return 'In Progress'; }
                        return 'Completed';
                    })
                    ->colors([
                        'gray' => 'Not Started',
                        'warning' => 'In Progress',
                        'success' => 'Completed',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'not_started' => 'Not Started',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed'
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!isset($data['value'])) {
                            return $query;
                        }

                        return match($data['value']) {
                            'not_started' => $query->whereNull('time_in'),
                            'in_progress' => $query->whereNotNull('time_in')->whereNull('time_out'),
                            'completed' => $query->whereNotNull('time_in')->whereNotNull('time_out'),
                            default => $query
                        };
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            ->defaultSort('work_date', 'desc');
    }
}
