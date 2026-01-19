<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPaystackIp
{

    // Paystack webhook IPs (update if docs change)
    protected array $allowedIps = [
        '52.31.139.75',
        '52.49.173.169',
        '52.214.14.220',
    ]; // from Paystack webhook docs[web:86]
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if (! in_array($ip, $this->allowedIps, true)) {
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        }
        return $next($request);
    }
}

