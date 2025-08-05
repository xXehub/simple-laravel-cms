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
        $data = $request->validated();
        
        // Handle boolean fields that exist in database
        // For checkboxes, they're only sent when checked
        $data['is_published'] = $request->has('is_published') ? true : false;
        
        // Set defaults for existing fields only
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        // Ensure content is never null - set default based on page type
        if (empty($data['content'])) {
            if ($request->has('builder_data') && !empty($request->input('builder_data'))) {
                // For builder pages, set minimal content
                $data['content'] = '<p>This page uses the page builder.</p>';
            } else {
                // For template pages, set default content
                $data['content'] = '<p>Welcome to our new page!</p>';
            }
        }
        
        // Handle builder data if present
        if ($request->has('builder_data')) {
            $data['builder_data'] = $request->input('builder_data');
        }

        $page = Page::create($data);

        // If this is an AJAX request, return JSON
        if ($request->expectsJson() || $request->ajax()) {
            $response = [
                'success' => true,
                'message' => 'Page created successfully',
                'page' => $page
            ];
            
            // If user wants to open builder after creation
            if ($request->has('open_after_create') && $request->open_after_create) {
                $response['redirect_url'] = url("panel/pages/{$page->id}/builder");
            }
            
            return response()->json($response);
        }

        return ResponseHelper::redirect('panel.pages', 'Page created successfully');
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

            $data = $request->validated();
            
            // Handle boolean fields that exist in database
            // For checkboxes, they're only sent when checked
            $data['is_published'] = $request->has('is_published') ? true : false;
            
            // Set defaults for existing fields only
            $data['sort_order'] = $data['sort_order'] ?? 0;
            
            // Handle builder data if present
            if ($request->has('builder_data')) {
                $data['builder_data'] = $request->input('builder_data');
            }

            $page->update($data);

            // If this is an AJAX request (from modal), return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Page updated successfully'
                ]);
            }

            return ResponseHelper::redirect('panel.pages', 'Page updated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating page: ' . $e->getMessage()
                ], 500);
            }
            
            return ResponseHelper::redirect('panel.pages', 'Error updating page: ' . $e->getMessage(), 'error');
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
            
            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Page deleted successfully'
                ]);
            }
            
            return ResponseHelper::redirect('panel.pages', 'Page deleted successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting page: ' . $e->getMessage()
                ], 500);
            }
            
            return ResponseHelper::redirect('panel.pages', 'Error deleting page: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Duplicate existing page
     */
    public function duplicate(Request $request, $id)
    {
        try {
            $originalPage = Page::findOrFail($id);
            
            // Create new page with copied data
            $newPage = $originalPage->replicate();
            $newPage->title = $originalPage->title . ' (Copy)';
            $newPage->slug = $originalPage->slug . '-copy-' . time();
            $newPage->is_published = false; // Set as draft by default
            $newPage->save();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Page duplicated successfully',
                    'page' => $newPage
                ]);
            }
            
            return ResponseHelper::redirect('panel.pages', 'Page duplicated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error duplicating page: ' . $e->getMessage()
                ], 500);
            }
            
            return ResponseHelper::redirect('panel.pages', 'Error duplicating page: ' . $e->getMessage(), 'error');
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
                $isBuilder = !empty($page->builder_data) && $page->builder_data !== '[]';
                $type = $isBuilder ? 'Builder' : 'Template';
                $class = $isBuilder ? 'info' : 'secondary';
                $icon = $isBuilder ? 'ti ti-puzzle' : 'ti ti-template';
                return '<span class="badge bg-' . $class . '"><i class="' . $icon . ' me-1"></i>' . $type . '</span>';
            })
            ->addColumn('status_badge', function ($page) {
                $status = $page->is_published ? 'published' : 'draft';
                $class = $page->is_published ? 'success' : 'warning';
                return '<span class="badge bg-' . $class . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('template_info', function ($page) {
                if (!empty($page->template)) {
                    return '<small class="text-muted">' . ucwords(str_replace(['-', '_'], ' ', $page->template)) . '</small>';
                }
                return '<small class="text-muted">No template</small>';
            })
            ->addColumn('action', function ($page) {
                // Render action buttons using the component
                return view('components.modals.pages.action', [
                    'page' => [
                        'id' => $page->id,
                        'title' => $page->title,
                        'slug' => $page->slug,
                        'is_published' => $page->is_published,
                        'builder_data' => $page->builder_data
                    ]
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
        
        // Get available templates for settings modal
        $templates = $this->getAvailableTemplates();
        
        return view('panel.pages.builder-new', compact('page', 'templates'));
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

        $data = $request->all();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['title']);
        }
        
        // Ensure content is not null
        if (empty($data['content'])) {
            $data['content'] = '<p>Welcome to our new page!</p>';
        }
        
        // Set defaults
        $data['is_published'] = $request->boolean('is_published', false);
        $data['sort_order'] = 0;
        $data['builder_data'] = null; // Template-based pages don't use builder data

        $page = Page::create($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Template-based page created successfully!',
                'page' => $page
            ]);
        }

        return redirect()->route('panel.pages')->with('success', 'Template-based page created successfully!');
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

        $data = $request->all();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['title']);
        }
        
        // Set defaults for builder pages
        $data['content'] = '<p>This page uses the page builder.</p>';
        $data['template'] = null; // Builder pages don't use templates
        $data['is_published'] = $request->boolean('is_published', false);
        $data['sort_order'] = 0;
        $data['builder_data'] = '[]'; // Initialize empty builder data

        $page = Page::create($data);

        if ($request->expectsJson() || $request->ajax()) {
            $response = [
                'success' => true,
                'message' => 'Builder page created successfully!',
                'page' => $page
            ];
            
            // Redirect to builder if requested
            if ($request->boolean('open_builder', true)) {
                $response['redirect_url'] = url("panel/pages/{$page->id}/builder");
            }
            
            return response()->json($response);
        }

        return redirect()->route('panel.pages')->with('success', 'Builder page created successfully!');
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
