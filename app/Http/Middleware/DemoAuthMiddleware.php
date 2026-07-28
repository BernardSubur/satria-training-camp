<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;

class DemoAuthMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (session()->has('demo_user')) {
            // Retrieve fake user from session
            $demoUser = session('demo_user');
            
            // Set the authenticated user for the current request
            Auth::setUser($demoUser);
            
            return $next($request);
        }

        // For normal users, run standard authentication
        $this->authenticate($request, $guards);

        return $next($request);
    }
}

