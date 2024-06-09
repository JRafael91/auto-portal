<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_orders(): void
    {
        $response = $this->get('/api/orders');

        $response->assertStatus(200);
    }

    public function test_get_order_by_uid(): void
    {
        $response = $this->get('/api/orders/001');

        $response->assertStatus(200);
    }
}
