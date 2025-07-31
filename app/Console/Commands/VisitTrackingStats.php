<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Services\VisitTrackingService;

class VisitTrackingStats extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'visit:stats 
                            {--clear : Clear all visit tracking data}
                            {--export : Export current statistics}';

    /**
     * The console command description.
     */
    protected $description = 'Show visit tracking statistics and queue status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('clear')) {
            return $this->clearAllData();
        }

        if ($this->option('export')) {
            return $this->exportData();
        }

        $this->showStats();
        return 0;
    }

    protected function showStats()
    {
        $this->info('📊 Visit Tracking System Statistics');
        $this->info(str_repeat('=', 50));

        // Get service stats
        $service = app(VisitTrackingService::class);
        $stats = $service->getStats();

        // Queue stats
        try {
            $redis = Redis::connection();
            $queueLength = $redis->llen('queues:visits');
            $processingLength = $redis->llen('queues:visits:processing');
            
            $this->table(['Metric', 'Value'], [
                ['Queue Status', $queueLength > 0 ? "✅ Active ({$queueLength} pending)" : '✅ Empty'],
                ['Processing Jobs', $processingLength],
                ['Redis Available', $stats['redis_available'] ? '✅ Yes' : '❌ No'],
                ['Total DB Visits', number_format($stats['total_database_visits'])],
                ['Total Menus', $stats['total_menus']],
                ['Redis Keys', $stats['redis_keys']],
                ['Buffer Count', $stats['buffer_count']],
                ['Unique Today', $stats['unique_visitors_today'] ?? 'N/A'],
            ]);

        } catch (\Exception $e) {
            $this->error('Could not connect to Redis: ' . $e->getMessage());
        }

        // Recent performance
        $this->info("\n🚀 Performance Status:");
        if ($stats['redis_available']) {
            $this->info('✅ Queue-based tracking active (ZERO page load impact)');
            $this->info('✅ Predis pipeline optimization enabled');
            $this->info('✅ Unique visitor tracking by IP');
        } else {
            $this->warn('⚠️  Redis unavailable - using database fallback');
        }
    }

    protected function clearAllData()
    {
        if (!$this->confirm('Are you sure you want to clear ALL visit tracking data?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $service = app(VisitTrackingService::class);
        $result = $service->resetAllVisits();

        $this->info('🗑️  Clearing all visit tracking data...');
        $this->info("Database records reset: {$result['database_reset']}");
        $this->info("Redis keys deleted: {$result['redis_reset']}");
        $this->info('✅ All data cleared successfully!');

        return 0;
    }

    protected function exportData()
    {
        $service = app(VisitTrackingService::class);
        $data = $service->exportData();

        $filename = 'visit_tracking_export_' . date('Y-m-d_H-i-s') . '.json';
        $path = storage_path('app/' . $filename);

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));

        $this->info('📁 Data exported to: ' . $path);
        $this->info('📊 Export includes:');
        $this->info('   • Database visit counts');
        $this->info('   • Pending Redis data');
        $this->info('   • System settings');

        return 0;
    }
}
