<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Tests\TestCase;

class OrdersControllerTest extends TestCase
{
    public function testIndex()
    {
        Order::factory()
            ->has(OrderItem::factory()->count(2))
            ->for(Customer::factory()->create())
            ->create();

        $response = $this->getJson(
            route('api.v1.orders.index'),
            ['Token' => env('API_TOKEN')]
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'payed',
                    'payment_method',
                    'customer' => [
                        'first_name',
                        'last_name',
                    ],
                ],
            ],
        ]);
    }

    public function testStore()
    {
        $this->actingAs(Customer::factory()->create());

        $response = $this->postJson(
            route('api.v1.orders.store'),
            [],
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
            ],
        ]);

        $response->assertCreated();
    }

    public function testDelete()
    {

        $order = Order::factory()->create();

        $this->actingAs($order->customer);

        $response = $this->deleteJson(
            route('api.v1.orders.destroy', $order->getKey()),
            [],
            ['Token' => env('API_TOKEN')]
        );

        $response->assertJsonStructure([
            'meta' => [
                'message',
            ],
        ]);

        $response->assertOk();
    }
}
