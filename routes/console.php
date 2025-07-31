<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SyncVisitCountsJob;
use App\Jobs\TrackVisitJob;
use App\Services\VisitTrackingService;
use App\Models\MasterMenu;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule visit tracking sync job
Schedule::job(new SyncVisitCountsJob())
    ->everyFiveMinutes()
    ->name('sync-visit-counts')
    ->withoutOverlapping()
    ->onFailure(function () {
        \Log::error('SyncVisitCountsJob scheduled execution failed');
    });

Artisan::command('test:visit-tracking', function () {
    $this->info('=== TESTING VISIT TRACKING SYSTEM ===');
    
    // Test 1: Service instantiation
    $this->info('1. Testing VisitTrackingService...');
    try {
        $service = new VisitTrackingService();
        $stats = $service->getStats();
        $this->info('   ✓ Service working: ' . json_encode($stats));
    } catch (\Exception $e) {
        $this->error('   ✗ Service error: ' . $e->getMessage());
    }
    
    // Test 2: Job dispatch
    $this->info('2. Testing job dispatch...');
    try {
        TrackVisitJob::dispatch('test-page', 'ip_127.0.0.1');
        $this->info('   ✓ Job dispatched successfully');
    } catch (\Exception $e) {
        $this->error('   ✗ Job dispatch error: ' . $e->getMessage());
    }
    
    // Test 3: Check jobs in queue
    $this->info('3. Checking queue...');
    $jobCount = \DB::table('jobs')->where('queue', 'visit_tracking')->count();
    $this->info("   Jobs in visit_tracking queue: {$jobCount}");
    
    // Test 4: Menu check
    $this->info('4. Checking menus...');
    $menuCount = MasterMenu::count();
    $this->info("   Total menus in database: {$menuCount}");
    
    if ($menuCount > 0) {
        $sampleMenu = MasterMenu::first();
        $this->info("   Sample menu: {$sampleMenu->nama_menu} (slug: {$sampleMenu->slug})");
    }
    
    $this->info('=== TEST COMPLETED ===');
    $this->info('To start processing: php artisan queue:work --queue=visit_tracking');
    
})->purpose('Test the visit tracking system');

Artisan::command('start:visit-worker', function () {
    $this->info('Starting visit tracking worker...');
    $this->info('Queue: visit_tracking');
    $this->info('Press Ctrl+C to stop');
    $this->newLine();
    
    // Start the queue worker for visit tracking
    Artisan::call('queue:work', [
        '--queue' => 'visit_tracking',
        '--sleep' => 3,
        '--tries' => 3,
        '--max-time' => 3600
    ]);
    
})->purpose('Start the visit tracking queue worker');

Artisan::command('visit:stats', function () {
    $service = new VisitTrackingService();
    $stats = $service->getStats();
    
    $this->info('=== VISIT TRACKING STATISTICS ===');
    $this->table(
        ['Metric', 'Value'],
        [
            ['Total Database Visits', $stats['total_database_visits']],
            ['Total Menus', $stats['total_menus']],
            ['Cache Store', $stats['cache_store']],
            ['Tracking Method', $stats['tracking_method']]
        ]
    );
    
    // Show top pages
    $topPages = $service->getTopPages(5);
    if (!empty($topPages)) {
        $this->info('Top 5 Visited Pages:');
        $this->table(
            ['Menu Name', 'Slug', 'Visit Count'],
            array_map(function($page) {
                return [$page['nama_menu'], $page['slug'], $page['visit_count']];
            }, $topPages)
        );
    }
    
    // Show queue status
    $queueJobs = \DB::table('jobs')->where('queue', 'visit_tracking')->count();
    $this->info("Jobs in queue: {$queueJobs}");
    
})->purpose('Show visit tracking statistics');
