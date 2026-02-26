<?php

namespace  App\Repositories\V1\InventoryLog;

use App\Models\InventoryLog;
class InventoryLogRepository
{
    public function __construct(protected InventoryLog $model){}

    public function create(array $data): void
    {
        $this->model->create($data);
    }
}