<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = new User();
        $user->name =$request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();
        return "loggeeeeddd   horaaaaaaaaaaaa";

    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // ایجاد session و regenerate برای جلوگیری از session fixation
        $request->session()->regenerate();

        return response()->json(['logged' => true]);
    }

    public function me()
    {
//        $user = Auth::user();

        return response()->json(Auth::user());

        // فقط اطلاعات مورد نیاز را ارسال کنید
//        return response()->json($user);
    }
    // خروج از حساب کاربری
    public function logout(Request $request)
    {
        Auth::guard('web')->logout(); // کاربر فعلی را از Auth خارج می‌کند

        // invalidate و regenerate برای امنیت بیشتر
        session()->invalidate();
        session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

}
