<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use App\Enums\OrderStatus;
use Filament\Actions\Action;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos'),
            'Generado' => Tab::make()->query(fn ($query) => $query->where('status', OrderStatus::Generated)),
            'Proceso' => Tab::make()->query(fn ($query) => $query->where('status', OrderStatus::Processing)),
            'Finalizado' => Tab::make()->query(fn ($query) => $query->where('status', OrderStatus::Finished)),
            'Cancelado' => Tab::make()->query(fn ($query) => $query->where('status', OrderStatus::Cancelled)),
        ];
    }
}
