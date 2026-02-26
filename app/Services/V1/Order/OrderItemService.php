<?php

namespace App\Services\V1\Order;

use App\Repositories\V1\Order\OrderItemRepository;
use App\Services\BaseService;

class OrderItemService extends BaseService
{
    public function __construct(OrderItemRepository $orderItemRepository)
    {
        parent::__construct($orderItemRepository);
    }
}

