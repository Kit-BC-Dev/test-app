<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\V1\InventoryLog\InventoryLogService;

class ProductObserver
{
    public function __construct(protected InventoryLogService $inventoryLogService)
    {
    }
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
       $this->inventoryLogService->create([
        'event' => 'product.create',
        'model_type' => Product::class,
        'model_id' => $product->id,
        'user_id' => $product->user_id,
        'before' => null,
        'after' => $product->toArray()
       ]);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $before = $product->getOriginal();
        $after = $product->getDirty();
        $this->inventoryLogService->create([
            'event' => 'product.update',
            'model_type' => Product::class,
            'model_id' => $product->id,
            'user_id' => $product->user_id,
            'before' => $before,
            'after' => $after
        ]);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
