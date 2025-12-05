<?php

namespace Tests\Feature;

use App\Enums\StockProcess;
use App\Models\Product;
use App\Models\Productvariant;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createProduct(array $overrides = []): Product
    {
        return Product::create(array_replace([
            'category_id' => 1,
            'subcategory_id' => 1,
            'brand_id' => 1,
            'name' => 'Test Product',
            'name_ar' => 'اختبار',
            'description' => null,
            'description_ar' => null,
            'more_info' => null,
            'more_info_ar' => null,
            'imageurl' => null,
            'imageurl2' => null,
            'imageurl3' => null,
            'price' => 10,
            'price_offer' => 8,
            'percentage_discount' => 20,
            'is_newarrival' => 0,
            'is_trending' => 0,
            'is_recommended' => 0,
            'is_topsearch' => 0,
            'searchtag_id' => null,
            'search_count' => 0,
            'created_by' => 1,
            'updated_by' => 1,
            'accept_status' => 1,
            'delete_status' => 0,
            'min_age' => null,
            'max_age' => null,
            'colors' => null,
            'size' => null,
        ], $overrides));
    }

    protected function createVariant(Product $product, array $overrides = []): Productvariant
    {
        return Productvariant::create(array_replace([
            'product_id' => $product->id,
            'size_id' => null,
            'color_id' => null,
            'price' => 10,
            'available_quantity' => 0,
            'imageurl' => null,
            'imageurl2' => null,
            'imageurl3' => null,
            'created_by' => 1,
            'updated_by' => 1,
            'delete_status' => 0,
        ], $overrides));
    }

    public function test_store_adds_stock_and_updates_variant_quantity(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $variant = $this->createVariant($product);

        $response = $this->actingAs($user)->post(route('stock.store'), [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 5,
        ]);

        $response->assertRedirect(route('stock.variant', [$variant->id, $product->id]));

        $this->assertDatabaseHas('stocks', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 5,
            'process' => StockProcess::ADD,
        ]);

        $this->assertEquals(5, $variant->fresh()->available_quantity);
    }

    public function test_store_rejects_variant_not_linked_to_product(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $otherProduct = $this->createProduct(['name' => 'Other']);
        $foreignVariant = $this->createVariant($otherProduct);

        $response = $this->actingAs($user)
            ->from(route('stock.variant', [$foreignVariant->id, $product->id]))
            ->post(route('stock.store'), [
                'product_id' => $product->id,
                'variant_id' => $foreignVariant->id,
                'quantity' => 2,
            ]);

        $response->assertRedirect(route('stock.variant', [$foreignVariant->id, $product->id]));
        $response->assertSessionHas('error', 'Variant does not belong to the selected product.');
        $this->assertDatabaseCount('stocks', 0);
    }

    public function test_update_changes_pending_entry_and_variant_quantity(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $variant = $this->createVariant($product, ['available_quantity' => 3]);

        $stock = Stock::create([
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 3,
            'process' => StockProcess::ADD,
            'status' => 0,
            'cart_id' => null,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->from(route('stock.variant', [$variant->id, $product->id]))
            ->post(route('stock.update'), [
                'editid' => $stock->id,
                'quantity' => 10,
            ]);

        $response->assertRedirect(route('stock.variant', [$variant->id, $product->id]));
        $response->assertSessionHas('success', 'Stock entry updated.');

        $this->assertEquals(10, $stock->fresh()->quantity);
        $this->assertEquals(10, $variant->fresh()->available_quantity);
    }

    public function test_update_rejects_approved_entries(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $variant = $this->createVariant($product, ['available_quantity' => 5]);

        $stock = Stock::create([
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 5,
            'process' => StockProcess::ADD,
            'status' => 1,
            'cart_id' => null,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->from(route('stock.variant', [$variant->id, $product->id]))
            ->post(route('stock.update'), [
                'editid' => $stock->id,
                'quantity' => 8,
            ]);

        $response->assertRedirect(route('stock.variant', [$variant->id, $product->id]));
        $response->assertSessionHas('error', 'Approved stock entries cannot be modified.');
        $this->assertEquals(5, $stock->fresh()->quantity);
    }
}

