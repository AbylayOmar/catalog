<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Test login auth
     *
     * @return void
     */
    public function test_register()
    {
        $formData = [
            'name' => 'John Doe',
            'email' => 'admin@mail.com',
            'password' => 'aaaaAA11',
        ];

        $response = $this->json('POST', '/api/v1/register', $formData);
        
        $response->assertStatus(422);
    }

    /**
     * Test login auth
     *
     * @return void
     */
    public function test_login() {
        $formData = [
            'email' => 'admin@mail.com',
            'password' => 'aaaaAA11',
        ];

        $response = $this->json('POST', '/api/v1/login', $formData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
    }

    /**
     * Test incorrect credentials login auth
     *
     * @return void
     */
    public function test_incorrect_credentials_login() {
        $formData = [
            'email' => 'admin@mail.com',
            'password' => 'aaaaAA11_incorrect',
        ];

        $response = $this->json('POST', '/api/v1/login', $formData);

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message',
        ]);
    }
}
