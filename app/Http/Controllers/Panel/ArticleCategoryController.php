<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleCategoryController extends Controller
{
    public function index()
    {
        return view('panel.article-category.index', [
            'title' => 'Article Categories'
        ]);
    }

    public function create()
    {
        return view('panel.article-category.create', [
            'title' => 'Create Article Category'
        ]);
    }

    public function store(Request $request)
    {
        // Implementation will be added later
        return redirect()->route('panel.article-category.index')
            ->with('success', 'Article category created successfully');
    }

    public function show($id)
    {
        return view('panel.article-category.show', [
            'title' => 'View Article Category'
        ]);
    }

    public function edit($id)
    {
        return view('panel.article-category.edit', [
            'title' => 'Edit Article Category'
        ]);
    }

    public function update(Request $request, $id)
    {
        // Implementation will be added later
        return redirect()->route('panel.article-category.index')
            ->with('success', 'Article category updated successfully');
    }

    public function destroy($id)
    {
        // Implementation will be added later
        return redirect()->route('panel.article-category.index')
            ->with('success', 'Article category deleted successfully');
    }
}
