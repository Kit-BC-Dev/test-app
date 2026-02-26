<?php

namespace App\Http\Controllers\V1\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Order\OrderRequest;
use App\Http\Resources\V1\Order\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\V1\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class OrderController extends Controller
{
    use AuthorizesRequests;
    public function __construct(protected OrderService $orderService) {}

    public function index(): JsonResponse
    {
        $orders = $this->orderService->getByUser(auth()->id());

        return response()->json([
            'data' => OrderResource::collection($orders),
        ], 200);
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder($request->validated());

        return response()->json([
            'data' => new OrderResource($order->load('items.product')),
        ], 201);
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);

        return response()->json([
            'data' => new OrderResource($order->load('items.product')),
        ], 200);
    }

    public function confirm(Order $order): JsonResponse
    {
        $this->authorize('confirm', $order);

        $order = $this->orderService->confirmOrder($order);

        return response()->json([
            'data' => new OrderResource($order->load('items.product')),
        ], 200);
    }

    public function cancel(Order $order): JsonResponse
    {
        $this->authorize('cancel', $order);

        $order = $this->orderService->cancelOrder($order);

        return response()->json([
            'data' => new OrderResource($order->load('items.product')),
        ], 200);
    }

    public function cancelItem(Order $order, OrderItem $item): JsonResponse
    {
        $this->authorize('cancelItem', $order);

        $order = $this->orderService->cancelOrderItem($order, $item);

        return response()->json([
            'data' => new OrderResource($order->load('items.product')),
        ], 200);
    }
}

