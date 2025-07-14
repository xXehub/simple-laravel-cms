<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\Page\StorePageRequest;
use App\Http\Requests\Panel\Page\UpdatePageRequest;

class PageController extends Controller
{
    /**
     * Display pages listing
     */
    public function index()
    {
        $pages = Page::paginate(20);
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
        Page::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'template' => $request->template,
            'is_published' => $request->has('is_published'),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('panel.pages.index')
            ->with('success', 'Page created successfully');
    }

    /**
     * Show edit page form
     */
    public function edit(Request $request)
    {
        $pageId = $request->route('id') ?? $request->input('id');
        $page = Page::findOrFail($pageId);
        
        return view('panel.pages.edit', compact('page'));
    }

    /**
     * Update page
     */
    public function update(UpdatePageRequest $request)
    {
        $pageId = $request->route('id') ?? $request->input('id');
        $page = Page::findOrFail($pageId);

        $page->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'template' => $request->template,
            'is_published' => $request->has('is_published'),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('panel.pages.index')
            ->with('success', 'Page updated successfully');
    }

    /**
     * Delete page
     */
    public function destroy(Request $request)
    {
        $pageId = $request->route('id') ?? $request->input('id');
        $page = Page::findOrFail($pageId);

        $page->delete();

        return redirect()->route('panel.pages.index')
            ->with('success', 'Page deleted successfully');
    }
}
