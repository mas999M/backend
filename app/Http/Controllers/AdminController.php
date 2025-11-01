<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function user()
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
