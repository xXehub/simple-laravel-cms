<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
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
     * Display pages listing
     */
    public function index(Request $request)
    {
        // Handle DataTable AJAX requests
        if ($request->ajax() && $request->has('draw')) {
            return $this->datatable($request);
        }

        // Regular view request
        return view('panel.pages.index');
    }

    /**
     * Show create page form
     */
    public function create()
    {
        return view('panel.pages.create');
    }

    /**
     * Store new page
     */
    public function store(StorePageRequest $request)
    {
        $data = $request->validated();
        
        // Handle boolean fields
        $data['is_published'] = $request->has('is_published');
        $data['featured'] = $request->has('featured');
        $data['show_in_menu'] = $request->has('show_in_menu');
        
        // Set defaults
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['status'] = $data['status'] ?? 'draft';
        $data['page_type'] = $data['page_type'] ?? 'page';
        $data['template'] = $data['template'] ?? 'default';
        
        // Set creator
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        
        // Handle published_at
        if ($data['is_published'] && $data['status'] === 'published' && !isset($data['published_at'])) {
            $data['published_at'] = now();
        }

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
                    'status' => $page->status,
                    'page_type' => $page->page_type,
                    'excerpt' => $page->excerpt,
                    'featured' => $page->featured,
                    'show_in_menu' => $page->show_in_menu,
                    'meta_title' => $page->meta_title,
                    'meta_description' => $page->meta_description,
                    'sort_order' => $page->sort_order,
                    'parent_id' => $page->parent_id,
                    'menu_id' => $page->menu_id,
                    'published_at' => $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : null
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
        
        // Handle boolean fields
        $data['is_published'] = $request->has('is_published');
        $data['featured'] = $request->has('featured');
        $data['show_in_menu'] = $request->has('show_in_menu');
        
        // Set defaults
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        // Set updater
        $data['updated_by'] = auth()->id();
        
        // Handle published_at
        if ($data['is_published'] && $data['status'] === 'published' && !$page->published_at) {
            $data['published_at'] = now();
        } elseif (!$data['is_published'] || $data['status'] !== 'published') {
            $data['published_at'] = null;
        }

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
