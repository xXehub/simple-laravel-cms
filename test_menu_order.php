<?php
/**
 * Simple test script for menu ordering functionality
 * Run this script from Laravel project root: php test_menu_order.php
 */

require_once 'vendor/autoload.php';

use App\Models\MasterMenu;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Menu Order Functionality\n";
echo "================================\n\n";

// Display current menu structure
function displayMenuStructure() {
    echo "Current Menu Structure:\n";
    echo "-----------------------\n";
    
    $menus = MasterMenu::whereNull('parent_id')
        ->orderBy('urutan', 'asc')
        ->get();
    
    foreach ($menus as $menu) {
        echo "├─ [{$menu->urutan}] {$menu->nama_menu} (ID: {$menu->id})\n";
        
        $children = $menu->children()->orderBy('urutan', 'asc')->get();
        foreach ($children as $child) {
            echo "│  └─ [{$child->urutan}] {$child->nama_menu} (ID: {$child->id})\n";
        }
    }
    echo "\n";
}

// Create test data if not exists
function createTestData() {
    echo "Creating test menu data...\n";
    
    // Clear existing test data
    MasterMenu::where('nama_menu', 'like', 'Test%')->delete();
    
    // Create parent menus
    $parent1 = MasterMenu::create([
        'nama_menu' => 'Test Parent 1',
        'slug' => 'test-parent-1',
        'urutan' => 1,
        'is_active' => true
    ]);
    
    $parent2 = MasterMenu::create([
        'nama_menu' => 'Test Parent 2', 
        'slug' => 'test-parent-2',
        'urutan' => 3,
        'is_active' => true
    ]);
    
    // Create children for parent 1
    MasterMenu::create([
        'nama_menu' => 'Test Child 1.1',
        'slug' => 'test-child-1-1',
        'parent_id' => $parent1->id,
        'urutan' => 2,
        'is_active' => true
    ]);
    
    // Create children for parent 2
    MasterMenu::create([
        'nama_menu' => 'Test Child 2.1',
        'slug' => 'test-child-2-1', 
        'parent_id' => $parent2->id,
        'urutan' => 4,
        'is_active' => true
    ]);
    
    MasterMenu::create([
        'nama_menu' => 'Test Child 2.2',
        'slug' => 'test-child-2-2',
        'parent_id' => $parent2->id,
        'urutan' => 5,
        'is_active' => true
    ]);
    
    echo "Test data created successfully!\n\n";
}

// Test the move order functionality
function testMoveOrder($menuId, $direction) {
    echo "Testing move {$direction} for menu ID {$menuId}...\n";
    
    $menu = MasterMenu::findOrFail($menuId);
    echo "Moving: {$menu->nama_menu}\n";
    
    // Simulate the controller logic
    try {
        $controller = new \App\Http\Controllers\Panel\MenuController();
        
        // Create a mock request
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'menu_id' => $menuId,
            'direction' => $direction
        ]);
        
        $response = $controller->moveOrder($request);
        $data = json_decode($response->getContent(), true);
        
        if ($data['success']) {
            echo "✓ Success: {$data['message']}\n";
        } else {
            echo "✗ Failed: {$data['message']}\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Error: {$e->getMessage()}\n";
    }
    
    echo "\n";
}

// Run tests
try {
    displayMenuStructure();
    
    // Create test data
    createTestData();
    displayMenuStructure();
    
    // Get test menu IDs
    $parent1 = MasterMenu::where('nama_menu', 'Test Parent 1')->first();
    $parent2 = MasterMenu::where('nama_menu', 'Test Parent 2')->first();
    $child21 = MasterMenu::where('nama_menu', 'Test Child 2.1')->first();
    
    // Test 1: Move parent 2 up (should move with all children)
    echo "TEST 1: Move Parent 2 up\n";
    testMoveOrder($parent2->id, 'up');
    displayMenuStructure();
    
    // Test 2: Move child 2.1 down within its parent group
    echo "TEST 2: Move Child 2.1 down\n";
    testMoveOrder($child21->id, 'down');
    displayMenuStructure();
    
    // Test 3: Try to move parent 1 up (should fail - already at top)
    echo "TEST 3: Try to move Parent 1 up (should fail)\n";
    testMoveOrder($parent1->id, 'up');
    displayMenuStructure();
    
    echo "All tests completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
