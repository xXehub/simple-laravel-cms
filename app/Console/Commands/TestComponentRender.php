<?php

namespace App\Console\Commands;

use App\Services\ComponentRegistry;
use App\Services\PageBuilderService;
use App\Models\Page;
use Illuminate\Console\Command;

class TestComponentRender extends Command
{
    protected $signature = 'test:component-render {page?}';
    protected $description = 'Test if components render correctly (not just JSON)';

    public function handle()
    {
        $this->info('=== TEST COMPONENT RENDERING ===');
        
        // Get page
        $pageId = $this->argument('page') ?? 1;
        $page = Page::find($pageId);
        
        if (!$page) {
            $this->error("Page with ID $pageId not found");
            return 1;
        }
        
        $this->info("Testing page: {$page->title} (ID: {$page->id})");
        
        // Check if page has content
        if (empty($page->content)) {
            $this->warn("Page has no content. Add some components in the page builder first.");
            return 0;
        }
        
        // Check if content is JSON
        $content = json_decode($page->content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Page content is not valid JSON");
            return 1;
        }
        
        $this->info("Page content structure:");
        $this->info("- Components: " . count($content['components'] ?? []));
        $this->info("- Version: " . ($content['metadata']['version'] ?? 'unknown'));
        
        // Test PageBuilderService rendering
        $pageBuilderService = app(PageBuilderService::class);
        $renderedContent = $pageBuilderService->renderPage($page->id);
        
        $this->info("Rendered content:");
        $this->info("- Length: " . strlen($renderedContent) . " characters");
        
        // Check if it's just showing JSON or actual HTML
        if (str_contains($renderedContent, '{"components"')) {
            $this->error("❌ Still showing raw JSON instead of rendered components!");
            $this->info("Content preview: " . substr($renderedContent, 0, 200) . "...");
            return 1;
        }
        
        if (str_contains($renderedContent, 'No components to render')) {
            $this->warn("⚠️  No components found to render (page is empty)");
            return 0;
        }
        
        if (str_contains($renderedContent, 'Error rendering')) {
            $this->error("❌ Error found in rendered content");
            $this->info("Content preview: " . substr($renderedContent, 0, 200) . "...");
            return 1;
        }
        
        // Check if it contains HTML tags (sign of actual rendering)
        if (preg_match('/<[^>]+>/', $renderedContent)) {
            $this->info("✅ Content contains HTML tags - components are being rendered!");
            
            // Show preview of rendered content
            $preview = strip_tags($renderedContent);
            $preview = trim(preg_replace('/\s+/', ' ', $preview));
            $this->info("Content preview: " . substr($preview, 0, 150) . "...");
            
            $this->info("🎉 SUCCESS: Components are being rendered correctly!");
            return 0;
        } else {
            $this->warn("⚠️  No HTML tags found in rendered content");
            $this->info("Content: " . substr($renderedContent, 0, 200) . "...");
            return 1;
        }
    }
}
