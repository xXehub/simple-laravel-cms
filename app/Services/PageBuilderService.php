<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * PageBuilderService
 * 
 * Service untuk mengelola save dan load data page builder
 * Menggunakan JSON untuk menyimpan component data
 */
class PageBuilderService
{
    /**
     * Save page builder data
     *
     * @param int $pageId
     * @param array $componentData
     * @return bool
     */
    public function savePage(int $pageId, array $componentData): bool
    {
        try {
            $page = Page::findOrFail($pageId);
            
            // Validate component data structure
            $validatedData = $this->validateComponentData($componentData);
            
            // Save to page content field as JSON
            $page->content = json_encode($validatedData);
            $page->save();
            
            // Clear cache for this page
            $this->clearPageCache($pageId);
            
            Log::info("Page builder data saved successfully for page ID: {$pageId}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to save page builder data for page ID: {$pageId}. Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Load page builder data
     *
     * @param int $pageId
     * @return array
     */
    public function loadPage(int $pageId): array
    {
        try {
            $page = Page::findOrFail($pageId);
            
            if (empty($page->content)) {
                return $this->getDefaultPageStructure();
            }
            
            $content = json_decode($page->content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning("Invalid JSON in page content for page ID: {$pageId}");
                return $this->getDefaultPageStructure();
            }
            
            return $content;
        } catch (\Exception $e) {
            Log::error("Failed to load page builder data for page ID: {$pageId}. Error: " . $e->getMessage());
            return $this->getDefaultPageStructure();
        }
    }
    
    /**
     * Validate component data structure
     *
     * @param array $data
     * @return array
     */
    private function validateComponentData(array $data): array
    {
        $validated = [
            'components' => [],
            'metadata' => [
                'version' => '1.0',
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString()
            ]
        ];
        
        if (isset($data['components']) && is_array($data['components'])) {
            foreach ($data['components'] as $component) {
                if ($this->isValidComponent($component)) {
                    $validated['components'][] = $component;
                }
            }
        }
        
        // Preserve existing metadata if available
        if (isset($data['metadata']) && is_array($data['metadata'])) {
            $validated['metadata'] = array_merge($validated['metadata'], $data['metadata']);
            $validated['metadata']['updated_at'] = now()->toISOString();
        }
        
        return $validated;
    }
    
    /**
     * Validate individual component
     *
     * @param mixed $component
     * @return bool
     */
    private function isValidComponent($component): bool
    {
        if (!is_array($component)) {
            return false;
        }
        
        // Required fields for a component
        $requiredFields = ['id', 'component_id', 'properties'];
        
        foreach ($requiredFields as $field) {
            if (!isset($component[$field])) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get default page structure
     *
     * @return array
     */
    private function getDefaultPageStructure(): array
    {
        return [
            'components' => [],
            'metadata' => [
                'version' => '1.0',
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString()
            ]
        ];
    }
    
    /**
     * Render page for preview/display
     *
     * @param int $pageId
     * @return string
     */
    public function renderPage(int $pageId): string
    {
        try {
            $pageData = $this->loadPage($pageId);
            $html = '';
            
            // Safely access components array
            $components = $pageData['components'] ?? [];
            
            if (empty($components)) {
                return '<!-- No components to render -->';
            }
            
            foreach ($components as $componentData) {
                if (is_array($componentData)) {
                    $html .= $this->renderComponent($componentData);
                }
            }
            
            return $html;
        } catch (\Exception $e) {
            Log::error("Failed to render page {$pageId}: " . $e->getMessage());
            return '<!-- Error rendering page -->';
        }
    }
    
    /**
     * Render individual component (SIMPLIFIED VERSION)
     *
     * @param array $componentData
     * @return string
     */
    private function renderComponent(array $componentData): string
    {
        try {
            $componentId = $componentData['component_id'] ?? null;
            $properties = $componentData['properties'] ?? [];
            
            if (!$componentId) {
                return "<!-- Component ID missing -->";
            }
            
            // Get component registry
            $registry = app(ComponentRegistry::class);
            $componentInfo = $registry->getComponent($componentId);
            
            if (!$componentInfo) {
                return "<!-- Component not found: {$componentId} -->";
            }
            
            $componentClass = $componentInfo['class'];
            
            // Simple component creation - avoid complex error handling that causes stack issues
            try {
                // Create instance with empty array first
                $component = new $componentClass([]);
                
                // Get default properties
                $defaultProperties = [];
                if (method_exists($component, 'getDefaultProperties')) {
                    $defaultProperties = $component->getDefaultProperties();
                }
                
                // Merge properties
                $finalProperties = array_merge($defaultProperties, $properties);
                
                // Create final instance
                $component = new $componentClass($finalProperties);
                
            } catch (\Exception $e) {
                // Simple fallback
                return "<!-- Error creating component {$componentId}: {$e->getMessage()} -->";
            }
            
            // Simple rendering - avoid complex view handling
            try {
                $view = $component->render();
                
                // Convert to string safely
                if (is_string($view)) {
                    return $view;
                } elseif (is_object($view) && method_exists($view, '__toString')) {
                    return (string) $view;
                } else {
                    return "<!-- Component rendered but could not convert to string -->";
                }
                
            } catch (\Exception $e) {
                return "<!-- Error rendering component {$componentId}: {$e->getMessage()} -->";
            }
            
        } catch (\Exception $e) {
            return "<!-- Error processing component: {$e->getMessage()} -->";
        }
    }
    
    /**
     * Clear page cache
     *
     * @param int $pageId
     * @return void
     */
    private function clearPageCache(int $pageId): void
    {
        Cache::forget("page_builder_{$pageId}");
        Cache::forget("page_content_{$pageId}");
    }
    
    /**
     * Export page data for backup
     *
     * @param int $pageId
     * @return array
     */
    public function exportPage(int $pageId): array
    {
        $pageData = $this->loadPage($pageId);
        $page = Page::findOrFail($pageId);
        
        return [
            'page' => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'status' => $page->status,
            ],
            'builder_data' => $pageData,
            'exported_at' => now()->toISOString()
        ];
    }
    
    /**
     * Import page data from backup
     *
     * @param int $pageId
     * @param array $importData
     * @return bool
     */
    public function importPage(int $pageId, array $importData): bool
    {
        try {
            if (!isset($importData['builder_data'])) {
                throw new \InvalidArgumentException('Invalid import data structure');
            }
            
            return $this->savePage($pageId, $importData['builder_data']);
            
        } catch (\Exception $e) {
            Log::error("Failed to import page builder data for page ID: {$pageId}. Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get page statistics
     *
     * @param int $pageId
     * @return array
     */
    public function getPageStats(int $pageId): array
    {
        $pageData = $this->loadPage($pageId);
        
        $stats = [
            'total_components' => count($pageData['components']),
            'component_types' => [],
            'last_modified' => $pageData['metadata']['updated_at'] ?? null
        ];
        
        // Count component types
        foreach ($pageData['components'] as $component) {
            $type = $component['component_id'] ?? 'unknown';
            $stats['component_types'][$type] = ($stats['component_types'][$type] ?? 0) + 1;
        }
        
        return $stats;
    }
    
    /**
     * Public method to test component rendering (for testing purposes)
     *
     * @param array $componentData
     * @return string
     */
    public function testRenderComponent(array $componentData): string
    {
        return $this->renderComponent($componentData);
    }
}
