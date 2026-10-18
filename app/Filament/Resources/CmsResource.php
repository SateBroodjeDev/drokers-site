<?php

namespace App\Filament\Resources;

use App\Models\MediaAsset;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

abstract class CmsResource extends Resource
{
    protected static bool $isDiscovered = false;
    protected static string|UnitEnum|null $navigationGroup = 'Website beheren';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && $user->is_admin && $user->is_active;
    }

    public static function canCreate(): bool { return static::canViewAny(); }
    public static function canEdit(Model $record): bool { return static::canViewAny(); }
    public static function canDelete(Model $record): bool { return static::canViewAny(); }
    public static function canRestore(Model $record): bool { return static::canViewAny(); }
    public static function canReorder(): bool { return static::canViewAny(); }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        return static::getModel() === MediaAsset::class ? $query : $query->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function table(Table $table): Table
    {
        $media = static::getModel() === MediaAsset::class;
        return $table->columns([
            TextColumn::make(static::$recordTitleAttribute ?? 'title')->label('Naam')->searchable()->sortable()->wrap(),
            ...($media ? [] : [
                TextColumn::make('status')->label('Publicatie')->badge()->formatStateUsing(fn ($state) => ['draft' => 'Concept', 'published' => 'Gepubliceerd', 'archived' => 'Gearchiveerd'][$state] ?? $state)
                    ->color(fn ($state) => $state === 'published' ? 'success' : ($state === 'archived' ? 'gray' : 'warning')),
                TextColumn::make('sort_order')->label('Volgorde')->sortable(),
            ]),
            TextColumn::make('updated_at')->label('Bijgewerkt')->dateTime('d-m-Y H:i')->sortable(),
        ])->defaultSort($media ? 'updated_at' : 'sort_order', $media ? 'desc' : 'asc')
            ->reorderable($media ? null : 'sort_order')
            ->filters($media ? [] : [SelectFilter::make('status')->label('Publicatie')->options(['draft' => 'Concept', 'published' => 'Gepubliceerd', 'archived' => 'Gearchiveerd']), TrashedFilter::make()])
            ->recordActions($media ? [EditAction::make()] : [EditAction::make(), RestoreAction::make()]);
    }
}
