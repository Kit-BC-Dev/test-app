<?php

namespace App\Repositories\V1\Product;

use App\Repositories\BaseRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;


class ProductRepository extends BaseRepository
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function getUserProducts($userId): Collection
    {
        return $this->model->whereUserId($userId)->get();
    }

    public function decrementQuantity(int $productId, int $quantity): void
    {
        $product = $this->model->findOrFail($productId);
        $product->update(['quantity' => $product->quantity - $quantity]);
    }

    public function incrementQuantity(int $productId, int $quantity): void
    {
        $product = $this->model->findOrFail($productId);
        $product->update(['quantity' => $product->quantity + $quantity]);
    }

    public function countLowStock(int $threshold = 10): int
    {
        return $this->model->where('quantity', '>', 0)->where('quantity', '<', $threshold)->count();
    }

    public function countOutOfStock(): int
    {
        return $this->model->where('quantity', 0)->count();
    }
}
