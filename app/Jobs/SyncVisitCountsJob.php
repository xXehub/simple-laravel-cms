<?php

namespace App\Jobs;

use App\Services\VisitTrackingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncVisitCountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Execute the job.
     */
    public function handle(VisitTrackingService $visitTrackingService): void
    {
        try {
            Log::info('SyncVisitCountsJob started');

            // Sync Redis data to database
            $syncResult = $visitTrackingService->syncToDatabase();

            if (is_array($syncResult)) {
                $syncedCount = count($syncResult);
                Log::info("SyncVisitCountsJob completed successfully", [
                    'synced_count' => $syncedCount,
                    'synced_slugs' => array_keys($syncResult)
                ]);
            } else {
                Log::info("SyncVisitCountsJob completed", [
                    'result' => $syncResult
                ]);
            }

        } catch (\Exception $e) {
            Log::error('SyncVisitCountsJob failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw exception to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SyncVisitCountsJob failed permanently after all retries', [
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
            'max_tries' => $this->tries
        ]);
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array
    {
        // Exponential backoff: 30s, 60s, 120s
        return [30, 60, 120];
    }
}
