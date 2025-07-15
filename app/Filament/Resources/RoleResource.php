<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\CheckboxList;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use Filament\Forms\Components\Fieldset;

class RoleResource extends Resource
{
    protected static ?string $model = SpatieRole::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Roles & Permissions';
    protected static ?string $modelLabel = 'Role & Permission';
    protected static ?string $pluralModelLabel = 'Roles & Permissions';

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
        $userPermissions = Permission::where('group', 'User')->pluck('name', 'id')->toArray();
        $adminPermissions = Permission::where('group', 'Admin')->pluck('name', 'id')->toArray();

        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Role Name')
                    ->required()
                    ->unique(ignoreRecord: true),
                Fieldset::make('Permissions')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('Permissions')
                            ->relationship('permissions', 'name')
                            ->options(Permission::all()->pluck('name', 'id')->toArray())
                            ->columns(2)
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Role Name')->searchable()->sortable(),
                TextColumn::make('permissions_list')
                    ->label('Permissions')
                    ->getStateUsing(function ($record) {
                        return $record->permissions->pluck('name')->implode(', ');
                    })
                    ->badge()
                    ->limit(3),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Roles & Permissions';
    }

    public static function getLabel(): string
    {
        return 'Role & Permission';
    }

    public static function getPluralLabel(): string
    {
        return 'Roles & Permissions';
    }

    public static function afterSave($record, array $data): void
    {
        \Log::info('afterSave called', $data);
        $permissions = array_merge(
            $data['user_permissions'] ?? [],
            $data['admin_permissions'] ?? []
        );
        $record->syncPermissions($permissions);
    }

    public static function mutateFormDataBeforeFill(array $data): array
    {
        $role = \Spatie\Permission\Models\Role::find($data['id'] ?? null);
        if ($role) {
            $data['user_permissions'] = $role->permissions()->where('group', 'User')->pluck('id')->map(fn($id) => (string)$id)->toArray();
            $data['admin_permissions'] = $role->permissions()->where('group', 'Admin')->pluck('id')->map(fn($id) => (string)$id)->toArray();
            \Log::info('mutateFormDataBeforeFill', [
                'role_id' => $role->id,
                'user_permissions' => $data['user_permissions'],
                'admin_permissions' => $data['admin_permissions'],
            ]);
        }
        return $data;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('permissions');
    }
}
