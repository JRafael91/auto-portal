<?php

namespace App\Filament\Resources\TechnicResource\Pages;

use App\Filament\Resources\TechnicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechnic extends EditRecord
{
    protected static string $resource = TechnicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
