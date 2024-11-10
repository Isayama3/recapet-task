<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductBladeViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_view_displays_correctly()
    {
        // Arrange: Create a product instance
        $product = Product::factory()
            ->hasImages(2)
            ->hasReviews(3)
            ->hasTags(5)
            ->hasDimensions(1)
            ->hasMeta(1)
            ->create();

        // Act: Visit the product page
        $response = $this->get(route('products.index', $product->id)); // Adjust the route accordingly

        // Assert: Check the response status and view content
        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index'); // Ensure the correct view is returned
        $response->assertSee($product->title); // Check if the title is present
        $response->assertSee($product->description); // Check if the description is present
        $response->assertSee($product->category); // Check if the category is present
        $response->assertSee($product->price); // Check if the price is present
        $response->assertSee($product->stock); // Check if the stock is present
        $response->assertSee($product->brand); // Check if the brand is present
        $response->assertSee($product->sku); // Check if the SKU is present
        $response->assertSee($product->thumbnail); // Check if the thumbnail URL is present
    }
}
