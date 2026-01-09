<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonHeaders
{
    public function handle(Request $request, Closure $next)
    {
        // Force server-side Accept header to application/json
        $request->headers->set('Accept', 'application/json');

        // If request method has a body and Content-Type absent, set JSON
        $method = strtoupper($request->method());
        if (in_array($method, ['POST','PUT','PATCH']) && ! $request->header('Content-Type')) {
            $request->headers->set('Content-Type', 'application/json');
        }

        $response = $next($request);

        // Ensure response has JSON content-type
        if (method_exists($response, 'headers')) {
            $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        }

        return $response;
    }
}