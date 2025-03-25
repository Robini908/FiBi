<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Jenssegers\Agent\Agent;

class DiagnosticController extends Controller
{
    /**
     * Display session, authentication, and request diagnostics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Security check - only allow in debug mode
        if (!config('app.debug')) {
            abort(404);
        }

        $diagnostics = [
            'session' => $this->getSessionInfo(),
            'auth' => $this->getAuthInfo(),
            'request' => $this->getRequestInfo($request),
            'cookies' => $this->getCookieInfo($request),
            'routes' => $this->getRouteInfo(),
            'environment' => $this->getEnvironmentInfo(),
        ];

        return response()->json($diagnostics);
    }

    /**
     * Get session information
     *
     * @return array
     */
    protected function getSessionInfo()
    {
        $sessionData = [];
        
        try {
            $sessionData = [
                'id' => Session::getId(),
                'started' => Session::isStarted(),
                'has_user_id' => Session::has('user_id'),
                'user_id' => Session::get('user_id'),
                'user_name' => Session::get('user_name'),
                'user_type' => Session::get('user_type'),
                'last_activity' => Session::get('last_activity') ? date('Y-m-d H:i:s', Session::get('last_activity')) : null,
                'token' => Session::token(),
            ];
        } catch (\Exception $e) {
            $sessionData['error'] = $e->getMessage();
        }

        return $sessionData;
    }

    /**
     * Get authentication information
     *
     * @return array
     */
    protected function getAuthInfo()
    {
        $authData = [
            'check' => Auth::check(),
            'guest' => Auth::guest(),
        ];

        if (Auth::check()) {
            $user = Auth::user();
            $authData['user'] = [
                'id' => $user->id,
                'name' => $user->name ?? 'N/A',
                'email' => $user->email ?? 'N/A',
                'type' => $user->user_type ?? 'N/A',
            ];
        }

        return $authData;
    }

    /**
     * Get request information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getRequestInfo(Request $request)
    {
        $agent = new Agent();
        
        return [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'path' => $request->path(),
            'url' => $request->url(),
            'ajax' => $request->ajax(),
            'secure' => $request->secure(),
            'user_agent' => $request->userAgent(),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device' => $agent->device(),
            'is_mobile' => $agent->isMobile(),
            'headers' => $this->getRequestHeaders($request),
        ];
    }

    /**
     * Get request headers
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getRequestHeaders(Request $request)
    {
        $headers = [];
        foreach ($request->headers->all() as $key => $value) {
            $headers[$key] = implode(', ', $value);
        }
        return $headers;
    }

    /**
     * Get cookie information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCookieInfo(Request $request)
    {
        $cookieData = [
            'request_cookies' => [],
            'cookie_jar_bound' => app()->bound('cookie'),
        ];

        // Get cookies from request
        foreach ($request->cookies->all() as $key => $value) {
            $cookieData['request_cookies'][$key] = substr($value, 0, 30) . (strlen($value) > 30 ? '...' : '');
        }

        return $cookieData;
    }

    /**
     * Get route information
     *
     * @return array
     */
    protected function getRouteInfo()
    {
        $routes = [];
        $registeredRoutes = Route::getRoutes();
        
        foreach ($registeredRoutes as $route) {
            $middleware = $route->middleware();
            $routes[] = [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware_count' => count($middleware),
            ];
        }

        return [
            'count' => count($routes),
            'routes' => array_slice($routes, 0, 10), // Show first 10 routes
        ];
    }

    /**
     * Get environment information
     *
     * @return array
     */
    protected function getEnvironmentInfo()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
        ];
    }
} 