<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class Authorization
{
    private $JWTS;

    public function __construct()
    {
        $this->JWTS = config('services.JWT.key'); // JWT secret
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token required! Add this to your headers: Authorization: Bearer TOKEN_STRING'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key($this->JWTS, 'HS256'));

            // Check if the token contains an email (Authorization check)
            if (!isset($decoded->email)) {
                return response()->json(['error' => 'You are not authorized!'], 401);
            }

            // Pass the request to the next middleware/controller
            return $next($request);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }
}
