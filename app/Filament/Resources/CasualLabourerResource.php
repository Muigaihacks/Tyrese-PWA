<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CasualLabourerResource\Pages;
use App\Models\CasualLabourer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;

class CasualLabourerResource extends Resource
{
    protected static ?string $model = CasualLabourer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Insurance Management';

    protected static ?string $modelLabel = 'Casual Labourer';

    protected static ?string $pluralModelLabel = 'Casual Labourers';

    public static function canViewAny(): bool
    {
        return true; // Temporarily allow all access
    }

    public static function canCreate(): bool
    {
        return true; // Temporarily allow all access
    }

    public static function canEdit($record): bool
    {
        return true; // Temporarily allow all access
    }

    public static function canDelete($record): bool
    {
        return true; // Temporarily allow all access
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'M' => 'Male',
                                'F' => 'Female'
                            ])
                            ->required(),
                        Select::make('age_group')
                            ->label('Age Group')
                            ->options([
                                '18-35' => '18-35',
                                '36+ YEARS' => '36+ YEARS'
                            ])
                            ->required(),
                        TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->tel()
                            ->required(),
                        TextInput::make('id_number')
                            ->label('ID Number')
                            ->required(),
                    ])->columns(2),

                Section::make('Emergency Contact')
                    ->schema([
                        TextInput::make('next_of_kin_name')
                            ->label('Next of Kin Name')
                            ->required(),
                        TextInput::make('next_of_kin_phone')
                            ->label('Next of Kin Phone')
                            ->tel()
                            ->required(),
                    ])->columns(2),

                Section::make('Safety Compliance')
                    ->schema([
                        Checkbox::make('health_declaration')
                            ->label('Health Declaration - Sound mind and good health'),
                        Checkbox::make('skills_confirmation')
                            ->label('Skills Confirmation - Possess necessary skills'),
                        Checkbox::make('ppe_provided')
                            ->label('PPE Provided - Given necessary PPE'),
                        Checkbox::make('safety_briefing')
                            ->label('Safety Briefing - Briefed on safety procedures'),
                        Checkbox::make('tool_safety_agreement')
                            ->label('Tool Safety - Will use tools carefully'),
                        Checkbox::make('accident_cover_enrolled')
                            ->label('Accident Cover - Enrolled for personal accident cover'),
                        Checkbox::make('data_consent')
                            ->label('Data Consent - Allow SokoFresh to retain data'),
                    ])->columns(2),

                Section::make('Contract Information')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'terminated' => 'Terminated'
                            ])
                            ->default('active')
                            ->required(),
                        DatePicker::make('contract_start_date')
                            ->label('Contract Start Date')
                            ->required(),
                        DatePicker::make('contract_end_date')
                            ->label('Contract End Date')
                            ->nullable(),
                        Select::make('user_id')
                            ->label('Link to User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select the user account this casual labourer will use to log in'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->label('Gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'M' => 'blue',
                        'F' => 'pink',
                        default => 'gray',
                    }),
                TextColumn::make('age_group')
                    ->label('Age Group'),
                TextColumn::make('phone_number')
                    ->label('Phone')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Linked User')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => 'terminated',
                    ]),
                TextColumn::make('today_status')
                    ->label('Today\'s Status')
                    ->getStateUsing(function ($record) {
                        $todayAttendance = $record->attendance()->where('work_date', today())->first();
                        if (!$todayAttendance) {
                            return 'Not Clocked In';
                        }
                        if ($todayAttendance->time_in && !$todayAttendance->time_out) {
                            return 'Currently Working';
                        }
                        if ($todayAttendance->time_in && $todayAttendance->time_out) {
                            return 'Completed Today';
                        }
                        return 'Not Clocked In';
                    })
                    ->badge()
                    ->color(function ($state) {
                        return match($state) {
                            'Currently Working' => 'success',
                            'Completed Today' => 'info',
                            default => 'gray'
                        };
                    }),
                TextColumn::make('total_hours_today')
                    ->label('Hours Today')
                    ->getStateUsing(function ($record) {
                        $todayAttendance = $record->attendance()->where('work_date', today())->first();
                        if ($todayAttendance && $todayAttendance->total_hours_decimal) {
                            return number_format($todayAttendance->total_hours_decimal, 1) . 'h';
                        }
                        return '-';
                    }),
                TextColumn::make('total_hours_month')
                    ->label('Hours This Month')
                    ->getStateUsing(function ($record) {
                        $totalHours = $record->attendance()
                            ->whereMonth('work_date', now()->month)
                            ->whereYear('work_date', now()->year)
                            ->sum('total_hours_decimal');
                        return number_format($totalHours, 1) . 'h';
                    }),
                IconColumn::make('health_declaration')
                    ->label('Health')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                IconColumn::make('ppe_provided')
                    ->label('PPE')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                IconColumn::make('accident_cover_enrolled')
                    ->label('Insurance')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('contract_start_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'terminated' => 'Terminated'
                    ]),
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Gender')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female'
                    ]),
                Tables\Filters\SelectFilter::make('attendance_status')
                    ->label('Today\'s Attendance')
                    ->options([
                        'not_clocked_in' => 'Not Clocked In',
                        'currently_working' => 'Currently Working',
                        'completed_today' => 'Completed Today'
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!isset($data['value'])) {
                            return $query;
                        }

                        $today = today();
                        
                        return match($data['value']) {
                            'not_clocked_in' => $query->whereDoesntHave('attendance', function ($q) use ($today) {
                                $q->where('work_date', $today);
                            }),
                            'currently_working' => $query->whereHas('attendance', function ($q) use ($today) {
                                $q->where('work_date', $today)
                                  ->whereNotNull('time_in')
                                  ->whereNull('time_out');
                            }),
                            'completed_today' => $query->whereHas('attendance', function ($q) use ($today) {
                                $q->where('work_date', $today)
                                  ->whereNotNull('time_in')
                                  ->whereNotNull('time_out');
                            }),
                            default => $query
                        };
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_attendance')
                    ->label('View Attendance')
                    ->icon('heroicon-o-calendar')
                    ->color('info')
                    ->url(fn ($record) => route('filament.admin.resources.casual-labourers.edit', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('force_clock_out')
                    ->label('Force Clock Out')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Force Clock Out')
                    ->modalDescription('Are you sure you want to force clock out this labourer? This will mark them as completed for today.')
                    ->action(function ($record) {
                        $todayAttendance = $record->attendance()->where('work_date', today())->first();
                        if ($todayAttendance && $todayAttendance->time_in && !$todayAttendance->time_out) {
                            $todayAttendance->update([
                                'time_out' => now(),
                            ]);
                            $todayAttendance->calculateTotalHours();
                        }
                    })
                    ->visible(function ($record) {
                        $todayAttendance = $record->attendance()->where('work_date', today())->first();
                        return $todayAttendance && $todayAttendance->time_in && !$todayAttendance->time_out;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\CasualLabourerResource\RelationManagers\AttendanceRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCasualLabourers::route('/'),
            'create' => Pages\CreateCasualLabourer::route('/create'),
            'edit' => Pages\EditCasualLabourer::route('/{record}/edit'),
        ];
    }
}
