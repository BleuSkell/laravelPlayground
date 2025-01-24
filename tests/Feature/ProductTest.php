<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $products = Product::factory()->count(3)->create();

    $response = $this->get(route('products.index'));

    $response->assertStatus(200);
    $response->assertViewIs('products.index');
    $response->assertViewHas('products', function ($viewProducts) use ($products) {
        return $viewProducts->contains($products->first());
    });
});

test('store', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $productData = Product::factory()->make()->toArray();

    $response = $this->post(route('products.store'), $productData);

    $response->assertStatus(302);
    $response->assertRedirect(route('products.index'));
    $this->assertDatabaseHas('products', [
        'Name' => $productData['Name'],
    ]);
});