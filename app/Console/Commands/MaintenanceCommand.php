<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class MaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:maintenance {action : up|down}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle application maintenance mode via settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        if ($action === 'down') {
            Setting::updateOrCreate(
                ['key' => 'maintenance_mode'],
                [
                    'value' => '1',
                    'type' => 'boolean',
                    'group' => 'feature',
                    'description' => 'Mode maintenance aplikasi'
                ]
            );
            
            $this->info('🔧 Application is now in MAINTENANCE mode.');
            $this->line('Only users with "access-panel" or "view-dashboard" permissions can access the application.');
            
        } elseif ($action === 'up') {
            Setting::updateOrCreate(
                ['key' => 'maintenance_mode'],
                [
                    'value' => '0',
                    'type' => 'boolean',
                    'group' => 'feature',
                    'description' => 'Mode maintenance aplikasi'
                ]
            );
            
            $this->info('✅ Application is now LIVE.');
            
        } else {
            $this->error('Invalid action. Use "up" or "down".');
            return 1;
        }

        // Clear cache if needed
        try {
            \Artisan::call('cache:clear');
            $this->line('Cache cleared.');
        } catch (\Exception $e) {
            // Silent fail if cache clear fails
        }

        return 0;
    }
}
