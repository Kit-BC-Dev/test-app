<?php

namespace App\Http\Controllers\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Product\ProductResource;
use App\Services\V1\Product\ProductService;
use Illuminate\Http\Request;
use App\Http\Requests\V1\Product\ProductRequest;
use App\Http\Requests\V1\Product\UpdateProductRequest;


class ProductController extends Controller
{
    public function __construct(protected ProductService $productService){}

    public function index()
    {
        return response()->json([
            'data' => ProductResource::collection($this->productService->index()),
        ], 200);
    }

    public function store(ProductRequest $request)
    {

        return response()->json([
            'data' => new ProductResource($this->productService->create($request->all())),
        ], 201);
    }

    public function show(int $productId)
    {
        return response()->json([
            'data' => new ProductResource($this->productService->show($productId)),
        ], 200);
    }

    public function update(UpdateProductRequest $request, int $productId)
    {
        return response()->json([
            'data' => new ProductResource($this->productService->update($request->all(), $productId)),
        ], 200);
    }

    public function destroy(int $productId)
    {
        $this->productService->delete($productId);
        return response()->json([
            'message' => 'Product deleted successfully',
        ], 204);
    }
    public function getProductByUser(int $userId)
    {
        return response()->json([
            'data' => ProductResource::collection($this->productService->getUserProducts($userId)),
        ], 200);
    }
}
