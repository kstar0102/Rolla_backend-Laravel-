<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check if the request is for admin routes
            if ($request->is('car-types') || $request->is('car-type/*') || 
                $request->is('droppins') || $request->is('droppin/*') ||
                $request->is('dashboard') || $request->is('users') || 
                $request->is('user/*') || $request->is('trips') || 
                $request->is('trip/*')) {
                return route('login');
            }
            return route('login');
        }
    }
}
