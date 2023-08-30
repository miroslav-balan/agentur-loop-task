<?php

namespace App\Http\Controllers\Api;

use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class OrdersController extends Controller
{
    public function index(Pipeline $pipeline)
    {
        $data = $pipeline
            ->send(Order::query()->latest())
            ->thenReturn()
            ->paginate(30);

        return OrderResource::collection($data);
    }

    public function store(Request $request)
    {
        $order = Order::query()->create([
            'customer_id' => \uth::user()->id,
            'payed' => false,
        ]);

        return new OrderResource($order);
    }

    public function destroy(Order $order, ApiResponse $apiResponse)
    {
        if ($order->customer_id !== Auth::user()->id) {
            throw new \RuntimeException('Access Denied');
        }
        $order->delete();

        $apiResponse
            ->setMessage('Order deleted Successfully');

        return $apiResponse->getResponse();
    }
}
