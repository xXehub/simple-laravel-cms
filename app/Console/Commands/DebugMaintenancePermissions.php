<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DebugMaintenancePermissions extends Command
{
    protected $signature = 'debug:maintenance-permissions {email}';
    protected $description = 'Debug maintenance mode permissions for a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $this->info("=== DEBUG MAINTENANCE PERMISSIONS ===");
        $this->info("User: {$user->name} ({$user->email})");
        $this->info("User ID: {$user->id}");

        // Check maintenance mode setting
        $maintenanceMode = setting('maintenance_mode', false);
        $this->info("Maintenance Mode: " . ($maintenanceMode ? 'ENABLED' : 'DISABLED'));

        // Check if user is authenticated
        $this->info("User authenticated: YES (we're checking manually)");

        // Check permissions exist
        $accessPanelExists = Permission::where('name', 'access-panel')->exists();
        $viewDashboardExists = Permission::where('name', 'view-dashboard')->exists();
        
        $this->info("Permission 'access-panel' exists: " . ($accessPanelExists ? 'YES' : 'NO'));
        $this->info("Permission 'view-dashboard' exists: " . ($viewDashboardExists ? 'YES' : 'NO'));

        // Check user permissions
        $hasAccessPanel = false;
        $hasViewDashboard = false;

        try {
            $hasAccessPanel = $user->hasPermissionTo('access-panel');
            $this->info("User has 'access-panel': " . ($hasAccessPanel ? 'YES' : 'NO'));
        } catch (\Exception $e) {
            $this->error("Error checking 'access-panel': " . $e->getMessage());
        }

        try {
            $hasViewDashboard = $user->hasPermissionTo('view-dashboard');
            $this->info("User has 'view-dashboard': " . ($hasViewDashboard ? 'YES' : 'NO'));
        } catch (\Exception $e) {
            $this->error("Error checking 'view-dashboard': " . $e->getMessage());
        }

        // Show user roles
        $this->info("User Roles:");
        foreach ($user->roles as $role) {
            $this->info("  - {$role->name}");
        }

        // Show direct permissions
        $this->info("Direct Permissions:");
        foreach ($user->permissions as $permission) {
            $this->info("  - {$permission->name}");
        }

        // Show permissions via roles
        $this->info("Permissions via Roles:");
        foreach ($user->roles as $role) {
            $this->info("  Role: {$role->name}");
            foreach ($role->permissions as $permission) {
                $this->info("    - {$permission->name}");
            }
        }

        // Final decision
        $canBypass = $hasAccessPanel || $hasViewDashboard;
        $this->info("=== FINAL DECISION ===");
        $this->info("Can bypass maintenance: " . ($canBypass ? 'YES' : 'NO'));

        return 0;
    }
}
