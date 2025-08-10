<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\ComponentRegistry;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    protected ComponentRegistry $componentRegistry;

    public function __construct(ComponentRegistry $componentRegistry)
    {
        $this->componentRegistry = $componentRegistry;
    }

    /**
     * Validate component properties
     */
    public function validateProperties(Request $request): JsonResponse
    {
        try {
            $componentId = $request->input('component_id');
            $properties = $request->input('properties', []);
            
            $component = $this->componentRegistry->getComponent($componentId);
            
            if (!$component) {
                return response()->json([
                    'success' => false,
                    'message' => 'Component not found'
                ], 404);
            }

            $instance = new $component['class']();
            $validationRules = $instance->getValidationRules();
            
            $validator = Validator::make($properties, $validationRules);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Properties are valid',
                'data' => $properties
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error validating properties: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get property suggestions/autocomplete
     */
    public function getPropertySuggestions(Request $request): JsonResponse
    {
        try {
            $componentId = $request->input('component_id');
            $property = $request->input('property');
            $query = $request->input('query', '');
            
            $suggestions = $this->generatePropertySuggestions($componentId, $property, $query);
            
            return response()->json([
                'success' => true,
                'data' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting suggestions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get preset values for a property
     */
    public function getPropertyPresets(Request $request): JsonResponse
    {
        try {
            $componentId = $request->input('component_id');
            $property = $request->input('property');
            
            $presets = $this->getPresetsForProperty($componentId, $property);
            
            return response()->json([
                'success' => true,
                'data' => $presets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting presets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save property template
     */
    public function savePropertyTemplate(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'component_id' => 'required|string',
                'properties' => 'required|array',
                'description' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Here you would save to database
            // For now, just return success
            
            return response()->json([
                'success' => true,
                'message' => 'Property template saved successfully',
                'data' => [
                    'id' => uniqid(),
                    'name' => $request->input('name'),
                    'component_id' => $request->input('component_id'),
                    'properties' => $request->input('properties'),
                    'description' => $request->input('description')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get saved property templates
     */
    public function getPropertyTemplates(Request $request): JsonResponse
    {
        try {
            $componentId = $request->input('component_id');
            
            // Here you would fetch from database
            // For now, return empty array
            $templates = [];
            
            return response()->json([
                'success' => true,
                'data' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting templates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset properties to defaults
     */
    public function resetProperties(Request $request): JsonResponse
    {
        try {
            $componentId = $request->input('component_id');
            
            $component = $this->componentRegistry->getComponent($componentId);
            
            if (!$component) {
                return response()->json([
                    'success' => false,
                    'message' => 'Component not found'
                ], 404);
            }

            $instance = new $component['class']();
            $defaults = $instance->getDefaultProperties();
            
            return response()->json([
                'success' => true,
                'message' => 'Properties reset to defaults',
                'data' => $defaults
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error resetting properties: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate property suggestions based on context
     */
    private function generatePropertySuggestions(string $componentId, string $property, string $query): array
    {
        $suggestions = [];
        
        // Common suggestions based on property type/name
        switch ($property) {
            case 'color':
            case 'text_color':
            case 'background_color':
                $suggestions = [
                    'text-primary', 'text-secondary', 'text-success', 'text-danger',
                    'text-warning', 'text-info', 'text-light', 'text-dark', 'text-muted',
                    'bg-primary', 'bg-secondary', 'bg-success', 'bg-danger',
                    'bg-warning', 'bg-info', 'bg-light', 'bg-dark'
                ];
                break;
                
            case 'margin':
            case 'padding':
                $suggestions = [
                    'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5',
                    'mt-0', 'mt-1', 'mt-2', 'mt-3', 'mt-4', 'mt-5',
                    'mb-0', 'mb-1', 'mb-2', 'mb-3', 'mb-4', 'mb-5',
                    'mx-0', 'mx-1', 'mx-2', 'mx-3', 'mx-4', 'mx-5',
                    'my-0', 'my-1', 'my-2', 'my-3', 'my-4', 'my-5',
                    'p-0', 'p-1', 'p-2', 'p-3', 'p-4', 'p-5',
                    'pt-0', 'pt-1', 'pt-2', 'pt-3', 'pt-4', 'pt-5',
                    'pb-0', 'pb-1', 'pb-2', 'pb-3', 'pb-4', 'pb-5',
                    'px-0', 'px-1', 'px-2', 'px-3', 'px-4', 'px-5',
                    'py-0', 'py-1', 'py-2', 'py-3', 'py-4', 'py-5'
                ];
                break;
                
            case 'text_align':
            case 'alignment':
                $suggestions = [
                    'text-start', 'text-center', 'text-end', 'text-justify'
                ];
                break;
                
            case 'font_size':
                $suggestions = [
                    'fs-1', 'fs-2', 'fs-3', 'fs-4', 'fs-5', 'fs-6'
                ];
                break;
                
            case 'font_weight':
                $suggestions = [
                    'fw-light', 'fw-normal', 'fw-bold', 'fw-bolder'
                ];
                break;
                
            case 'icon':
                $suggestions = [
                    'heart', 'star', 'download', 'upload', 'edit', 'trash',
                    'plus', 'minus', 'check', 'x', 'arrow-right', 'arrow-left',
                    'home', 'user', 'settings', 'search', 'mail', 'phone'
                ];
                break;
        }
        
        // Filter suggestions based on query
        if (!empty($query)) {
            $suggestions = array_filter($suggestions, function($suggestion) use ($query) {
                return stripos($suggestion, $query) !== false;
            });
        }
        
        return array_values($suggestions);
    }

    /**
     * Get preset values for specific properties
     */
    private function getPresetsForProperty(string $componentId, string $property): array
    {
        $presets = [];
        
        switch ($property) {
            case 'variant':
                if (str_contains($componentId, 'button')) {
                    $presets = [
                        'Primary Button' => ['variant' => 'btn-primary'],
                        'Secondary Button' => ['variant' => 'btn-secondary'],
                        'Success Button' => ['variant' => 'btn-success'],
                        'Outline Primary' => ['variant' => 'btn-outline-primary'],
                    ];
                }
                break;
                
            case 'text_align':
                $presets = [
                    'Left Aligned' => ['text_align' => 'text-start'],
                    'Center Aligned' => ['text_align' => 'text-center'],
                    'Right Aligned' => ['text_align' => 'text-end'],
                ];
                break;
                
            case 'background':
                $presets = [
                    'Primary Background' => ['background' => 'bg-primary', 'color' => 'text-white'],
                    'Light Background' => ['background' => 'bg-light', 'color' => 'text-dark'],
                    'Dark Background' => ['background' => 'bg-dark', 'color' => 'text-white'],
                ];
                break;
        }
        
        return $presets;
    }
}
