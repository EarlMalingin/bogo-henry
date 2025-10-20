<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WalletSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS in production
        if (config('wallet_security.security.require_https') && !$request->secure() && app()->environment('production')) {
            return redirect()->secure($request->getRequestUri());
        }

        // Log suspicious activity
        if ($this->isSuspiciousRequest($request)) {
            Log::warning('Suspicious wallet request detected', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
        }

        // Add security headers
        $response = $next($request);
        
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }

    /**
     * Check if the request appears suspicious
     */
    private function isSuspiciousRequest(Request $request): bool
    {
        // Check for common attack patterns
        $suspiciousPatterns = [
            '/script/i',
            '/javascript/i',
            '/<script/i',
            '/union.*select/i',
            '/drop.*table/i',
            '/insert.*into/i',
            '/delete.*from/i',
            '/update.*set/i',
            '/exec\(/i',
            '/eval\(/i',
        ];

        $input = $request->all();
        $inputString = json_encode($input);

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $inputString)) {
                return true;
            }
        }

        // Check for unusual user agent
        $userAgent = $request->userAgent();
        if (empty($userAgent) || strlen($userAgent) < 10) {
            return true;
        }

        // Check for rapid requests (basic check)
        $key = 'wallet_requests:' . $request->ip();
        $requests = cache()->get($key, 0);
        
        if ($requests > 100) { // More than 100 requests in 1 minute
            return true;
        }

        cache()->put($key, $requests + 1, 60); // 1 minute

        return false;
    }
}
