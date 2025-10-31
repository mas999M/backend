<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function add(Request $request)
    {
        $user = Auth::user();
        (int)$id = $request->input('id');
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $product = Product::find($id);
        Log::info($product);

        $cartItem = CartItem::where('cart_id' , $cart->id)->where('product_id' , $product->id)->first();
        if($cartItem){
            $cartItem->quantity = $cartItem->quantity + 1;
            $cartItem->save();
        }else{
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return $cartItem;
    }

    public function show()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id' , $user->id)->first();
        $cartItem = CartItem::where('cart_id' , $cart->id)->with('product')->get();

        $cartItem->transform(function ($item) {
            if ($item->product && $item->product->image_path) {
                $item->product->image_url = asset('storage/' . $item->product->image_path);
            } else {
                $item->product->image_url = null;
            }
            return $item;
        });

        return response()->json($cartItem);
    }

    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id' , $user->id)->first();
        $cartItem = CartItem::where('cart_id' , $cart->id)->get();

        $total = 0;
        foreach ($cartItem as $item) {
            $total += $item->quantity * $item->product->price;
        }

        $order = Order::create([
            'user_id' => $cart->user_id,
            'total' => $total,
        ]);

        foreach($cartItem as $item) {
           $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ]);
        }

        if($orderItem){
            Cart::query()->delete();
            CartItem::query()->delete();
        }

    }


}
