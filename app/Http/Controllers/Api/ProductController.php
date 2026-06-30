<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Traits\ApiResponse;

class ProductController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected ProductRepository $repository
    ) {}

    public function index()
    {
        $products = $this->repository->all();

        return $this->success(
            ProductResource::collection($products)
        );
    }
    public function show(string $id)
    {
        
         return $this->success(
            new ProductResource($this->repository->find($id)),
            'Product retrieved successfully'
        );
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->repository->create(
            $request->validated()
        );

        return $this->success(
            new ProductResource($product),
            'Product created successfully',
            201
        );
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        $product = $this->repository->find($id);

        $product = $this->repository->update(
            $product,
            $request->validated()
        );

        return $this->success(
            new ProductResource($product),
            'Product updated successfully'
        );
    }

    public function destroy(string $id)
    {
        $product = $this->repository->find($id);

        $this->repository->delete($product);

        return $this->success(
            null,
            'Product deleted successfully'
        );
    }

    public function adjustStock(UpdateStockRequest $request, Product $product)
    {
        $product = $this->repository->adjustStock(
            $product,
            $request->type,
            $request->quantity
        );

        return $this->success(
            new ProductResource($product),
            'Stock adjusted successfully'
        );
    }
    
    public function lowStock()
    {
        return $this->success(
            $this->repository->lowStock(),
            'Low stock products retrieved successfully'
        );
    }
}
