<?php

namespace Tests\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use Tests\TestCase;

class OrderItemsControllerTest extends TestCase
{

    public function testStore()
    {
        $order = Order::factory()->create();
        $this->actingAs($order->customer);

        $response = $this->postJson(
            route('api.v1.orders.add', $order->getKey()),
            ['product_id' => Product::factory()->create()->getKey()],
            ['Token' => env('API_TOKEN')]
        );

        $response->assertJsonStructure([
            'data' => [
                'payed',
                'payment_method',
                'customer' => [
                    'first_name',
                    'last_name',
                ],
                'products' => [
                    '*' => [
                        'id',
                        'productname',
                        'price'
                    ],
                ],
            ],
        ]);

        $response->assertOk();
    }

}
