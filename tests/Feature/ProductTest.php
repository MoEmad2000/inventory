<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a product', function () {
    $payload = [
        'sku' => 'SKU-001',
        'name' => 'Laptop',
        'description' => 'Gaming Laptop',
        'price' => 25000,
        'stock_quantity' => 10,
        'low_stock_threshold' => 2,
        'status' => 'active',
    ];

    $this->postJson('/api/products', $payload)
        ->assertCreated()
        ->assertJsonFragment([
            'name' => 'Laptop',
            'sku' => 'SKU-001',
        ]);

    $this->assertDatabaseHas('products', [
        'sku' => 'SKU-001',
    ]);
});

it('can list all products', function () {
    Product::factory()->count(3)->create();

    $this->getJson('/api/products')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can show a product', function () {
    $product = Product::factory()->create();

    $this->getJson("/api/products/{$product->id}")
        ->assertOk()
        ->assertJsonFragment([
            'id' => $product->id,
            'sku' => $product->sku,
        ]);
});

it('can update a product', function () {
    $product = Product::factory()->create();

    $payload = [
        'name' => 'Updated Product',
        'price' => 999,
    ];

    $product = Product::factory()->create();

    $this->putJson("/api/products/{$product->id}", [
        'sku' => 'SKU-002',
        'name' => 'Updated Product',
        'description' => 'Updated Description',
        'price' => 999,
        'stock_quantity' => 20,
        'low_stock_threshold' => 5,
        'status' => 'active',
    ])->assertOk();

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Product',
        'price' => 999,
    ]);
});

it('can delete a product', function () {
    $product = Product::factory()->create();

    $this->deleteJson("/api/products/{$product->id}")
        ->assertOk();

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
        'deleted_at' => null,
    ]);
});
