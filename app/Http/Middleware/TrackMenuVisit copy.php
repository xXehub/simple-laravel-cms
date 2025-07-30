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
     * Handle an incoming request - OPTIMIZED FOR PERFORMANCE
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Execute the request first
        $response = $next($request);

        // Only track legitimate page visits (not AJAX/DataTable requests)
        if ($this->shouldTrackRequest($request, $response)) {
            $this->trackVisit($request);
        }

        return $response;
    }

    /**
     * Determine if this request should be tracked
     */
    protected function shouldTrackRequest(Request $request, Response $response): bool
    {
        // Only track successful responses
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            return false;
        }

        // Skip AJAX requests (including DataTable requests)
        if ($request->ajax()) {
            return false;
        }

        // Skip if request wants JSON response (API calls)
        if ($request->wantsJson()) {
            return false;
        }

        // Skip if X-Requested-With header indicates AJAX
        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return false;
        }

        // Skip if Accept header requests JSON/XML (API responses)
        $acceptHeader = $request->header('Accept', '');
        if (str_contains($acceptHeader, 'application/json') || 
            str_contains($acceptHeader, 'application/xml')) {
            return false;
        }

        // Skip if Content-Type suggests API response
        $contentType = $response->headers->get('Content-Type', '');
        if (str_contains($contentType, 'application/json') || 
            str_contains($contentType, 'application/xml')) {
            return false;
        }

        // Skip DataTable-specific query parameters
        if ($request->has(['draw', 'start', 'length'])) {
            return false; // This is a DataTable AJAX request
        }

        // Skip certain paths
        if ($this->shouldSkipTracking($request->path())) {
            return false;
        }

        return true;
    }

    /**
     * Track the visit for the current request - SIMPLIFIED
     */
    protected function trackVisit(Request $request): void
    {
        try {
            $slug = $this->extractSlugFromPath($request->path());
            
            if (!$slug) {
                return; // No valid slug, skip tracking
            }

            // Track using the service (handles Redis/database fallback internally)
            $this->visitTrackingService->trackVisit($slug);
            
        } catch (\Exception $e) {
            // Log error but don't break the request
            Log::error('Visit tracking failed', [
                'path' => $request->path(),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Determine if tracking should be skipped for this path - PERFORMANCE OPTIMIZED
     */
    protected function shouldSkipTracking(string $path): bool
    {
        // Quick checks first (most common cases)
        if (empty($path) || $path === '/') {
            return false; // Track homepage
        }

        // Use simple string operations for better performance
        $firstChar = $path[0] ?? '';
        $firstSegment = strtok($path, '/');

        // Skip common non-page requests
        static $skipSegments = [
            'api' => true,
            'admin' => true,
            'login' => true,
            'logout' => true,
            'register' => true,
            'password' => true,
            '_debugbar' => true,
            'telescope' => true,
            'health' => true,
            'up' => true,
            'favicon.ico' => true,
            'robots.txt' => true,
            'sitemap.xml' => true,
            'css' => true,
            'js' => true,
            'images' => true,
            'img' => true,
            'assets' => true,
            'storage' => true,
        ];

        return isset($skipSegments[$firstSegment]);
    }

    /**
     * Extract slug from the path for dynamic routes - PERFORMANCE OPTIMIZED
     */
    protected function extractSlugFromPath(string $path): ?string
    {
        // Handle root path
        if ($path === '/' || $path === '') {
            return '/';
        }

        // Normalize path - ensure starts with /
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        // Clean path - remove query parameters and fragments
        $cleanPath = strtok($path, '?') ?: $path;
        $cleanPath = strtok($cleanPath, '#') ?: $cleanPath;

        // Use static cache for database lookups to avoid repeated queries
        static $slugCache = [];
        
        if (isset($slugCache[$cleanPath])) {
            return $slugCache[$cleanPath];
        }

        // Try exact match first
        $menuExists = \App\Models\MasterMenu::where('slug', $cleanPath)->exists();
        if ($menuExists) {
            $slugCache[$cleanPath] = $cleanPath;
            return $cleanPath;
        }

        // Try without leading slash
        $withoutSlash = ltrim($cleanPath, '/');
        $menuExists = \App\Models\MasterMenu::where('slug', $withoutSlash)->exists();
        if ($menuExists) {
            $slugCache[$cleanPath] = $withoutSlash;
            return $withoutSlash;
        }

        // Cache negative results too to avoid repeated queries
        $slugCache[$cleanPath] = null;
        return null;
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
