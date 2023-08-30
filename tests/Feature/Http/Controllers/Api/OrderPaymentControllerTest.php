<?php

namespace Tests\Http\Controllers\Api;

use App\Enums\PaymentProviders;
use App\Models\Order;
use App\Models\OrderItem;
use Http;
use Tests\TestCase;

class OrderPaymentControllerTest extends TestCase
{
    public function testPay()
    {

        \Http::fake([
            'agentur-loop.com/*' => Http::response(['message' => 'Payment Successful']),
        ]);

        $order = Order::factory()
            ->create();
        $this->actingAs($order->customer);

        OrderItem::factory()->count(3)->create(['order_id' => $order->getKey()]);

        $response = $this->postJson(
            route('api.v1.orders.pay', $order->getKey()),
            [],
            ['Token' => env('API_TOKEN')]
        );

        $this->assertDatabaseHas('orders', [
            'id' => $order->getKey(),
            'payed' => true,
            'payment_provider' => PaymentProviders::SUPERPAY->value,
        ]);
    }
}
