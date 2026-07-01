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
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected ProductRepository $repository
    ) {}
    public function index()
    {
        $page = request()->integer('page', 1);

        $cacheKey = "products:page:{$page}";

        $response = Cache::remember($cacheKey, 600, function () use ($page) {

            $products = $this->repository->all();

            return [
                'data' => ProductResource::collection($products)->resolve(),
                'meta' => [
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                    ],
                ],
            ];
        });

        return $this->success(
            $response['data'],
            meta: $response['meta'],
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

        Product::clearProductsCache();

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
        Product::clearProductsCache();
        return $this->success(
            new ProductResource($product),
            'Product updated successfully'
        );
    }

    public function destroy(string $id)
    {
        $product = $this->repository->find($id);

        $this->repository->delete($product);
        Product::clearProductsCache();
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
        Product::clearProductsCache();
        return $this->success(
            new ProductResource($product),
            'Stock adjusted successfully'
        );
    }

    public function lowStock()
    {
        return $this->success(
            ProductResource::collection(
                $this->repository->lowStock()
            ),
            'Low stock products retrieved successfully'
        );
    }
}
