<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\PageBuilderService;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Page Builder Controller untuk sistem dinamis
 */
class PageBuilderController extends Controller
{
    protected PageBuilderService $pageBuilderService;

    public function __construct(PageBuilderService $pageBuilderService)
    {
        $this->pageBuilderService = $pageBuilderService;
    }

    /**
     * Halaman utama builder
     */
    public function index(Request $request)
    {
        $pageId = $request->get('page_id');
        $pageData = null;
        
        if ($pageId) {
            $page = Page::find($pageId);
            if ($page) {
                $pageData = $this->pageBuilderService->loadPage($pageId);
            }
        }
        
        return view('panel.pages.builder-modular', [
            'title' => 'Page Builder',
            'description' => 'Build and design pages with drag & drop components',
            'pageId' => $pageId,
            'pageData' => $pageData
        ]);
    }

    /**
     * Save page builder data
     */
    public function save(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'page_id' => 'required|integer|exists:pages,id',
                'components' => 'required|array',
                'components.*.id' => 'required|string',
                'components.*.component_id' => 'required|string',
                'components.*.properties' => 'sometimes|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $pageId = $request->input('page_id');
            $componentData = [
                'components' => $request->input('components', []),
                'metadata' => [
                    'saved_at' => now()->toISOString(),
                    'user_id' => auth()->id()
                ]
            ];

            $success = $this->pageBuilderService->savePage($pageId, $componentData);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Page saved successfully',
                    'data' => [
                        'page_id' => $pageId,
                        'saved_at' => now()->toISOString(),
                        'component_count' => count($componentData['components'])
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save page'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('PageBuilder save error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the page',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Load page builder data
     */
    public function load(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'page_id' => 'required|integer|exists:pages,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $pageId = $request->input('page_id');
            $pageData = $this->pageBuilderService->loadPage($pageId);

            return response()->json([
                'success' => true,
                'data' => $pageData
            ]);

        } catch (\Exception $e) {
            Log::error('PageBuilder load error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load page data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Preview page
     */
    public function preview(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'page_id' => 'required|integer|exists:pages,id',
                'components' => 'sometimes|array' // Allow saving components before preview
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $pageId = $request->input('page_id');
            $page = Page::findOrFail($pageId);
            
            // If components are provided, save them first before preview
            if ($request->has('components')) {
                $componentData = [
                    'components' => $request->input('components', []),
                    'metadata' => [
                        'saved_at' => now()->toISOString(),
                        'user_id' => auth()->id(),
                        'type' => 'preview_save'
                    ]
                ];
                
                $this->pageBuilderService->savePage($pageId, $componentData);
            }
            
            // Generate preview URL using the dynamic route
            $previewUrl = url('/' . $page->slug);

            return response()->json([
                'success' => true,
                'data' => [
                    'preview_url' => $previewUrl,
                    'page_title' => $page->title,
                    'page_slug' => $page->slug
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('PageBuilder preview error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate preview',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get page statistics
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'page_id' => 'required|integer|exists:pages,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $pageId = $request->input('page_id');
            $stats = $this->pageBuilderService->getPageStats($pageId);

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('PageBuilder stats error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get page statistics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Export page data
     */
    public function export(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'page_id' => 'required|integer|exists:pages,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $pageId = $request->input('page_id');
            $exportData = $this->pageBuilderService->exportPage($pageId);

            return response()->json([
                'success' => true,
                'data' => $exportData
            ]);

        } catch (\Exception $e) {
            Log::error('PageBuilder export error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to export page data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
