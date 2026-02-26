<?php

namespace App\Repositories\V1\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->with('items.product')->get();
    }

    public function countByStatus(string $status): int
    {
        return $this->model->where('status', $status)->count();
    }

    public function getTotalRevenue(): float
    {
        return $this->model->where('status', 'confirmed')->sum('total');
    }
}

