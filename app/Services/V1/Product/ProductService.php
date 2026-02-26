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
}