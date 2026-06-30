<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepository $repository
    ) {}

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->repository->all()
        ]);
    }

    public function show(string $id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->repository->find($id)
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->repository->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $product
        ], 201);
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        $product = $this->repository->find($id);

        $product = $this->repository->update(
            $product,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function destroy(string $id)
    {
        $product = $this->repository->find($id);

        $this->repository->delete($product);

        return response()->json([
            'success' => true
        ]);
    }
}
