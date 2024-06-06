<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use App\Models\Order;

class OrderController extends Controller
{
    
    public function index(): JsonResponse
    {
        try {
            $orders = Order::query()->get();
            return response()->json($orders);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function show(string $uid): JsonResponse
    {
        try {
            $order = Order::query()->where('uid', 'LIKE', '%'.$uid.'%')->first();
            if (!$order) {
                throw new Exception(['message' => 'No se encontraron resultados'], 404);
            }
            return response()->json($order);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
