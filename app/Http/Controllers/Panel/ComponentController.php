<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\ComponentRegistry;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ComponentController extends Controller
{
    protected ComponentRegistry $componentRegistry;

    public function __construct(ComponentRegistry $componentRegistry)
    {
        $this->componentRegistry = $componentRegistry;
    }

    /**
     * Get all available components organized by category (untuk API)
     */
    public function components(): JsonResponse
    {
        try {
            $components = $this->componentRegistry->getAllComponents();
            $organized = $this->organizeComponentsByCategory($components->toArray());
            
            return response()->json([
                'success' => true,
                'data' => $organized,
                'stats' => $this->componentRegistry->getStats()
            ]);
        } catch (\Exception $e) {
            \Log::error('ComponentController::components error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading components: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all available components organized by category (alias untuk routing dinamis)
     */
    public function index(): JsonResponse
    {
        return $this->components();
    }

    /**
     * Get components by specific category
     */
    public function getByCategory(string $category): JsonResponse
    {
        $components = $this->componentRegistry->getComponentsByCategory($category);
        
        return response()->json([
            'success' => true,
            'data' => $components,
            'category' => $category
        ]);
    }

    /**
     * Get component metadata and property definitions
     */
    public function getComponentInfo(Request $request, string $id = null): JsonResponse
    {
        try {
            // Get component ID from route parameter, request parameter, or query string
            $componentId = $id ?? $request->input('component_id') ?? $request->route('id') ?? $request->query('component_id');
            
            if (!$componentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Component ID is required'
                ], 400);
            }
            
            $component = $this->componentRegistry->getComponent($componentId);
            
            if (!$component) {
                return response()->json([
                    'success' => false,
                    'message' => 'Component not found: ' . $componentId
                ], 404);
            }

            $instance = new $component['class']([]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $componentId,
                    'class' => $component['class'],
                    'metadata' => $instance->getMetadata(),
                    'properties' => $instance->getPropertyDefinitions(),
                    'validation' => $instance->getValidationRules(),
                    'defaults' => $instance->getDefaultProperties(),
                    'enabled' => $component['enabled']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('ComponentController::getComponentInfo error: ' . $e->getMessage(), [
                'component_id' => $componentId ?? 'unknown',
                'request_url' => $request->fullUrl(),
                'route_params' => $request->route()->parameters ?? [],
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting component info: ' . $e->getMessage(),
                'debug' => [
                    'component_id' => $componentId ?? 'unknown',
                    'request_url' => $request->fullUrl(),
                    'available_components' => array_keys($this->componentRegistry->getAllComponents()->toArray())
                ]
            ], 500);
        }
    }

    /**
     * Render component with provided properties
     */
    public function renderComponent(Request $request): JsonResponse
    {
        try {
            $componentId = $request->input('component_id');
            $properties = $request->input('properties', []);
            
            if (!$componentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Component ID is required'
                ], 400);
            }
            
            $component = $this->componentRegistry->getComponent($componentId);
            
            if (!$component) {
                return response()->json([
                    'success' => false,
                    'message' => 'Component not found: ' . $componentId
                ], 404);
            }

            // Create component instance with properties
            $componentClass = $component['class'];
            
            // Safely create instance - some components might not expect properties in constructor
            try {
                $instance = new $componentClass($properties);
            } catch (\Exception $e) {
                // Fallback: try without properties, then set them afterwards
                $instance = new $componentClass();
                if (method_exists($instance, 'setProperties')) {
                    $instance->setProperties($properties);
                }
            }
            
            // Render the component
            $html = $instance->render()->render();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'html' => $html,
                    'component_id' => $componentId,
                    'properties' => $properties
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Component render error: ' . $e->getMessage(), [
                'component_id' => $request->input('component_id'),
                'properties' => $request->input('properties'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error rendering component: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Render component with provided properties (alias for renderComponent untuk sistem dinamis)
     */
    public function render(Request $request): JsonResponse
    {
        return $this->renderComponent($request);
    }

    /**
     * Search components by name or description
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $category = $request->input('category', null);
        
        $components = $this->componentRegistry->searchComponents($query, $category);
        
        return response()->json([
            'success' => true,
            'data' => $components,
            'query' => $query,
            'category' => $category
        ]);
    }

    /**
     * Get component categories
     */
    public function getCategories(): JsonResponse
    {
        $categories = $this->componentRegistry->getCategories();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Organize components by category for sidebar display
     */
    private function organizeComponentsByCategory(array $components): array
    {
        $organized = [];
        
        foreach ($components as $id => $component) {
            try {
                // Create instance with default properties to avoid constructor errors
                $className = $component['class'];
                $tempInstance = new $className();
                $defaultProperties = $tempInstance->getDefaultProperties();
                $instance = new $className($defaultProperties);
                
                $metadata = $instance->getMetadata();
                $category = $metadata['category'] ?? 'other';
                
                if (!isset($organized[$category])) {
                    $organized[$category] = [
                        'name' => ucfirst($category),
                        'components' => []
                    ];
                }
                
                $organized[$category]['components'][$id] = [
                    'id' => $id,
                    'name' => $metadata['name'] ?? $id,
                    'description' => $metadata['description'] ?? '',
                    'icon' => $metadata['icon'] ?? 'ti ti-square',
                    'enabled' => $component['enabled'],
                    'class' => $component['class']
                ];
            } catch (\Exception $e) {
                // Skip components that can't be instantiated
                \Log::warning("Failed to instantiate component {$id}: " . $e->getMessage());
                continue;
            }
        }
        
        // Sort categories
        ksort($organized);
        
        return $organized;
    }
}
