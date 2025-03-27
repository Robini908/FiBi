<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            // Get current authenticated user
            $user = Auth::user();
            
            // Update the last_active_at timestamp only if more than a minute has passed
            // since the last update to avoid excessive database writes
            if (!$user->last_active_at || Carbon::parse($user->last_active_at)->diffInMinutes(Carbon::now()) >= 1) {
                $user->last_active_at = Carbon::now();
                $user->save();
            }
        }
        
        return $next($request);
    }
}
