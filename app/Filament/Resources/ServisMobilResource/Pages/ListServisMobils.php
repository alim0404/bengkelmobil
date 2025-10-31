<?php

namespace App\Filament\Resources\ServisMobilResource\Pages;

use App\Filament\Resources\ServisMobilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServisMobils extends ListRecords
{
    protected static string $resource = ServisMobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
