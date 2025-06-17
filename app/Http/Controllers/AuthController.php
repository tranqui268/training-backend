<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{

    public function showLogin(){
        return Inertia::render('Auth/Login');
    }

    public function showDasboard(){
        return Inertia::render('Dashboard', [
            'user' => Auth::user()
        ]);
    }

    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'auth' => 'Email hoặc mật khẩu không đúng.'
            ])->withInput();
        }

        $request->session()->regenerate();
        $user = Auth::user();
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return redirect()->intended('/dashboard')->with('success', 'Đăng nhập thành công');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}
