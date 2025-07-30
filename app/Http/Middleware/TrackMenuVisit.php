<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\VisitTrackingService;
use App\Models\MasterMenu;
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

        // Skip certain paths first (most specific)
        if ($this->shouldSkipTracking($request->path())) {
            return false;
        }

        // Skip DataTable AJAX requests specifically 
        // DataTable sends these query parameters: draw, start, length
        if ($request->has(['draw', 'start', 'length'])) {
            return false; // This is definitely a DataTable AJAX request
        }

        // Skip if X-Requested-With header indicates AJAX AND it's requesting JSON
        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Check if it's actually requesting JSON data (like DataTable)
            $acceptHeader = $request->header('Accept', '');
            if (str_contains($acceptHeader, 'application/json')) {
                return false; // AJAX request wanting JSON = not a page visit
            }
        }

        // Skip pure API calls (URLs starting with /api/)
        if (str_starts_with($request->path(), 'api/')) {
            return false;
        }

        // Skip if response content type is JSON/XML (API response)
        $contentType = $response->headers->get('Content-Type', '');
        if (
            str_contains($contentType, 'application/json') ||
            str_contains($contentType, 'application/xml')
        ) {
            return false;
        }

        // Track everything else (normal page visits)
        return true;
    }

    /**
     * Track visit - ULTRA FAST VERSION
     */
    protected function trackVisit(Request $request): void
    {
        try {
            $slug = $this->extractSlugFromPath($request->path());

            if (!$slug) {
                return; // No slug = skip (no logging for performance)
            }

            // Track in background - don't block response
            $this->visitTrackingService->trackVisit($slug);

        } catch (\Exception $e) {
            // Silent error handling - no logging to avoid performance hit
            // Errors will be caught by the service's own error handling
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
     * Extract slug - PERFORMANCE OPTIMIZED with aggressive caching
     */
    protected function extractSlugFromPath(string $path): ?string
    {
        // Static cache for ultra-fast lookups
        static $slugCache = [];
        static $cacheSize = 0;

        // Limit cache size to prevent memory issues
        if ($cacheSize > 1000) {
            $slugCache = array_slice($slugCache, -500, null, true);
            $cacheSize = 500;
        }

        if (isset($slugCache[$path])) {
            return $slugCache[$path];
        }

        // Handle common cases first
        if ($path === '/' || $path === '') {
            $slugCache[$path] = 'beranda';
            $cacheSize++;
            return 'beranda';
        }

        // Clean path quickly
        $cleanPath = ltrim($path, '/');
        $cleanPath = strtok($cleanPath, '?') ?: $cleanPath;
        $cleanPath = strtok($cleanPath, '#') ?: $cleanPath;

        // Quick validation - only alphanumeric, dash, underscore, slash
        if (preg_match('/[^a-zA-Z0-9\-_\/]/', $cleanPath)) {
            $slugCache[$path] = null;
            $cacheSize++;
            return null;
        }

        // Try without slash first (most common format)
        $exists = MasterMenu::where('slug', $cleanPath)->exists();
        if ($exists) {
            $slugCache[$path] = $cleanPath;
            $cacheSize++;
            return $cleanPath;
        }

        // Try with leading slash
        $withSlash = '/' . $cleanPath;
        $exists = MasterMenu::where('slug', $withSlash)->exists();
        if ($exists) {
            $slugCache[$path] = $withSlash;
            $cacheSize++;
            return $withSlash;
        }

        // Not found - cache negative result
        $slugCache[$path] = null;
        $cacheSize++;
        return null;
    }
}