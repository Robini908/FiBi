<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Make sure we have a session started
        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }

        // Save basic user data to session if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Store user data in session
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_type', $user->user_type);
        }

        // Process the request through middleware stack
        $response = $next($request);

        return $response;
    }
} 