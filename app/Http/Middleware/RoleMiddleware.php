<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if (Auth::user()->hasRole($role)) {
                return $next($request);
            }
        }

        // User has none of the required roles
        return response()->json(['error' => 'Forbidden: Insufficient permissions.'], 403);
    }
    
}
