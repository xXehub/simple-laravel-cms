<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class DynamicController extends Controller
{
    /**
     * Handle welcome page
     */
    public function handleWelcome()
    {
        return view('welcome');
    }

    /**
     * Handle user profile page
     */
    public function handleProfile()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return view('profile.show', ['user' => auth()->user()]);
    }

    /**
     * Handle dynamic CMS pages
     */
    public function handleDynamicPage($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }
}
    