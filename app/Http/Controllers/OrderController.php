<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;

use App\Models\Order;

class OrderController extends Controller
{
    /**
     * returns orders of user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userOrders = Order::where('user_id', auth()->id())->get();
        return new OrderCollection($userOrders);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if($order->userID == auth()->id()){
            return new OrderResource($order);
        }else{
           return response()->json([
               'message' => 'The order you\'re trying to view doesn\'t seem to be yours, hmmmm.',
           ], 403);
        }
    }
}
