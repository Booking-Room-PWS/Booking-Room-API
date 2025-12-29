<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Coba ambil user via guard api, fallback ke JWTAuth jika perlu
        $user = null;

        try {
            $user = auth('api')->user();
        } catch (\Throwable $e) {
            // ignore
        }

        if (!$user) {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (\Throwable $e) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        if (!$user || !$user->is_admin) {
            return response()->json(['error' => 'Admin Access Required'], 403);
        }

        return $next($request);
    }
}
