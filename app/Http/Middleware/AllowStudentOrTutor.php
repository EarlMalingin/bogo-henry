<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowStudentOrTutor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!\Illuminate\Support\Facades\Auth::guard('student')->check() && 
            !\Illuminate\Support\Facades\Auth::guard('tutor')->check()) {
            abort(403, 'Unauthorized. You must be logged in as a student or tutor.');
        }
        
        return $next($request);
    }
}
