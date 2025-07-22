<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\Page\StorePageRequest;
use App\Http\Requests\Panel\Page\UpdatePageRequest;
use App\Helpers\ResponseHelper;

class PageController extends Controller
{
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
    public function index()
    {
        $pages = Page::ordered()->paginate(20);
        return view('panel.pages.index', compact('pages'));
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
        $data['is_published'] = $request->has('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Page::create($data);

        return ResponseHelper::redirect('panel.pages.index', 'Page created successfully');
    }

    /**
     * Show edit page form
     */
    public function edit(Request $request)
    {
        $page = $this->getPageById($request);
        return view('panel.pages.edit', compact('page'));
    }

    /**
     * Update page
     */
    public function update(UpdatePageRequest $request)
    {
        $page = $this->getPageById($request);

        $data = $request->validated();
        $data['is_published'] = $request->has('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $page->update($data);

        return ResponseHelper::redirect('panel.pages.index', 'Page updated successfully');
    }

    /**
     * Delete page
     */
    public function destroy(Request $request)
    {
        $page = $this->getPageById($request);
        $page->delete();

        return ResponseHelper::redirect('panel.pages.index', 'Page deleted successfully');
    }
}
