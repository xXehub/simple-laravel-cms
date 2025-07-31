<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Jobs\TrackVisitJob;
use App\Models\MasterMenu;
use Symfony\Component\HttpFoundation\Response;

class TrackMenuVisit
{
    /**
     * Handle an incoming request - Optimized for performance
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

        // Skip certain paths first
        if ($this->shouldSkipTracking($request->path())) {
            return false;
        }

        // Skip DataTable AJAX requests (they send draw, start, length parameters)
        if ($request->has(['draw', 'start', 'length'])) {
            return false;
        }

        // Skip AJAX requests wanting JSON data
        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            $acceptHeader = $request->header('Accept', '');
            if (str_contains($acceptHeader, 'application/json')) {
                return false;
            }
        }

        // Skip API calls
        if (str_starts_with($request->path(), 'api/')) {
            return false;
        }

        // Skip if response is JSON/XML (API response)
        $contentType = $response->headers->get('Content-Type', '');
        if (
            str_contains($contentType, 'application/json') ||
            str_contains($contentType, 'application/xml')
        ) {
            return false;
        }

        return true;
    }

    /**
     * Track visit using queue for zero performance impact
     */
    protected function trackVisit(Request $request): void
    {
        try {
            $slug = $this->extractSlugFromPath($request->path());

            if (!$slug) {
                return; // No valid slug found
            }

            // Generate user identifier for unique visitor tracking
            $userIdentifier = $this->generateUserIdentifier($request);

            // Dispatch to queue for background processing
            TrackVisitJob::dispatch($slug, $userIdentifier);

        } catch (\Exception $e) {
            // Silent error handling - don't affect user experience
        }
    }

    /**
     * Generate unique user identifier for accurate tracking
     */
    protected function generateUserIdentifier(Request $request): string
    {
        $ip = $request->ip();
        
        // Use user ID if authenticated
        if (auth()->check()) {
            return 'user_' . auth()->id() . '_' . $ip;
        }
        
        // For anonymous users, use IP only
        return 'ip_' . $ip;
    }

    /**
     * Check if tracking should be skipped for this path
     */
    protected function shouldSkipTracking(string $path): bool
    {
        if (empty($path) || $path === '/') {
            return false; // Track homepage
        }

        $firstSegment = strtok($path, '/');

        // Skip common non-page requests
        $skipSegments = [
            'admin', 'login', 'logout', 'register', 'password',
            '_debugbar', 'telescope', 'health', 'up',
            'favicon.ico', 'robots.txt', 'sitemap.xml',
            'css', 'js', 'images', 'img', 'assets', 'storage',
            'build', 'libs'
        ];

        return in_array($firstSegment, $skipSegments);
    }

    /**
     * Extract slug from path with caching
     */
    protected function extractSlugFromPath(string $path): ?string
    {
        static $slugCache = [];

        if (isset($slugCache[$path])) {
            return $slugCache[$path];
        }

        // Handle homepage
        if ($path === '/' || $path === '') {
            $slugCache[$path] = 'beranda';
            return 'beranda';
        }

        // Clean path
        $cleanPath = ltrim($path, '/');
        $cleanPath = strtok($cleanPath, '?') ?: $cleanPath;

        // Basic validation
        if (preg_match('/[^a-zA-Z0-9\-_\/]/', $cleanPath)) {
            $slugCache[$path] = null;
            return null;
        }

        // Check if menu exists with this slug
        $exists = MasterMenu::where('slug', $cleanPath)
                           ->orWhere('slug', '/' . $cleanPath)
                           ->exists();

        if ($exists) {
            $slugCache[$path] = $cleanPath;
            return $cleanPath;
        }

        $slugCache[$path] = null;
        return null;
    }
}