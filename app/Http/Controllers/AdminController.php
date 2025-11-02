<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
}
