<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SyncVisitCountsJob;

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
