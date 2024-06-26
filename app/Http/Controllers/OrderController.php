<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use App\Services\OrderService;

class OrderController extends Controller
{

    public function __construct(protected OrderService $orderService) {}
    
    public function index(): JsonResponse
    {
        try {
            $orders = $this->orderService->list();

            return response()->json($orders);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function show(string $uid): JsonResponse
    {
        try {
            $order = $this->orderService->find($uid);

            return response()->json($order);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
