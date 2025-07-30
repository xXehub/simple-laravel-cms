<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        return view('articles.index', [
            'title' => 'Articles'
        ]);
    }

    public function show($slug)
    {
        return view('articles.show', [
            'title' => 'Article Detail',
            'slug' => $slug
        ]);
    }

    public function category($slug)
    {
        return view('articles.category', [
            'title' => 'Articles by Category',
            'category' => $slug
        ]);
    }

    public function tag($slug)
    {
        return view('articles.tag', [
            'title' => 'Articles by Tag',
            'tag' => $slug
        ]);
    }
}
