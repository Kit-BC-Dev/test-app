<?php

namespace App\Repositories\V1\Order;

use App\Models\OrderItem;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderItemRepository extends BaseRepository
{
    public function __construct(OrderItem $orderItem)
    {
        parent::__construct($orderItem);
    }

    public function getActiveByOrder(int $orderId): Collection
    {
        return $this->model->where('order_id', $orderId)->where('status', 'active')->get();
    }
}

