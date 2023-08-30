<?php

namespace App\Services\OrderPayment;

use App\Enums\PaymentProviders;
use App\Models\Order;
use Illuminate\Http\Response;

class OrderPayment
{
    public function pay(Order $order, PaymentProviders $provider)
    {
        $className = 'App\\Services\\OrderPayment\\Providers\\'.\Str::title($provider->value);

        abort_unless(
            class_exists($className),
            Response::HTTP_FORBIDDEN,
            'Provider '.$provider->value.' not implemented'
        );

        app()->make($className)->handle($order);

        $order->update([
            'payed' => true,
            'payment_provider' => $provider->value,
        ]);
    }
}
