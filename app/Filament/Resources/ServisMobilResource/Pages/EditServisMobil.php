<?php

namespace App\Filament\Resources\ServisMobilResource\Pages;

use App\Filament\Resources\ServisMobilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServisMobil extends EditRecord
{
    protected static string $resource = ServisMobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
