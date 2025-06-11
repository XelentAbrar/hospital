<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictLoginByIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the IP address is 103.x.x.x
        $ipAddress = $request->ip();

        $restrictedIp = '192.168.18.10';

        if (str_starts_with($ipAddress, $restrictedIp) && !optional(Auth::user())->isAdmin()) {
            return redirect()->route('login')->withErrors([
                'error' => 'Only admins can log in from this IP address.'
            ]);
        }

        return $next($request);
    }
}
