<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $ip =$request->ip();
        $credentials = $request->only('email', 'password');

        try {
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thông tin đăng nhập không chính xác',
                    'errors' => ['auth' => ['Email hoặc mật khẩu không đúng']]
                ], 401);
            }

            $user = Auth::user();
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $ip,
            ]);

            $tokenName = 'auth_token_' . time();
            $token = $user->createToken($tokenName)->plainTextToken;
            $remember = $request->boolean('remember');
            $tokenTtl = $remember ? 60 * 24 *30 : 60;

            $cookie = Cookie::make(
            name: 'auth_token',
            value: $token,
            minutes: $tokenTtl,
            path: '/',
            domain: null,
            secure: env('APP_ENV') === 'production',
            httpOnly: true,
            sameSite: 'Lax'
        );

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $ip,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ])->withCookie($cookie);
        } catch (\Exception $e) {
             Log::error('Login failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'ip' => $ip,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đăng nhập thất bại',
                'errors' => ['system' => ['Có lỗi xảy ra, vui lòng thử lại']]
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            
            $request->user()->currentAccessToken()->delete();

            $cookie = Cookie::forget('auth_token');

            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công'
            ])->withCookie($cookie);

        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đăng xuất thất bại'
            ], 500);
        }
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}
