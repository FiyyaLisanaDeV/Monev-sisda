<?php

namespace App\Filament\Resources\PaketProgres;

use App\Filament\Resources\PaketProgres\Pages\CreatePaketProgres;
use App\Filament\Resources\PaketProgres\Pages\EditPaketProgres;
use App\Filament\Resources\PaketProgres\Pages\ListPaketProgres;
use App\Filament\Resources\PaketProgres\Schemas\PaketProgresForm;
use App\Filament\Resources\PaketProgres\Tables\PaketProgresTable;
use App\Models\PaketProgres;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;

class PaketProgresResource extends Resource
{
    protected static ?string $model = PaketProgres::class;

    public static function getNavigationIcon(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'heroicon-o-table-cells';
    }

    public static function getNavigationLabel(): string
    {
        return 'Master Data Paket';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Data';
    }

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

    protected static ?string $recordTitleAttribute = 'paket';

    public static function form(Schema $schema): Schema
    {
        return PaketProgresForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaketProgresTable::configure($table)
            ->actions([
                ViewAction::make()
                    ->label('Lihat Detail')
                    ->modalHeading('Detail Informasi Paket')
                    ->stickyModalHeader()
                    ->modalWidth('5xl')
                    ->modalContent(fn ($record) => view('filament.partials.paket-detail-modal', [
                        'record' => $record,
                    ])),
            ])
            ->recordAction(ViewAction::class);
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
            'index' => ListPaketProgres::route('/'),
            'create' => CreatePaketProgres::route('/create'),
            'edit' => EditPaketProgres::route('/{record}/edit'),
        ];
    }
}
