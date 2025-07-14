<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\Setting\UpdateSettingsRequest;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::all()->keyBy('key') ?? collect();
        return view('panel.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(UpdateSettingsRequest $request)
    {
        $settings = $request->input('settings', []);
        
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }
        
        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
