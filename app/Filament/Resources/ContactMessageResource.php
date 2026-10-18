<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ContactMessageResource extends CmsResource
{
    protected static bool $isDiscovered = true;
    protected static ?string $model = ContactMessage::class;
    protected static ?string $modelLabel = 'bericht';
    protected static ?string $pluralModelLabel = 'Contactberichten';
    protected static ?string $recordTitleAttribute = 'subject';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;
    protected static ?int $navigationSort = 6;

    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; }
    public static function canView(Model $record): bool { return static::canViewAny(); }

    public static function getNavigationBadge(): ?string
    {
        if (! static::canViewAny()) return null;
        $count = ContactMessage::whereNull('handled_at')->count();
        return $count ? (string) $count : null;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('name')->label('Naam'),
            TextEntry::make('email')->label('E-mailadres'),
            TextEntry::make('subject')->label('Onderwerp')->columnSpanFull(),
            TextEntry::make('message')->label('Bericht')->columnSpanFull()->extraAttributes(['class' => 'whitespace-pre-wrap']),
            TextEntry::make('created_at')->label('Ontvangen')->dateTime('d-m-Y H:i'),
            TextEntry::make('handled_at')->label('Afgehandeld')->dateTime('d-m-Y H:i')->placeholder('Nog niet afgehandeld'),
        ]);
    }

    public static function messageActions(): array
    {
        return [
            Action::make('reply')->label('Antwoorden via e-mail')->icon(Heroicon::OutlinedArrowUturnLeft)
                ->url(fn (ContactMessage $record) => 'mailto:'.$record->email.'?subject='.rawurlencode('Re: '.$record->subject)),
            Action::make('handle')->label('Markeer als afgehandeld')->requiresConfirmation()
                ->visible(fn (ContactMessage $record) => $record->handled_at === null)
                ->action(fn (ContactMessage $record) => $record->update(['handled_at' => now()])),
            DeleteAction::make(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Afzender')->searchable()->wrap(),
            TextColumn::make('subject')->label('Onderwerp')->searchable()->wrap(),
            TextColumn::make('handled_at')->label('Status')->formatStateUsing(fn ($state) => $state ? 'Afgehandeld' : 'Nieuw')->placeholder('Nieuw')->badge(),
            TextColumn::make('created_at')->label('Ontvangen')->dateTime('d-m-Y H:i')->sortable(),
        ])->defaultSort('created_at', 'desc')->filters([
            TernaryFilter::make('handled_at')->label('Afgehandeld')->nullable(),
        ])->recordActions([ViewAction::make(), ...static::messageActions()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListContactMessages::route('/'), 'view' => Pages\ViewContactMessage::route('/{record}')];
    }
}
