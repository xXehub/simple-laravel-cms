<?php

namespace App\Jobs;

use App\Models\MasterMenu;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TrackVisitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;

    protected $slug;
    protected $userIdentifier;

    /**
     * Create a new job instance.
     */
    public function __construct(string $slug, string $userIdentifier)
    {
        $this->slug = $slug;
        $this->userIdentifier = $userIdentifier;
        
        // Set queue for visit tracking
        $this->onQueue('visit_tracking');
    }

    /**
     * Execute the job - Fast database tracking with unique visitor logic
     */
    public function handle(): void
    {
        try {
            // Find menu by slug with single query
            $menu = MasterMenu::where('slug', $this->slug)
                             ->orWhere('slug', '/' . ltrim($this->slug, '/'))
                             ->first(['id', 'slug', 'nama_menu', 'visit_count']);

            if (!$menu) {
                return; // Silent skip if menu not found
            }

            // Use database transaction for atomicity
            DB::transaction(function () use ($menu) {
                // Always increment visit count (total page views)
                $menu->increment('visit_count');

                // Track unique visitor using cache (simpler than session)
                $this->trackUniqueVisitor($menu->slug);
            });

        } catch (\Exception $e) {
            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Track unique visitor using cache-based deduplication
     */
    protected function trackUniqueVisitor(string $slug): void
    {
        // Create unique cache key for this IP/User on this page
        $cacheKey = 'unique_visit_' . md5($this->userIdentifier . '_' . $slug);
        
        // Check if this user has visited this page before (24 hour window)
        if (!Cache::has($cacheKey)) {
            // Mark as visited for 24 hours
            Cache::put($cacheKey, true, now()->addDay());
            
            // Here you could increment a separate unique_visitors field if needed
            // For now, we're just tracking that this user visited this page
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        // Silent failure - don't log visit tracking failures to reduce noise
    }
}
