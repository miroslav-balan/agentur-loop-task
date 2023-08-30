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
            route('api.v1.orders'),
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
                        'last_name'
                    ]
                ],
            ],
        ]);
    }
}
