<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\ApiFormatter;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // AuthController with JWTAuth facade
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'access_token' => $token,
        ], 201);

        if (!request->isMethod('post')) {
            return response()->json(['message' => 'Method not allowed', 'data' => 'test'], 405);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        if (!JWTAuth::getToken()) {
            return ApiFormatter::createJson(401, 'A token is required');
        }

        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    public function logout()
    {
        if (!JWTAuth::getToken()) {
            return ApiFormatter::createJson(401, 'A token is required');
        }
        
        JWTAuth::parseToken()->invalidate();
        return response()->json(['message' => 'Logged out']);
    }

    public function refresh()
    {
        if (!JWTAuth::getToken()) {
            return ApiFormatter::createJson(401, 'A token is required');
        }

        try {
            $newToken = JWTAuth::refresh();
            return $this->respondWithToken($newToken);
        } catch (TokenExpiredException $e) {
            return ApiFormatter::createJson(401, 'Token refresh period has expired');
        } catch (JWTException $e) {
            return ApiFormatter::createJson(500, 'Could not refresh token');
        }
    }

    protected function respondWithToken($token)
    {
        // Gunakan factory dari facade agar tidak error tipe guard
        $ttl = JWTAuth::factory()->getTTL();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl * 60,
        ]);
    }
}
