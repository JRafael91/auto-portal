<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function list()
    {
        return Order::query()->get();
    }

    public function find(string $uid)
    {
        $order = Order::query()->where('uid', 'LIKE', '%'.$uid.'%')->first();
        if (!$order) {
            throw new Exception(['message' => 'No se encontraron resultados'], 404);
        }
        return $order;
    }
}