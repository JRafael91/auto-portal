<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;


class OrderService
{
    public function list(): Collection
    {
        return Order::query()->get();
    }

    public function listByTechnic(int $id): Collection
    {
        return Order::query()->where('technic_id', $id)
            ->where('status', '!=', 'FINALIZADO')
            ->where('status', '!=', 'CANCELADO')
            ->get();
    }

    public function find(string $uid): object
    {
        $order = Order::query()->with(['items.product'])
            ->where('uid', 'LIKE', '%'.$uid.'%')->first();
        if (!$order) {
            throw new Exception('No se encontraron resultados', 404);
        }
        return $order;
    }

    public function updateStatus(Order $order, string $status): void
    {
        $order->status = $status;
        $order->save();
    }

    public function uploadImage(Order $order, UploadedImage $image): void
    {
        $order->image = $image->store('orders', 'public');
        $order->save();
    }
}