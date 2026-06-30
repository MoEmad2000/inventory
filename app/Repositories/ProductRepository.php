<?php

namespace App\Repositories;

use App\Models\Product;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function all(int $perPage = 10): LengthAwarePaginator
    {
        return Product::paginate($perPage);
    }

    public function find(string $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function adjustStock(Product $product, string $type, int $quantity): Product
    {
        if ($type === 'increment') {
            $product->increment('stock_quantity', $quantity);
        } else {
            if ($product->stock_quantity < $quantity) {
                throw new Exception('Insufficient stock');
            }

            $product->decrement('stock_quantity', $quantity);
        }
        return $product->fresh();
    }

    public function lowStock()
    {
        return Product::whereColumn(
            'stock_quantity',
            '<=',
            'low_stock_threshold'
        )->get();
    }
}
