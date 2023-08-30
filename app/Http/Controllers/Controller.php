<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // Necessary to inject for a testing app
        if (! app()->runningUnitTests()) {
            $customer = Customer::first() ?? Customer::factory()->create();
            \Auth::login($customer);
        }

    }
}
