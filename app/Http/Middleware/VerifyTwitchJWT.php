<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ahc\Jwt\JWT;
use Exception;

class VerifyTwitchJWT
{
    public function __construct()
    {
        
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $secret = base64_decode( env('TWITCH_CLIENT_SECRET') );
        $jwt = new JWT($secret, 'HS256');
        $token = $request->bearerToken();
        try {
            $payload = $jwt->decode($token);
            $request->attributes->add([
                "payload" => $payload
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            abort(403);
        }

        return $next($request);
    }
}
