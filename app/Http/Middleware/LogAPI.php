<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\LogModel;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Str;
use Throwable;

class LogAPI
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = null;

        // Try to get user id from token (do not throw if token missing)
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user ? $user->id : null;
        } catch (Throwable $e) {
            $userId = null;
        }

        // Filter sensitive data using ApiFormatter helper (you already have this)
        $filteredRequest = ApiFormatter::filterSensitiveData($request->all());

        // Convert request to JSON string (safe)
        $requestPayload = json_encode($filteredRequest, JSON_UNESCAPED_UNICODE);

        // Optional: truncate long request payloads to avoid huge DB rows
        $requestPayload = (strlen($requestPayload) > 2000) ? Str::limit($requestPayload, 2000) : $requestPayload;

        // Create initial log entry
        $log = LogModel::create([
            'user_id'     => $userId,
            'log_method'  => $request->method(),
            'log_url'     => $request->fullUrl(),
            'log_ip'      => $request->ip(),
            'log_request' => $requestPayload,
        ]);

        try {
            $response = $next($request);

            // Get response content safely
            $content = method_exists($response, 'getContent') ? $response->getContent() : (string) $response;

            // Truncate long responses to reasonable length (adjust as needed)
            $content = (strlen($content) > 4000) ? Str::limit($content, 4000) : $content;

            // Update log entry
            $log->update([
                'log_response' => $content,
            ]);

            return $response;
        } catch (Throwable $e) {
            // Format error response (consistent with ApiFormatter)
            $errorResponse = ApiFormatter::createJson(500, 'Internal Server Error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            // Store error (as JSON string)
            $log->update([
                'log_response' => json_encode($errorResponse, JSON_UNESCAPED_UNICODE),
            ]);

            // Return the JSON error response (500)
            return response()->json($errorResponse, 500);
        }
    }
}