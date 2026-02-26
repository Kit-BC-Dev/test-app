<?php

namespace App\Services\V1\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Pipelines\V1\Order\AttachOrderItems;
use App\Pipelines\V1\Order\CancelOrderItems;
use App\Pipelines\V1\Order\CalculateTotal;
use App\Pipelines\V1\Order\CreateOrder;
use App\Pipelines\V1\Order\DeductInventory;
use App\Pipelines\V1\Order\MarkOrderConfirmed;
use App\Pipelines\V1\Order\RecalculateTotal;
use App\Pipelines\V1\Order\RestoreInventory;
use App\Pipelines\V1\Order\ValidateStock;
use App\Repositories\V1\Order\OrderRepository;
use App\Services\BaseService;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class OrderService extends BaseService
{
    public function __construct(
        OrderRepository $orderRepository,
        protected Pipeline $pipeline,
    ) {
        parent::__construct($orderRepository);
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $result = $this->pipeline->send($data)
                ->through([
                    CreateOrder::class,
                    AttachOrderItems::class,
                ])
                ->thenReturn();

            return $result['order'];
        });
    }

    public function confirmOrder(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            $result = $this->pipeline->send(['order' => $order])
                ->through([
                    ValidateStock::class,
                    DeductInventory::class,
                    CalculateTotal::class,
                    MarkOrderConfirmed::class,
                ])
                ->thenReturn();

            return $result['order'];
        });
    }

    public function cancelOrder(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            $result = $this->pipeline->send([
                'order' => $order,
                'items' => $order->activeItems,
                'full'  => true,
            ])
                ->through([
                    RestoreInventory::class,
                    CancelOrderItems::class,
                    RecalculateTotal::class,
                ])
                ->thenReturn();

            return $result['order'];
        });
    }

    public function getByUser(int $userId)
    {
        return $this->repository->getByUser($userId);
    }

    public function count(): int
    {
        return $this->repository->index()->count();
    }

    public function countByStatus(string $status): int
    {
        return $this->repository->countByStatus($status);
    }

    public function getTotalRevenue(): float
    {
        return $this->repository->getTotalRevenue();
    }

    public function cancelOrderItem(Order $order, OrderItem $item): Order
    {
        return DB::transaction(function () use ($order, $item) {
            $result = $this->pipeline->send([
                'order' => $order,
                'items' => collect([$item]),
                'full'  => false,
            ])
                ->through([
                    RestoreInventory::class,
                    CancelOrderItems::class,
                    RecalculateTotal::class,
                ])
                ->thenReturn();

            return $result['order'];
        });
    }
}

