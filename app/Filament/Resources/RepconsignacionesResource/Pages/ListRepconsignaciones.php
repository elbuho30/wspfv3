<?php

namespace App\Filament\Resources\RepconsignacionesResource\Pages;

use App\Filament\Resources\RepconsignacionesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRepconsignaciones extends ListRecords
{
    protected static string $resource = RepconsignacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
