<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemsStoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Response;

class OrderItemsController extends Controller
{
    public function store(Order $order, OrderItemsStoreRequest $request)
    {
        abort_if($order->payed, Response::HTTP_FORBIDDEN, 'Order already payed');

        $order->orderItems()->create([
            'product_id' => $request->get('product_id'),
        ]);

        return new OrderResource($order);
    }
}
