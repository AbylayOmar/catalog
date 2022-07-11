<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
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
     * Test cart create.
     */
    public function test_cart_create() {
        $response = $this->post('/api/v1/cart');

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "Message",
            "cartToken",
            "cartKey"
        ]);
    }
}
