<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
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
        //
    }


    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
