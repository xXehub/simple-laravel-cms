<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterMenu;

echo "=== DEBUGGING PARENT ID ISSUE ===\n";
echo "All menus in database:\n";

$menus = MasterMenu::orderBy('id')->get(['id', 'nama_menu', 'parent_id']);

foreach ($menus as $menu) {
    echo "ID: {$menu->id}, Name: {$menu->nama_menu}, Parent: " . ($menu->parent_id ?? 'NULL') . "\n";
}

echo "\n=== Parent Menu Options Generation ===\n";

// Simulate the getMenuOptions method
function getMenuOptions($parentId = null, $level = 0, $excludeId = null)
{
    $menus = MasterMenu::where('parent_id', $parentId)
        ->orderBy('urutan')
        ->orderBy('id')
        ->get();

    $options = [];

    foreach ($menus as $menu) {
        if ($excludeId && $menu->id == $excludeId) {
            continue;
        }

        $prefix = str_repeat('└─ ', $level);
        $hasChildren = $menu->children()->count() > 0;
        $label = $hasChildren ? 'Parent' : '';
        $displayName = $prefix . ($label ? $label . ' - ' : '') . $menu->nama_menu;

        $options[$menu->id] = $displayName;
        echo "Added option: Key={$menu->id}, Value={$displayName}\n";

        $children = getMenuOptions($menu->id, $level + 1, $excludeId);
        // Use + operator instead of array_merge to preserve numeric keys
        $options = $options + $children;
    }

    return $options;
}

$parentOptions = getMenuOptions();

echo "\n=== Final Parent Options ===\n";
foreach ($parentOptions as $key => $value) {
    echo "Key: {$key}, Value: {$value}\n";
}

echo "\n=== Beranda Menu Details ===\n";
$beranda = MasterMenu::where('nama_menu', 'Beranda')->first();
if ($beranda) {
    echo "Beranda ID: {$beranda->id}\n";
    echo "Beranda Parent ID: " . ($beranda->parent_id ?? 'NULL') . "\n";
    echo "Is Beranda in parent options? " . (isset($parentOptions[$beranda->id]) ? 'YES' : 'NO') . "\n";
    if (isset($parentOptions[$beranda->id])) {
        echo "Beranda option value: {$parentOptions[$beranda->id]}\n";
    }
} else {
    echo "Beranda menu not found!\n";
}
