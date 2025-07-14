<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Debugging Advanced Management Menu Issue\n";
echo "========================================\n\n";

// Check the viewer user specifically
$viewer = App\Models\User::where('email', 'viewer@example.com')->first();

if ($viewer) {
    auth()->login($viewer);
    echo "Logged in as viewer. Testing specific menus:\n\n";
    
    $advancedMenu = App\Models\MasterMenu::where('nama_menu', 'Advanced Management')->first();
    $contentMenu = App\Models\MasterMenu::where('nama_menu', 'Content Management')->first();
    
    if ($advancedMenu) {
        echo "Advanced Management:\n";
        echo "- Direct access: " . ($advancedMenu->isAccessible() ? 'Yes' : 'No') . "\n";
        echo "- Has accessible content: " . (menu_has_accessible_content($advancedMenu) ? 'Yes' : 'No') . "\n";
        echo "- Required permission: " . ($advancedMenu->getRequiredPermission() ?: 'None') . "\n";
        echo "- Slug: " . $advancedMenu->slug . "\n";
        echo "- Children count: " . $advancedMenu->children->count() . "\n";
        
        foreach ($advancedMenu->children as $child) {
            echo "  - " . $child->nama_menu . " accessible: " . ($child->isAccessible() ? 'Yes' : 'No') . "\n";
            echo "    - Has accessible content: " . (menu_has_accessible_content($child) ? 'Yes' : 'No') . "\n";
        }
        echo "\n";
    }
    
    if ($contentMenu) {
        echo "Content Management:\n";
        echo "- Direct access: " . ($contentMenu->isAccessible() ? 'Yes' : 'No') . "\n";
        echo "- Has accessible content: " . (menu_has_accessible_content($contentMenu) ? 'Yes' : 'No') . "\n";
        echo "- Required permission: " . ($contentMenu->getRequiredPermission() ?: 'None') . "\n";
        echo "- Slug: " . $contentMenu->slug . "\n";
        echo "- Children count: " . $contentMenu->children->count() . "\n";
        
        foreach ($contentMenu->children as $child) {
            echo "  - " . $child->nama_menu . " accessible: " . ($child->isAccessible() ? 'Yes' : 'No') . "\n";
            echo "    - Has accessible content: " . (menu_has_accessible_content($child) ? 'Yes' : 'No') . "\n";
        }
    }
    
    auth()->logout();
}

echo "\nDone!\n";
