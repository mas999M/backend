<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function order()
    {
        $order = Order::with('user')->get();
        return response()->json($order);
    }
    public function update_users(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function orders($id)
    {
        $orderItem = OrderItem::where('order_id', $id)->with([
            'product',
            'product.category',
        ])->get();

// اگر image_path فقط مسیر داخلی است، URL بسازید
        $orderItem->transform(function ($item) {
            $item->product->image_url = asset('storage/' . $item->product->image_path);
            return $item;
        });

        return response()->json($orderItem);

    }

    public function userOrders($id)
    {
        $orderItem = OrderItem::where('order_id', $id)->with([
            'product',
            'product.category',
        ])->get();

// اگر image_path فقط مسیر داخلی است، URL بسازید
        $orderItem->transform(function ($item) {
            $item->product->image_url = asset('storage/' . $item->product->image_path);
            return $item;
        });

        return response()->json($orderItem);
    }


}
