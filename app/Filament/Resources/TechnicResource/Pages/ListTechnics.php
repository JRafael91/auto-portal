<?php

namespace App\Filament\Resources\TechnicResource\Pages;

use App\Filament\Resources\TechnicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTechnics extends ListRecords
{
    protected static string $resource = TechnicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
