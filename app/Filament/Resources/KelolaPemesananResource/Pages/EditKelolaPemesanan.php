<?php

namespace App\Filament\Resources\KelolaPemesananResource\Pages;

use App\Filament\Resources\KelolaPemesananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKelolaPemesanan extends EditRecord
{
    protected static string $resource = KelolaPemesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
