<?php

namespace App\Services\V1\InventoryLog;

use App\Repositories\V1\InventoryLog\InventoryLogRepository;
class InventoryLogService
{
    public function __construct(protected InventoryLogRepository $inventoryLogRepository)
    {
    }

    public function create($data): void
    {
        $this->inventoryLogRepository->create($data);
    }

    public function getRecent(int $limit = 10)
    {
        return $this->inventoryLogRepository->getRecent($limit);
    }
}