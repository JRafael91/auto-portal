<?php

namespace App\Filament\Resources\TechnicResource\Pages;

use App\Filament\Resources\TechnicResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateTechnic extends CreateRecord
{
    protected static string $resource = TechnicResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['username']);
        
        return $data;
    }
}
