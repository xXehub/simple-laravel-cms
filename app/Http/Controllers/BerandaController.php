<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class BerandaController extends Controller
{
    /**
     * Tampilkan halaman beranda dengan search dan pagination
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ambil parameter search dari request
        $search = $request->get('search');
        
        // Query pages yang published dengan urutan sort_order
        $query = Page::published()->orderBy('sort_order');
        
        // Jika ada search, filter berdasarkan title atau content
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                  ->orWhere('content', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Paginate hasil dengan 8 item per halaman
        $pages = $query->paginate(8);
        
        // Append search parameter ke pagination links agar tetap ada saat pindah halaman
        $pages->appends($request->only('search'));
        
        return view('beranda', [
            'pages' => $pages,
            'search' => $search
        ]);
    }
}
