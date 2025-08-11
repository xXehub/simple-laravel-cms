<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class ToggleMaintenanceMode extends Command
{
    protected $signature = 'maintenance:toggle {status?}';
    protected $description = 'Toggle maintenance mode on/off';

    public function handle()
    {
        $status = $this->argument('status');
        
        if ($status === null) {
            // Toggle current status
            $currentStatus = setting('maintenance_mode', false);
            $newStatus = !$currentStatus;
        } else {
            $newStatus = in_array(strtolower($status), ['true', '1', 'on', 'enable']);
        }

        // Update or create setting
        Setting::updateOrCreate(
            ['key' => 'maintenance_mode'],
            [
                'value' => $newStatus ? '1' : '0',
                'type' => 'boolean',
                'group' => 'feature',
                'description' => 'Mode maintenance aplikasi'
            ]
        );

        $statusText = $newStatus ? 'ENABLED' : 'DISABLED';
        $this->info("Maintenance mode is now: {$statusText}");
        
        return 0;
    }
}
