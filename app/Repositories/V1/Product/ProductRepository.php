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
}
