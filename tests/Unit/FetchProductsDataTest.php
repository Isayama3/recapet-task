<?php

namespace Tests\Unit;

use App\Services\Product\FetchProductsData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchProductsDataTest extends TestCase
{
    Use RefreshDatabase;
    public function test_fetch_products_and_save_to_database_with_specific_product()
    {
        // Mock the HTTP response to include the specific product
        Http::fake([
            'https://dummyjson.com/products' => Http::sequence()
                ->push(['products' => [
                    [
                        'id' => 1,
                        'title' => 'Essence Mascara Lash Princess',
                        'description' => 'The Essence Mascara Lash Princess is a popular mascara known for its volumizing and lengthening effects. Achieve dramatic lashes with this long-lasting and cruelty-free formula.',
                        'category' => 'beauty',
                        'price' => 9.99,
                        'discountPercentage' => 7.17,
                        'rating' => 4.94,
                        'stock' => 5,
                        'brand' => 'Essence',
                        'sku' => 'RCH45Q1A',
                        'weight' => 2,
                        'dimensions' => ['width' => 23.17, 'height' => 14.43, 'depth' => 28.01],
                        'warrantyInformation' => '1 month warranty',
                        'shippingInformation' => 'Ships in 1 month',
                        'availabilityStatus' => 'Low Stock',
                        'returnPolicy' => '30 days return policy',
                        'minimumOrderQuantity' => 24,
                        'images' => ['https://cdn.dummyjson.com/products/images/beauty/Essence%20Mascara%20Lash%20Princess/1.png'],
                        'tags' => ['mascara'],
                        'reviews' => [
                            [
                                'rating' => 2,
                                'comment' => 'Very unhappy with my purchase!',
                                'reviewerName' => 'John Doe',
                                'reviewerEmail' => 'john.doe@x.dummyjson.com',
                                'date' => '2024-05-23T08:56:21.618Z',
                            ],
                            [
                                'rating' => 2,
                                'comment' => 'Not as described!',
                                'reviewerName' => 'Nolan Gonzalez',
                                'reviewerEmail' => 'nolan.gonzalez@x.dummyjson.com',
                                'date' => '2024-05-23T08:56:21.618Z',
                            ],
                            [
                                'rating' => 5,
                                'comment' => 'Very satisfied!',
                                'reviewerName' => 'Scarlett Wright',
                                'reviewerEmail' => 'scarlett.wright@x.dummyjson.com',
                                'date' => '2024-05-23T08:56:21.618Z',
                            ],
                        ],
                        'meta' => [
                            'barcode' => '9164035109868',
                            'qrCode' => 'https://assets.dummyjson.com/public/qr-code.png',
                            'createdAt' => '2024-05-23T08:56:21.618Z',
                            'updatedAt' => '2024-05-23T08:56:21.618Z',
                        ],
                        'thumbnail' => 'https://cdn.dummyjson.com/products/images/beauty/Essence%20Mascara%20Lash%20Princess/thumbnail.png',
                    ],
                ]])
        ]);

        $response = FetchProductsData::fetchProductsAndSaveToDatabase();

        $this->assertEquals(200, $response->status());
        $this->assertDatabaseHas('products', [
            'id' => 1,
            'title' => 'Essence Mascara Lash Princess',
            'description' => 'The Essence Mascara Lash Princess is a popular mascara known for its volumizing and lengthening effects. Achieve dramatic lashes with this long-lasting and cruelty-free formula.',
            'price' => 9.99,
            'stock' => 5,
            'brand' => 'Essence',
            'sku' => 'RCH45Q1A',
        ]);

        // Check if the related records are saved
        $this->assertDatabaseHas('product_images', [
            'product_id' => 1,
            'image' => 'https://cdn.dummyjson.com/products/images/beauty/Essence%20Mascara%20Lash%20Princess/1.png',
        ]);

        $this->assertDatabaseHas('product_tags', [
            'product_id' => 1,
            'tag' => 'mascara',
        ]);

        $this->assertDatabaseHas('product_reviews', [
            'product_id' => 1,
            'rating' => 5,
            'comment' => 'Very satisfied!',
        ]);

        $this->assertDatabaseHas('product_dimensions', [
            'product_id' => 1,
            'width' => 23.17,
            'height' => 14.43,
            'depth' => 28.01,
            'weight' => 2,
        ]);

        $this->assertDatabaseHas('product_metas', [
            'product_id' => 1,
            'barcode' => '9164035109868',
            'qrCode' => 'https://assets.dummyjson.com/public/qr-code.png',
        ]);

    }
}
