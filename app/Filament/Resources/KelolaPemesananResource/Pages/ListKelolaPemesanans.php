<?php

namespace App\Filament\Resources\KelolaPemesananResource\Pages;

use App\Filament\Resources\KelolaPemesananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKelolaPemesanans extends ListRecords
{
    protected static string $resource = KelolaPemesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
