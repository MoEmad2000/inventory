<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
        ];
    }
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock_quantity',
        'low_stock_threshold',
        'status',
    ];

    public static function clearProductsCache()
    {
        foreach (range(1, 100) as $page) {
            Cache::forget("products_page_10_{$page}");
        }
    }
}
