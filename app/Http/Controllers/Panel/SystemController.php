<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function index()
    {
        return view('panel.system.index', [
            'title' => 'System Settings'
        ]);
    }

    public function backup()
    {
        return view('panel.system.backup', [
            'title' => 'System Backup'
        ]);
    }

    public function logs()
    {
        return view('panel.system.logs', [
            'title' => 'System Logs'
        ]);
    }

    public function cache()
    {
        return view('panel.system.cache', [
            'title' => 'Cache Management'
        ]);
    }

    public function clearCache(Request $request)
    {
        // Implementation will be added later
        return redirect()->route('panel.system.cache')
            ->with('success', 'Cache cleared successfully');
    }

    public function createBackup(Request $request)
    {
        // Implementation will be added later
        return redirect()->route('panel.system.backup')
            ->with('success', 'Backup created successfully');
    }
}
