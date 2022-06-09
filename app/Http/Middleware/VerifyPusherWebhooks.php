<?php

namespace App\Http\Middleware;

use Ahc\Jwt\JWT;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyPusherWebhooks
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $pusherKey = $request->header('X-Pusher-Key');
        $pusherSignature = $request->header('X-Pusher-Signature');

        if ($pusherKey == null || $pusherSignature == null) {
            return $request->response('', 403);
        }

        if ($pusherKey != env('PUSHER_APP_KEY')) {
            return $request->response('', 403);
        }

        Log::debug($pusherKey . " " . $pusherSignature);

        $secret = base64_decode( env('PUSHER_APP_SECRET') );
        $jwt = new JWT($secret, 'HS256');
        try {
            $payload = $jwt->decode($pusherSignature);
        } catch (Exception $e)
        {
            Log::error($e->getMessage());
            return response('', 403);
        }

        return $next($request);
    }
}
