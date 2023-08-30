<?php

namespace App\Services\OrderPayment\Providers;

use App\Models\Order;
use Illuminate\Http\Response;

class Superpay
{
    public function handle(Order $order)
    {
        $response = \Http::post('https://superpay.view.agentur-loop.com/pay', [
            'order_id' => $order->id,
            'customer_email' => $order->customer->email,
            'value' => $order->orderItems->sum('product.price'),
        ]);

        if ($response->json('message') !== 'Payment Successful') {
            \Log::critical(
                'Superpay:handle payment failed with message:'.$response->json('message')
            );
            abort(
                Response::HTTP_BAD_REQUEST,
                'Payment Failed'
            );
        }

    }
}
