<?php

namespace App\Http\Controllers\Api;

use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemsStoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class OrderItemsController extends Controller
{
    public function store(Order $order, OrderItemsStoreRequest $request)
    {
        $order->orderItems()->create([
            'product_id' => $request->get('product_id')
        ]);

        return new OrderResource($order);
    }
}
