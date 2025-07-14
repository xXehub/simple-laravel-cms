<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking Menu Hierarchy Structure\n";
echo "=================================\n\n";

$contentMenu = App\Models\MasterMenu::where('nama_menu', 'Content Management')->with('children')->first();

if ($contentMenu) {
    echo "Content Management Menu (ID: {$contentMenu->id}):\n";
    echo "- Children count: " . $contentMenu->children->count() . "\n";
    echo "- Parent ID: " . $contentMenu->parent_id . "\n\n";
    
    // Check if there are any menus that should be children
    $potentialChildren = App\Models\MasterMenu::where('parent_id', $contentMenu->id)->get();
    echo "Potential children (by parent_id): " . $potentialChildren->count() . "\n";
    
    foreach ($potentialChildren as $child) {
        echo "  - " . $child->nama_menu . " (ID: {$child->id})\n";
    }
    
    // Check the full tree structure
    echo "\nFull menu tree:\n";
    $allMenus = App\Models\MasterMenu::with('children')->get();
    foreach ($allMenus as $menu) {
        if ($menu->parent_id === null) {
            echo "ROOT: " . $menu->nama_menu . " (ID: {$menu->id})\n";
            foreach ($menu->children as $child) {
                echo "  - " . $child->nama_menu . " (ID: {$child->id})\n";
                foreach ($child->children as $grandchild) {
                    echo "    - " . $grandchild->nama_menu . " (ID: {$grandchild->id})\n";
                    foreach ($grandchild->children as $greatgrandchild) {
                        echo "      - " . $greatgrandchild->nama_menu . " (ID: {$greatgrandchild->id})\n";
                    }
                }
            }
        }
    }
}

echo "\nDone!\n";
