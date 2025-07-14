<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Debugging Menu Permission Issue\n";
echo "===============================\n\n";

// Check users and their permissions
$editor = App\Models\User::where('email', 'editor@example.com')->first();
$viewer = App\Models\User::where('email', 'viewer@example.com')->first();

if ($editor) {
    echo "Editor user found:\n";
    echo "- Email: " . $editor->email . "\n";
    echo "- Roles: " . $editor->roles->pluck('name')->implode(', ') . "\n";
    echo "- Permissions: " . $editor->getAllPermissions()->pluck('name')->implode(', ') . "\n\n";
    
    // Simulate login as editor
    auth()->login($editor);
    echo "Logged in as editor. Testing menu access:\n";
    
    $testMenu = App\Models\MasterMenu::where('nama_menu', 'Panel Management')->first();
    if ($testMenu) {
        echo "- Panel Management isAccessible: " . ($testMenu->isAccessible() ? 'Yes' : 'No') . "\n";
        echo "- Panel Management hasAccessibleContent: " . (menu_has_accessible_content($testMenu) ? 'Yes' : 'No') . "\n";
        
        foreach ($testMenu->children as $child) {
            echo "  - " . $child->nama_menu . " isAccessible: " . ($child->isAccessible() ? 'Yes' : 'No') . "\n";
        }
    }
    
    auth()->logout();
    echo "\n";
}

if ($viewer) {
    echo "Viewer user found:\n";
    echo "- Email: " . $viewer->email . "\n";
    echo "- Roles: " . $viewer->roles->pluck('name')->implode(', ') . "\n";
    echo "- Permissions: " . $viewer->getAllPermissions()->pluck('name')->implode(', ') . "\n\n";
    
    // Simulate login as viewer
    auth()->login($viewer);
    echo "Logged in as viewer. Testing menu access:\n";
    
    $testMenu = App\Models\MasterMenu::where('nama_menu', 'Panel Management')->first();
    if ($testMenu) {
        echo "- Panel Management isAccessible: " . ($testMenu->isAccessible() ? 'Yes' : 'No') . "\n";
        echo "- Panel Management hasAccessibleContent: " . (menu_has_accessible_content($testMenu) ? 'Yes' : 'No') . "\n";
        
        foreach ($testMenu->children as $child) {
            echo "  - " . $child->nama_menu . " isAccessible: " . ($child->isAccessible() ? 'Yes' : 'No') . "\n";
        }
    }
    
    auth()->logout();
}

echo "\nDone!\n";
