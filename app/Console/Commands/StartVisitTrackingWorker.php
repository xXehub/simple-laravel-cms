<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class StartVisitTrackingWorker extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'visit:worker 
                            {--queue=visits : The queue to process}
                            {--sleep=1 : Sleep time between jobs}
                            {--tries=3 : Number of attempts}
                            {--timeout=60 : Job timeout in seconds}';

    /**
     * The console command description.
     */
    protected $description = 'Start optimized queue worker for visit tracking';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queue = $this->option('queue');
        $sleep = $this->option('sleep');
        $tries = $this->option('tries');
        $timeout = $this->option('timeout');

        $this->info('🚀 Starting Visit Tracking Queue Worker...');
        $this->info("Queue: {$queue}");
        $this->info("Sleep: {$sleep}s");
        $this->info("Max tries: {$tries}");
        $this->info("Timeout: {$timeout}s");
        $this->info('');

        // Start the queue worker with optimized settings
        Artisan::call('queue:work', [
            'connection' => 'redis',
            '--queue' => $queue,
            '--sleep' => $sleep,
            '--tries' => $tries,
            '--timeout' => $timeout,
            '--verbose' => true,
            '--rest' => 1, // Restart worker every hour to prevent memory leaks
        ]);

        return 0;
    }
}
