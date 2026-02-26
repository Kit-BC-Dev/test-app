<?php

namespace App\Services\V1\Product;

use App\Repositories\V1\Product\ProductRepository;
use App\Services\BaseService;

class ProductService extends BaseService
{
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct($productRepository);
    }

    public function getUserProducts(int $userId)
    {
        return $this->repository->getUserProducts($userId);
    }

    public function decrementQuantity(int $productId, int $quantity): void
    {
        $this->repository->decrementQuantity($productId, $quantity);
    }

    public function incrementQuantity(int $productId, int $quantity): void
    {
        $this->repository->incrementQuantity($productId, $quantity);
    }

    public function count(): int
    {
        return $this->repository->index()->count();
    }

    public function countLowStock(int $threshold = 10): int
    {
        return $this->repository->countLowStock($threshold);
    }

    public function countOutOfStock(): int
    {
        return $this->repository->countOutOfStock();
    }
}