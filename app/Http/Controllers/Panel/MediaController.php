<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        return view('panel.media.index', [
            'title' => 'Media Library'
        ]);
    }

    public function create()
    {
        return view('panel.media.create', [
            'title' => 'Upload Media'
        ]);
    }

    public function store(Request $request)
    {
        // Implementation will be added later
        return redirect()->route('panel.media.index')
            ->with('success', 'Media uploaded successfully');
    }

    public function show($id)
    {
        return view('panel.media.show', [
            'title' => 'View Media'
        ]);
    }

    public function edit($id)
    {
        return view('panel.media.edit', [
            'title' => 'Edit Media'
        ]);
    }

    public function update(Request $request, $id)
    {
        // Implementation will be added later
        return redirect()->route('panel.media.index')
            ->with('success', 'Media updated successfully');
    }

    public function destroy($id)
    {
        // Implementation will be added later
        return redirect()->route('panel.media.index')
            ->with('success', 'Media deleted successfully');
    }
}
