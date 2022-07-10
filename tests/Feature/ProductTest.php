<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test get all products
     * 
     */
    public function test_get_products() {
        $response = $this->json('GET', '/api/v1/product');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                    'description',
                    'slug',
                    'weight',
                    'category' => [
                        'id',
                        'name',
                        'parent_id',
                    ],
                ],
            ]
        ]);
    }
}
