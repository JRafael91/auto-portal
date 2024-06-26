<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Database\Eloquent\Collection;


class OrderService
{
    public function list(): Collection
    {
        return Order::query()->get();
    }

    public function find(string $uid): object
    {
        $order = Order::query()->where('uid', 'LIKE', '%'.$uid.'%')->first();
        if (!$order) {
            throw new Exception('No se encontraron resultados', 404);
        }
        return $order;
    }
}