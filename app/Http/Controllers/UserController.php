<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function orders()
    {
        $user = Auth::user();
        $order = Order::where('user_id' , $user->id)->with('user')->get();
        return response()->json($order);

    }
    public function update(Request $request)
    {
        Log::info($request);
        $user = Auth::user();

       if($request->hasFile('avatar')){
           $avatar = $request->file('avatar')->store('images', 'public');
           $url = url(Storage::url($avatar));
       };
       $user->update([
           'name' => $request['name'] ?? $user->name ?? '',
           'email' => $request['email'] ?? $user->email ?? '',
           'avatar' => $url ?? $user->avatar ?? '',
           'firstName' => $request['firstName'] ?? $user->firstName ?? '',
           'lastName' => $request['lastName'] ?? $user->lastName ?? '',
           'bio' => $request['bio'] ?? $user->bio ?? '',
           'password' => $request['password'] ?? $user->password ?? '',
       ]);
    }
}
