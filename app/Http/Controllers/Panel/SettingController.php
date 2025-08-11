<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\MasterMenu;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Panel\Setting\UpdateSettingsRequest;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:view-settings')->only(['index', 'datatable']);
        $this->middleware('permission:update-settings')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display settings page with DataTable
     */
    public function index(Request $request)
    {
        // Get current menu from request for dynamic view resolution
        $currentSlug = $request->route()->uri ?? 'panel/settings';
        $menu = MasterMenu::where('slug', $currentSlug)->first();

        // Get dynamic view path from database
        $viewPath = $menu?->getDynamicViewPath() ?? 'panel.settings.index';

        return view($viewPath, compact('menu'));
    }

    /**
     * DataTable endpoint for settings
     */
    public function datatable(Request $request)
    {
        $query = Setting::query();

        // Add search if provided  
        if ($search = $request->get('search')['value'] ?? null) {
            $query->where(function ($q) use ($search) {
                $q->where('key', 'LIKE', "%{$search}%")
                    ->orWhere('value', 'LIKE', "%{$search}%")
                    ->orWhere('type', 'LIKE', "%{$search}%")
                    ->orWhere('group', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('checkbox', function ($setting) {
                return '<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select setting" value="' . $setting->id . '"/>';
            })
            ->addColumn('type_badge', function ($setting) {
                // Send raw data to be processed in JavaScript
                return $setting->type;
            })
            ->addColumn('group_badge', function ($setting) {
                // Send raw data to be processed in JavaScript
                return $setting->group;
            })
            ->addColumn('value_preview', function ($setting) {
                $value = $setting->value;
                
                if ($setting->type === Setting::TYPE_BOOLEAN) {
                    return $value ? 'true' : 'false';
                } elseif ($setting->type === Setting::TYPE_IMAGE && $value) {
                    return '<img src="' . asset('storage/' . $value) . '" alt="Image" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">';
                } elseif (strlen($value) > 50) {
                    return '<span title="' . htmlspecialchars($value) . '">' . substr($value, 0, 50) . '...</span>';
                }
                
                return $value ?: '<span class="text-muted">Empty</span>';
            })
            ->addColumn('action', function ($setting) {
                return view('components.modals.settings.action', compact('setting'))->render();
            })
            ->editColumn('created_at', function ($setting) {
                return $setting->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['checkbox', 'value_preview', 'action'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified setting.
     */
    public function edit($id)
    {
        try {
            $setting = Setting::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'setting' => $setting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }
    }

    /**
     * Store a new setting
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'nullable|string',
            'type' => 'required|string',
            'group' => 'required|string',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        try {
            $value = $request->value;
            
            // Handle image upload
            if ($request->type === 'image' && $request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('settings', $filename, 'public');
                $value = $path;
            }

            Setting::create([
                'key' => $request->key,
                'value' => $value,
                'type' => $request->type,
                'group' => $request->group,
                'description' => $request->description
            ]);

            return ResponseHelper::handle(
                $request,
                'panel.settings',
                'Setting created successfully!',
                null,
                'success'
            );
        } catch (\Exception $e) {
            return ResponseHelper::handle(
                $request,
                'panel.settings',
                'Error creating setting: ' . $e->getMessage(),
                null,
                'error'
            );
        }
    }

    /**
     * Update a setting
     */
    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);

        $request->validate([
            'key' => 'required|string|unique:settings,key,' . $id,
            'value' => 'nullable|string',
            'type' => 'required|string',
            'group' => 'required|string',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        try {
            $value = $request->value;
            
            // Handle image upload
            if ($request->type === 'image' && $request->hasFile('image_file')) {
                // Delete old image if exists
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }
                
                $file = $request->file('image_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('settings', $filename, 'public');
                $value = $path;
            }

            $setting->update([
                'key' => $request->key,
                'value' => $value,
                'type' => $request->type,
                'group' => $request->group,
                'description' => $request->description
            ]);

            return ResponseHelper::handle(
                $request,
                'panel.settings',
                'Setting updated successfully!',
                null,
                'success'
            );
        } catch (\Exception $e) {
            return ResponseHelper::handle(
                $request,
                'panel.settings',
                'Error updating setting: ' . $e->getMessage(),
                null,
                'error'
            );
        }
    }

    /**
     * Delete a setting
     */
    public function destroy(Request $request, $id)
    {
        try {
            $setting = Setting::findOrFail($id);
            $setting->delete();

            return ResponseHelper::handle(
                $request,
                'panel.settings',
                'Setting deleted successfully!',
                null,
                'success'
            );
        } catch (\Exception $e) {
            return ResponseHelper::handle(
                $request,
                'panel.settings',
                'Error deleting setting: ' . $e->getMessage(),
                null,
                'error'
            );
        }
    }

    /**
     * Bulk delete settings
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'setting_ids' => 'required|array',
            'setting_ids.*' => 'exists:settings,id'
        ]);

        $settingIds = $request->setting_ids;
        
        $deleted = Setting::whereIn('id', $settingIds)->delete();

        return redirect()->route('panel.settings')
            ->with('success', "{$deleted} setting(s) deleted successfully");
    }
}
