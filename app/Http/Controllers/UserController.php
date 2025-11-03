<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function orders()
    {
        $user = Auth::user();
        $order = Order::where('user_id' , $user->id)->get();
        return response()->json($order);
    }
}
