<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip middleware if maintenance mode is disabled
        if (!setting('maintenance_mode', false)) {
            return $next($request);
        }

        // Always allow access to authentication and maintenance routes
        $allowedRoutes = [
            'login',
            'logout', 
            'maintenance',
            'password.request',
            'password.email',
            'password.reset',
            'password.update'
        ];

        $currentRoute = $request->route();
        $routeName = $currentRoute ? $currentRoute->getName() : null;
        
        if ($routeName && in_array($routeName, $allowedRoutes)) {
            return $next($request);
        }

        // Process the request to ensure session is loaded
        $response = $next($request);
        
        // Check if authenticated user has permission to bypass maintenance
        if (auth()->check()) {
            $user = auth()->user();
            
            try {
                $canBypassMaintenance = $user->hasPermissionTo('access-panel') || 
                                      $user->hasPermissionTo('view-dashboard');
                
                if ($canBypassMaintenance) {
                    return $response;
                }
            } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                // Permission doesn't exist, continue to maintenance page
            }
        }

        // Show maintenance page for all other requests
        return response()->view('maintenance', [], 503);
    }
}
