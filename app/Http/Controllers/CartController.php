<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Requests\CartShowRequest;
use App\Http\Requests\CartAddProductRequest;
use App\Http\Requests\CartCheckoutRequest;

use App\Http\Resources\CartItemCollection;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;

class CartController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::guard('sanctum')->check()) {
            $user_id = Auth::guard('sanctum')->user()->id;
        }

        $cart = Cart::create([
            'id' => md5(uniqid(rand(), true)),
            'key' => md5(uniqid(rand(), true)),
            'user_id' => isset($user_id) ? $user_id : null,

        ]);
        return response()->json([
            'Message' => 'A new cart have been created for you!',
            'cartToken' => $cart->id,
            'cartKey' => $cart->key,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart, CartShowRequest $request)
    {

        $cartKey = $request->input('cartKey');
        if ($cart->key == $cartKey) {
            return response()->json([
                'cart' => $cart->id,
                'items in Cart' => new CartItemCollection($cart->items),
            ], 200);

        } else {

            return response()->json([
                'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
            ], 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart, CartShowRequest $request)
    {
        $cartKey = $request->input('cartKey');
        if ($cart->key == $cartKey) {
            $cart->delete();
            return response()->json(null, 204);
        } else {

            return response()->json([
                'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
            ], 400);
        }

    }

    /**
     * Adds Products to the given Cart;
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return void
     */
    public function addProducts(Cart $cart, CartAddProductRequest $request)
    {
        $cartKey = $request->input('cartKey');
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');

        if ($cart->key == $cartKey) {
            try { $Product = Product::findOrFail($product_id);} catch (ModelNotFoundException $e) {
                return response()->json([
                    'message' => 'The Product you\'re trying to add does not exist.',
                ], 404);
            }

            //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
            $cartItem = CartItem::where(['cart_id' => $cart->getKey(), 'product_id' => $product_id])->first();
            if ($cartItem) {
                $cartItem->quantity = $quantity;
                CartItem::where(['cart_id' => $cart->getKey(), 'product_id' => $product_id])->update(['quantity' => $quantity]);
            } else {
                CartItem::create(['cart_id' => $cart->getKey(), 'product_id' => $product_id, 'quantity' => $quantity]);
            }

            return response()->json(['message' => 'The Cart was updated with the given product information successfully'], 200);

        } else {

            return response()->json([
                'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
            ], 400);
        }

    }

    /**
     * checkout the cart Items and create and order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return void
     */
    public function checkout(Cart $cart, CartCheckoutRequest $request)
    {

        if (Auth::guard('sanctum')->check()) {
            $user_id = Auth::guard('sanctum')->user()->id;
        }

        $cartKey = $request->input('cartKey');
        if ($cart->key == $cartKey) {
            $name = $request->input('name');
            $adress = $request->input('adress');
            $TotalPrice = (float) 0.0;
            $items = $cart->items;

            foreach ($items as $item) {
                $product = Product::find($item->product_id);
                $price = $product->price;
                $TotalPrice = $TotalPrice + ($price * $item->quantity);                
            }

            $PaymentGatewayResponse = true;
            $transactionID = md5(uniqid(rand(), true));

            if ($PaymentGatewayResponse) {
                $order = Order::create([
                    'products' => json_encode(new CartItemCollection($items)),
                    'totalPrice' => $TotalPrice,
                    'name' => $name,
                    'address' => $adress,
                    'user_id' => isset($user_id) ? $user_id : null,
                    'transactionID' => $transactionID,
                ]);

                $cart->delete();

                return response()->json([
                    'message' => 'you\'re order has been completed succefully, thanks for shopping with us!',
                    'order_id' => $order->getKey(),
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
            ], 400);
        }

    }
}
