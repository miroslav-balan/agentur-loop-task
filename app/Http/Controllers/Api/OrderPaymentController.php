<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentProviders;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderPayment\OrderPayment;

class OrderPaymentController extends Controller
{
    public function __invoke(Order $order, OrderPayment $orderPayment)
    {
        $orderPayment->pay($order, PaymentProviders::SUPERPAY);

        return new OrderResource($order);
    }
}
