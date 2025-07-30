<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleTagController extends Controller
{
    public function index()
    {
        return view('panel.article-tag.index', [
            'title' => 'Article Tags'
        ]);
    }

    public function create()
    {
        return view('panel.article-tag.create', [
            'title' => 'Create Article Tag'
        ]);
    }

    public function store(Request $request)
    {
        // Implementation will be added later
        return redirect()->route('panel.article-tag.index')
            ->with('success', 'Article tag created successfully');
    }

    public function show($id)
    {
        return view('panel.article-tag.show', [
            'title' => 'View Article Tag'
        ]);
    }

    public function edit($id)
    {
        return view('panel.article-tag.edit', [
            'title' => 'Edit Article Tag'
        ]);
    }

    public function update(Request $request, $id)
    {
        // Implementation will be added later
        return redirect()->route('panel.article-tag.index')
            ->with('success', 'Article tag updated successfully');
    }

    public function destroy($id)
    {
        // Implementation will be added later
        return redirect()->route('panel.article-tag.index')
            ->with('success', 'Article tag deleted successfully');
    }
}
