<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\MasterMenu;
use App\Http\Requests\Panel\Page\StorePageRequest;
use App\Http\Requests\Panel\Page\UpdatePageRequest;
use App\Helpers\ResponseHelper;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:view-pages')->only(['index', 'datatable']);
        $this->middleware('permission:create-pages')->only(['create', 'store']);
        $this->middleware('permission:update-pages')->only(['edit', 'update']);
        $this->middleware('permission:delete-pages')->only(['destroy', 'bulkDestroy']);
    }

    /**
     * Get page by ID from request
     */
    protected function getPageById($request)
    {
        return Page::findOrFail($request->route('id') ?? $request->input('id'));
    }

    /**
     * Display pages listing with dynamic view from database
     */
    public function index(Request $request)
    {
        // Handle DataTable AJAX requests
        if ($request->ajax() && $request->has('draw')) {
            return $this->datatable($request);
        }

        // Get current menu from request
        $currentSlug = $request->route()->uri ?? 'panel/pages';
        $menu = MasterMenu::where('slug', $currentSlug)->first();

        // Get available templates for forms
        $templates = $this->getAvailableTemplates();
        
        // Get pages for the grid view
        $pages = Page::orderBy('created_at', 'desc')->get();

        // Get dynamic view path from database
        $viewPath = $menu?->getDynamicViewPath();

        return view($viewPath, compact('menu', 'templates', 'pages'));
    }

    /**
     * Show create page form with dynamic view
     */
    public function create(Request $request)
    {
        // Get current menu for dynamic view resolution
        $currentSlug = str_replace('/create', '', $request->route()->uri ?? 'panel/pages');
        $menu = MasterMenu::where('slug', $currentSlug)->first();

        // Use create view path or fallback
        $viewPath = 'panel.pages.create'; // Static for forms

        return view($viewPath, compact('menu'));
    }

    /**
     * Store new page
     */
    public function store(StorePageRequest $request)
    {
        $data = $this->preparePageData($request);
        $page = Page::create($data);

        return $this->handleResponse($request, 'Page created successfully', $page);
    }

    /**
     * Get page data for editing (AJAX support)
     */
    public function edit(Request $request, $id)
    {
        try {
            $page = Page::findOrFail($id);

            // Always return JSON data for AJAX requests (this should be modal-based)
            return response()->json([
                'success' => true,
                'page' => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'content' => $page->content,
                    'template' => $page->template,
                    'is_published' => $page->is_published,
                    'meta_title' => $page->meta_title,
                    'meta_description' => $page->meta_description,
                    'sort_order' => $page->sort_order,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update page
     */
    public function update(UpdatePageRequest $request, $id)
    {
        try {
            $page = Page::findOrFail($id);
            $data = $this->preparePageData($request);
            $page->update($data);

            return $this->handleResponse($request, 'Page updated successfully');
        } catch (\Exception $e) {
            return $this->handleError($request, 'Error updating page: ' . $e->getMessage());
        }
    }

    /**
     * Delete page
     */
    public function destroy(Request $request, $id)
    {
        try {
            $page = Page::findOrFail($id);
            $page->delete();
            
            return $this->handleResponse($request, 'Page deleted successfully');
        } catch (\Exception $e) {
            return $this->handleError($request, 'Error deleting page: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate existing page
     */
    public function duplicate(Request $request, $id)
    {
        try {
            $originalPage = Page::findOrFail($id);
            $newPage = $this->createDuplicatePage($originalPage);
            
            return $this->handleResponse($request, 'Page duplicated successfully', $newPage);
        } catch (\Exception $e) {
            return $this->handleError($request, 'Error duplicating page: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete pages
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'page_ids' => 'required|array',
            'page_ids.*' => 'exists:pages,id'
        ]);

        $deletedCount = Page::whereIn('id', $request->page_ids)->delete();

        $message = "Successfully deleted {$deletedCount} page(s)";
        $type = 'success';

        if ($deletedCount === 0) {
            $message = 'No pages were deleted';
            $type = 'warning';
        }

        return ResponseHelper::handle($request, 'panel.pages', $message, [
            'deleted_count' => $deletedCount
        ], $type);
    }

    /**
     * Server-side datatable for pages
     */
    public function datatable(Request $request)
    {
        $query = Page::query();

        // Add search if provided  
        if ($search = $request->get('search')['value'] ?? null) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%")
                    ->orWhere('meta_title', 'LIKE', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('checkbox', function ($page) {
                return '<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select page" value="' . $page->id . '"/>';
            })
            ->addColumn('page_type', function ($page) {
                $isBuilder = $this->isBuilderPage($page);
                $type = $isBuilder ? 'Builder' : 'Template';
                $class = $isBuilder ? 'indigo text-indigo-fg' : 'orange text-orange-fg';
                $icon = $isBuilder ? 'ti ti-puzzle' : 'ti ti-template';
                
                return '<span class="badge bg-' . $class . '"><i class="' . $icon . ' me-1"></i>' . $type . '</span>';
            })
            ->addColumn('status_badge', function ($page) {
                $status = $page->is_published ? 'published' : 'draft';
                $class = $page->is_published ? 'green text-green-fg' : 'yellow text-yellow-fg';
                return '<span class="badge bg-' . $class . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('template_info', function ($page) {
                if (!empty($page->template)) {
                    return '<small class="text-muted">' . ucwords(str_replace(['-', '_'], ' ', $page->template)) . '</small>';
                }
                return '<small class="text-muted">No template</small>';
            })
            ->addColumn('action', function ($page) {
                return view('components.modals.pages.action', [
                    'page' => $this->formatPageForAction($page)
                ])->render();
            })
            ->editColumn('created_at', function ($page) {
                return $page->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['checkbox', 'page_type', 'status_badge', 'template_info', 'action'])
            ->make(true);
    }

    /**
     * Upload new page template
     */
    public function uploadTemplate(Request $request)
    {
        $request->validate([
            'template_file' => 'required|file|max:2048',
            'overwrite_existing' => 'sometimes|boolean'
        ]);

        // Additional validation for file extension
        $uploadedFile = $request->file('template_file');
        $fileName = $uploadedFile->getClientOriginalName();
        
        if (!str_ends_with($fileName, '.blade.php') && !str_ends_with($fileName, '.php')) {
            return response()->json([
                'success' => false,
                'message' => 'Only .blade.php or .php files are allowed.'
            ], 422);
        }

        // Get uploaded file
        $uploadedFile = $request->file('template_file');
        $originalName = $uploadedFile->getClientOriginalName();
        
        // Extract template name from filename
        $templateName = pathinfo($originalName, PATHINFO_FILENAME);
        $templateName = str_replace('.blade', '', $templateName); // Remove .blade if exists
        
        // Create safe filename
        $safeFileName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $templateName));
        $safeFileName = preg_replace('/-+/', '-', $safeFileName); // Remove multiple dashes
        $safeFileName = trim($safeFileName, '-'); // Remove leading/trailing dashes
        
        $templatePath = resource_path("views/pages/templates/{$safeFileName}.blade.php");

        // Check if template already exists and overwrite is not checked
        $overwriteExisting = $request->boolean('overwrite_existing', false);
        if (file_exists($templatePath) && !$overwriteExisting) {
            return response()->json([
                'success' => false,
                'message' => 'Template already exists! Check "Overwrite existing template" to replace it.'
            ], 409); // Conflict status
        }

        // Get uploaded file content
        $fileContent = file_get_contents($uploadedFile->getPathname());
        
        // Basic validation for Blade template
        if (!$this->isValidBladeTemplate($fileContent)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Blade template. Please ensure it contains proper Blade syntax and uses $page variable for content.'
            ], 400);
        }

        // Ensure templates directory exists
        $templatesDir = resource_path('views/pages/templates');
        if (!is_dir($templatesDir)) {
            mkdir($templatesDir, 0755, true);
        }

        // Save the template file
        try {
            file_put_contents($templatePath, $fileContent);

            // Log template upload
            Log::info('Template uploaded', [
                'user_id' => auth()->id(),
                'template_name' => $safeFileName,
                'original_name' => $originalName,
                'file_size' => $uploadedFile->getSize()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Template '{$safeFileName}' uploaded successfully! It's now available in the template dropdown."
            ]);

        } catch (\Exception $e) {
            Log::error('Template upload failed', [
                'user_id' => auth()->id(),
                'template_name' => $safeFileName,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available templates for dropdown
     */
    public function getAvailableTemplates(Request $request = null)
    {
        $templatesDir = resource_path('views/pages/templates');
        $templates = [];

        if (is_dir($templatesDir)) {
            $files = glob($templatesDir . '/*.blade.php');
            
            foreach ($files as $file) {
                $filename = basename($file, '.blade.php');
                $displayName = ucwords(str_replace(['-', '_'], ' ', $filename));
                
                $templates[] = [
                    'value' => $filename,
                    'label' => $displayName,
                    'file' => $file
                ];
            }
        }

        // Sort templates alphabetically
        usort($templates, function($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        // Return JSON if this is an AJAX request
        if ($request && ($request->expectsJson() || $request->ajax())) {
            return response()->json($templates);
        }

        return $templates;
    }

    /**
     * Get available templates as JSON (for AJAX requests)
     */
    public function getAvailableTemplatesJson()
    {
        $templates = $this->getAvailableTemplates();
        return response()->json($templates);
    }

    /**
     * Delete template
     */
    public function deleteTemplate(Request $request)
    {
        $request->validate([
            'template_slug' => 'required|string'
        ]);

        $templateSlug = $request->template_slug;
        $templatePath = resource_path("views/pages/templates/{$templateSlug}.blade.php");

        // Prevent deletion of core templates
        $coreTemplates = ['default', 'about', 'kontak', 'layanan'];
        if (in_array($templateSlug, $coreTemplates)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete core templates.'
            ], 400);
        }

        if (!file_exists($templatePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found.'
            ], 404);
        }

        try {
            unlink($templatePath);

            Log::info('Template deleted', [
                'user_id' => auth()->id(),
                'template_slug' => $templateSlug
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Template deletion failed', [
                'user_id' => auth()->id(),
                'template_slug' => $templateSlug,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show page builder interface
     */
    public function builder(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        return view('panel.pages.builder', compact('page'));
    }

    /**
     * Create template-based page
     */
    public function createTemplate(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:pages,slug',
            'template' => 'required|string',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean'
        ]);

        $data = $this->prepareTemplatePageData($request);
        $page = Page::create($data);

        return $this->handleResponse($request, 'Template-based page created successfully!', $page);
    }

    /**
     * Create builder-based page
     */
    public function createBuilder(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:pages,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean'
        ]);

        $data = $this->prepareBuilderPageData($request);
        $page = Page::create($data);

        return $this->handleBuilderResponse($request, $page);
    }

    /**
     * Helper Methods
     */

    /**
     * Prepare page data from request
     */
    private function preparePageData(Request $request)
    {
        $data = $request->validated();
        
        // Set default values
        $data['is_published'] = $request->boolean('is_published', false);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        // Handle content
        if (empty($data['content'])) {
            $data['content'] = $this->hasBuilderData($request) 
                ? '<p>This page uses the page builder.</p>' 
                : '<p>Welcome to our new page!</p>';
        }
        
        // Handle builder data
        $data['builder_data'] = $this->hasBuilderData($request) 
            ? $request->input('builder_data') 
            : null;

        return $data;
    }

    /**
     * Prepare template page data
     */
    private function prepareTemplatePageData(Request $request)
    {
        $data = $request->all();
        
        $data['slug'] = $data['slug'] ?: \Str::slug($data['title']);
        $data['content'] = $data['content'] ?: '<p>Welcome to our new page!</p>';
        $data['is_published'] = $request->boolean('is_published', false);
        $data['sort_order'] = 0;
        $data['builder_data'] = null; // Template pages don't use builder
        
        return $data;
    }

    /**
     * Prepare builder page data
     */
    private function prepareBuilderPageData(Request $request)
    {
        $data = $request->all();
        
        $data['slug'] = $data['slug'] ?: \Str::slug($data['title']);
        $data['content'] = ''; // Empty content for builder pages
        $data['template'] = null; // Builder pages don't use templates
        $data['is_published'] = $request->boolean('is_published', false);
        $data['sort_order'] = 0;
        $data['builder_data'] = json_encode([
            'type' => 'builder',
            'components' => [],
            'created_at' => now()->toISOString()
        ]);
        
        return $data;
    }

    /**
     * Create duplicate page
     */
    private function createDuplicatePage($originalPage)
    {
        $newPage = $originalPage->replicate();
        $newPage->title = $originalPage->title . ' (Copy)';
        $newPage->slug = $originalPage->slug . '-copy-' . time();
        $newPage->is_published = false; // Set as draft by default
        $newPage->save();
        
        return $newPage;
    }

    /**
     * Cek apakah page menggunakan builder
     */
    private function isBuilderPage($page)
    {
        return !empty($page->builder_data) && 
               $page->builder_data !== '[]' && 
               $page->builder_data !== 'null';
    }

    /**
     * Format page data for action component
     */
    private function formatPageForAction($page)
    {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'is_published' => $page->is_published,
            'builder_data' => $page->builder_data
        ];
    }

    /**
     * Check if request has builder data
     */
    private function hasBuilderData(Request $request)
    {
        return $request->has('builder_data') && !empty($request->input('builder_data'));
    }

    /**
     * Handle success response
     */
    private function handleResponse(Request $request, string $message, $data = null)
    {
        if ($request->expectsJson()) {
            $response = ['success' => true, 'message' => $message];
            if ($data) $response['page'] = $data;
            if ($request->boolean('open_after_create') && $data) {
                $response['redirect_url'] = $this->getBuilderUrl($data->id);
            }
            return response()->json($response);
        }
        return ResponseHelper::redirect('panel.pages', $message);
    }

    /**
     * Handle builder response
     */
    private function handleBuilderResponse(Request $request, $page)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Builder page created successfully!',
                'page' => $page,
                'redirect_url' => $this->getBuilderUrl($page->id)
            ]);
        }
        return redirect()->route('panel.pages')->with('success', 'Builder page created successfully!');
    }

    /**
     * Generate builder URL
     */
    private function getBuilderUrl($pageId)
    {
        return route('panel.pages.builder', $pageId);
    }

    /**
     * Handle error response
     */
    private function handleError(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], 500);
        }
        return ResponseHelper::redirect('panel.pages', $message, 'error');
    }

    /**
     * Basic validation for Blade template
     */
    private function isValidBladeTemplate($content)
    {
        // Remove PHP comments and blank lines for better validation
        $cleanContent = preg_replace('/\/\*.*?\*\/|\/\/.*?$|#.*?$/m', '', $content);
        $cleanContent = preg_replace('/^\s*$/m', '', $cleanContent);
        
        // Check for basic HTML structure (minimum requirement)
        $hasHtmlTags = strpos($cleanContent, '<') !== false && strpos($cleanContent, '>') !== false;
        
        // Check for $page variable usage (can be anywhere in the content)
        $hasPageVariable = strpos($content, '$page') !== false;
        
        // Check for basic Blade or PHP structure (more flexible)
        $hasBladeOrPhp = strpos($content, '@') !== false || 
                        strpos($content, '<?php') !== false || 
                        strpos($content, '{{') !== false || 
                        strpos($content, '{!!') !== false;
        
        // Template should have HTML tags and either use $page variable or be a basic HTML template
        return $hasHtmlTags && ($hasPageVariable || $hasBladeOrPhp || strlen(trim($cleanContent)) > 50);
    }
}
