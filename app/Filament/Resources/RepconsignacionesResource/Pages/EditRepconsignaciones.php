<?php

namespace App\Filament\Resources\RepconsignacionesResource\Pages;

use App\Filament\Resources\RepconsignacionesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepconsignaciones extends EditRecord
{
    protected static string $resource = RepconsignacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
