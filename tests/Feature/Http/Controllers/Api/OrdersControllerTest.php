<?php
namespace Tests\Feature\Http\Controllers\Api;
use App\Models\Order;
use App\Models\OrderItem;
use Tests\TestCase;

class OrdersControllerTest extends TestCase
{

    public function testIndex()
    {
        $order = Order::factory()
            ->has(OrderItem::factory()->count(2))
            ->create();
    }
}
