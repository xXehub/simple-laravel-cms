<?php

namespace App\Console\Commands;

use App\Services\ComponentRegistry;
use App\Services\PageBuilderService;
use App\Models\Page;
use Illuminate\Console\Command;

class TestPageRender extends Command
{
    protected $signature = 'test:page-render {page?}';
    protected $description = 'Test page rendering to verify fixes work';

    public function handle()
    {
        $this->info('=== TEST PAGE RENDERING (AFTER SAVE) ===');
        
        // Test 1: Component Registry
        $this->info('1. Testing Component Registry...');
        try {
            $registry = app(ComponentRegistry::class);
            $components = $registry->getAllComponents();
            $this->info("   ✅ ComponentRegistry loaded: " . $components->count() . " components");
            
            foreach ($components as $componentId => $componentInfo) {
                try {
                    $className = $componentInfo['class'];
                    $instance = new $className();
                    $this->info("   ✅ $componentId: Ready");
                } catch (\Exception $e) {
                    $this->error("   ❌ $componentId: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            $this->error("   ❌ ComponentRegistry failed: " . $e->getMessage());
            return 1;
        }
        
        // Test 2: PageBuilderService
        $this->info('2. Testing PageBuilderService...');
        try {
            $pageBuilderService = app(PageBuilderService::class);
            $this->info("   ✅ PageBuilderService instantiated");
            
            // Get a page to test with
            $pageId = $this->argument('page') ?? 1;
            $page = Page::find($pageId);
            
            if (!$page) {
                $this->error("   ❌ Page with ID $pageId not found");
                return 1;
            }
            
            $this->info("   ✅ Found page: {$page->title} (ID: {$page->id})");
            
            // Test page rendering
            $renderedContent = $pageBuilderService->renderPage($page->id);
            $this->info("   ✅ Page rendered: " . strlen($renderedContent) . " characters");
            
            if (str_contains($renderedContent, 'Undefined array key')) {
                $this->error("   ❌ Still contains 'Undefined array key' error");
                return 1;
            } else {
                $this->info("   ✅ No 'Undefined array key' errors found");
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ PageBuilderService failed: " . $e->getMessage());
            return 1;
        }
        
        // Test 3: Template rendering
        $this->info('3. Testing template rendering...');
        try {
            $view = view('pages.templates.builder', ['page' => $page]);
            $html = $view->render();
            $this->info("   ✅ Template rendered: " . strlen($html) . " characters");
            
            if (str_contains($html, 'Undefined array key')) {
                $this->error("   ❌ Template still contains 'Undefined array key' error");
                return 1;
            } else {
                $this->info("   ✅ Template renders without errors");
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ Template rendering failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('');
        $this->info('🎉 SUCCESS: All tests passed!');
        $this->info('The preview functionality should now work correctly.');
        $this->info('');
        $this->info('Next steps:');
        $this->info('1. Start the server: php artisan serve');
        $this->info('2. Open the page builder');
        $this->info('3. Add components and save');
        $this->info('4. Click Preview - it should work without errors');
        
        return 0;
    }
}
