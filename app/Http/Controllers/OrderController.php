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
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
class OrderController extends Controller
{
    public function add(Request $request)
    {

        $user = Auth::user();
        (int)$id = $request->id;
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $product = Product::find($id);
        (int)$quantity = $request->quantity;
        Log::info($quantity);
        Log::info($id);

        $cartItem = CartItem::where('cart_id' , $cart->id)->where('product_id' , $product->id)->first();
        if($cartItem && $quantity <= 1){
            $cartItem->quantity = $cartItem->quantity + 1;
            $cartItem->save();
        }elseif($cartItem && $quantity > 1){
            $cartItem->quantity = $quantity + $cartItem->quantity;
            $cartItem->save();
        }elseif(!$cartItem && $quantity > 1){
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }else{
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        if($cartItem){
            return response()->json('added');
        }

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
            CartItem::where('cart_id', $cart->id)->delete();
            Cart::where('id', $cart->id)->delete();
        }

        $invoice = (new Invoice())->amount((int)$total);

// ساخت پرداخت با callback
        $payment = Payment::callbackUrl(route('callback'))
            ->purchase($invoice, function ($driver, $transactionId) use ($order) {
                // فقط سفارش مربوطه رو آپدیت کن
                $order->update([
                    'transaction_id' => $transactionId,
                ]);
            });

// گرفتن لینک پرداخت (به جای render)
        $paymentUrl = $payment->pay()->getAction();

// برگردوندن لینک به فرانت
        return response()->json([
            'url' => $paymentUrl,
        ]);

    }

    public function callback(Request $request)
    {
        $transaction_id = $request->get('Authority');

        // پیدا کردن سفارش بر اساس transaction_id
        $order = Order::where('transaction_id', $transaction_id)->first();

        if (!$order) {
            return redirect("http://localhost:3000/payment/fail?message=" . urlencode("سفارش پیدا نشد"));
        }

        try {
            $receipt = Payment::amount((int)$order->total) // ✅ مبلغ واقعی سفارش
            ->transactionId($transaction_id)
                ->verify();

            // آپدیت وضعیت سفارش
            $order->update(['status' => 'finished']);

            // هدایت به فرانت
            return redirect("http://localhost:3000/payment/success?ref_id=".$receipt->getReferenceId());

        } catch (InvalidPaymentException $exception) {
            $order->update(['status' => 'cancelled']);
            return redirect("http://localhost:3000/payment/fail?message=".urlencode($exception->getMessage()));
        }
    }

}
