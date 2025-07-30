<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\VisitTrackingService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackMenuVisit
{
    protected $visitTrackingService;

    public function __construct(VisitTrackingService $visitTrackingService)
    {
        $this->visitTrackingService = $visitTrackingService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log that middleware is being called
        Log::info('TrackMenuVisit middleware called', [
            'path' => $request->path(),
            'url' => $request->url(),
            'ip' => $request->ip()
        ]);

        // Execute the request first
        $response = $next($request);

        // Only track successful responses (200-299 status codes)
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            Log::info('Response successful, attempting to track visit', [
                'status_code' => $response->getStatusCode(),
                'path' => $request->path()
            ]);
            
            $this->trackVisit($request);
        } else {
            Log::info('Response not successful, skipping visit tracking', [
                'status_code' => $response->getStatusCode(),
                'path' => $request->path()
            ]);
        }

        return $response;
    }

    /**
     * Track the visit for the current request
     */
    protected function trackVisit(Request $request): void
    {
        try {
            // Get the current path
            $path = $request->path();
            
            // Skip certain paths that shouldn't be tracked
            if ($this->shouldSkipTracking($path)) {
                Log::info('Visit tracking skipped for path', ['path' => $path]);
                return;
            }

            // For dynamic routes, we need to get the slug from the URL
            $slug = $this->extractSlugFromPath($path);
            
            Log::info('Extracted slug from path', [
                'path' => $path,
                'extracted_slug' => $slug
            ]);
            
            if ($slug) {
                // Track the visit
                Log::info('Attempting to track visit for slug', ['slug' => $slug]);
                
                // Try Redis first, fallback to database
                try {
                    $this->visitTrackingService->trackVisit($slug);
                    Log::info('Redis visit tracking successful', ['slug' => $slug]);
                } catch (\Exception $e) {
                    Log::warning('Redis tracking failed, trying database fallback', [
                        'slug' => $slug,
                        'error' => $e->getMessage()
                    ]);
                    
                    // Direct database fallback
                    $menu = \App\Models\MasterMenu::where('slug', $slug)
                        ->orWhere('slug', '/' . ltrim($slug, '/'))
                        ->first();
                    
                    if ($menu) {
                        $oldCount = $menu->visit_count;
                        $menu->incrementVisit();
                        $menu->refresh();
                        $newCount = $menu->visit_count;
                        
                        Log::info('Database fallback successful', [
                            'slug' => $slug,
                            'menu_name' => $menu->nama_menu,
                            'old_count' => $oldCount,
                            'new_count' => $newCount
                        ]);
                    } else {
                        Log::warning('No menu found for slug', ['slug' => $slug]);
                    }
                }
                
            } else {
                Log::warning('No valid slug extracted from path', ['path' => $path]);
            }

        } catch (\Exception $e) {
            // Log error but don't break the request
            Log::error('Failed to track visit: ' . $e->getMessage(), [
                'path' => $request->path(),
                'exception' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Determine if tracking should be skipped for this path
     */
    protected function shouldSkipTracking(string $path): bool
    {
        $skipPaths = [
            'api/',
            'admin/',
            'login',
            'logout',
            'register',
            'password/',
            '_debugbar',
            'telescope',
            'health',
            'up',
            'favicon.ico',
            'robots.txt',
            'sitemap.xml',
        ];

        // Skip if path starts with any skip patterns
        foreach ($skipPaths as $skipPath) {
            if (str_starts_with($path, $skipPath)) {
                return true;
            }
        }

        // Skip API routes
        if (str_contains($path, '/api/')) {
            return true;
        }

        return false;
    }

    /**
     * Extract slug from the path for dynamic routes
     */
    protected function extractSlugFromPath(string $path): ?string
    {
        // Handle root path
        if ($path === '/' || $path === '') {
            return '/beranda'; // Default homepage slug
        }

        // For beranda path, return the exact slug as stored in database
        if ($path === 'beranda') {
            return '/beranda'; // Database stores it as /beranda
        }

        // Ensure path starts with /
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        // For dynamic CMS routes, the path should match the menu slug pattern
        // Remove any query parameters or fragments
        $slug = strtok($path, '?');
        $slug = strtok($slug, '#');

        // For other paths, try both with and without leading slash
        $possibleSlugs = [
            $slug,           // /about-us
            ltrim($slug, '/') // about-us
        ];

        // Check which one exists in database
        foreach ($possibleSlugs as $testSlug) {
            $menuExists = \App\Models\MasterMenu::where('slug', $testSlug)->exists();
            if ($menuExists) {
                return $testSlug;
            }
        }

        // If no menu found, return the original slug for logging
        return $slug;
    }

    /**
     * Validate if the slug is in the correct format
     */
    protected function isValidSlug(string $slug): bool
    {
        // Basic validation - adjust according to your slug rules
        if (strlen($slug) < 2 || strlen($slug) > 255) {
            return false;
        }

        // Should start with /
        if (!str_starts_with($slug, '/')) {
            return false;
        }

        // Should not contain certain characters
        if (preg_match('/[^a-zA-Z0-9\/\-_]/', $slug)) {
            return false;
        }

        return true;
    }
}
