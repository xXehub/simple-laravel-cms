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

        // Get dynamic view path from database
        $viewPath = $menu?->getDynamicViewPath() ?? 'panel.pages.index';

        return view($viewPath, compact('menu'));
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

        Page::create($data);

        return ResponseHelper::redirect('panel.pages', 'Page created successfully');
    }

    /**
     * Get page data for editing (AJAX support)
     */
    public function edit(Request $request)
    {
        $page = $this->getPageById($request);

        // If this is an AJAX request, return JSON data
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
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
        }

        return view('panel.pages.edit', compact('page'));
    }

    /**
     * Update page
     */
    public function update(UpdatePageRequest $request)
    {
        $page = $this->getPageById($request);

        $data = $request->validated();
        
        // Handle boolean fields that exist in database
        // For checkboxes, they're only sent when checked
        $data['is_published'] = $request->has('is_published') ? true : false;
        
        // Set defaults for existing fields only
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $page->update($data);

        return ResponseHelper::redirect('panel.pages', 'Page updated successfully');
    }

    /**
     * Delete page
     */
    public function destroy(Request $request)
    {
        $page = $this->getPageById($request);
        $page->delete();
        
        return ResponseHelper::redirect('panel.pages', 'Page deleted successfully');
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
            ->addColumn('status_badge', function ($page) {
                $status = $page->is_published ? 'published' : 'draft';
                $class = $page->is_published ? 'success' : 'warning';
                return '<span class="badge bg-' . $class . '">' . ucfirst($status) . '</span>';
            })
            ->addColumn('action', function ($page) {
                // Render action buttons using the component
                return view('components.modals.pages.action', [
                    'page' => [
                        'id' => $page->id,
                        'title' => $page->title,
                        'slug' => $page->slug,
                        'is_published' => $page->is_published
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($page) {
                return $page->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['checkbox', 'status_badge', 'action'])
            ->make(true);
    }
}
