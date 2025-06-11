<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckPermission
{
    public function handle($request, Closure $next, $permission = null)
    {
        // Get the authenticated user
        $user = Auth::user();
        if ($permission === null) {
            $permission = Route::currentRouteName();
        }

        // Check if the user has the necessary permission
        if ($user && $user->userRole) {
            foreach ($user->userRole as $key => $userRole) {
                if ($userRole && $userRole->hasPermission($permission)) {
                    return $next($request);
                }
            }
        }


        // Unauthorized, redirect or respond as needed
        return abort(403, 'Unauthorized');
    }
}
