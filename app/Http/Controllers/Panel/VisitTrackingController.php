<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\VisitTrackingService;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VisitTrackingController extends Controller
{
    protected $visitService;
    
    public function __construct(VisitTrackingService $visitService)
    {
        $this->visitService = $visitService;
    }
    
    /**
     * Halaman setting visit tracking
     */
    public function index()
    {
        $stats = $this->visitService->getStats();
        $bufferData = $this->visitService->getBufferData();
        $retentionDays = Setting::getValue('visit_retention_days', 7);
        
        return view('panel.visit-tracking.index', compact('stats', 'bufferData', 'retentionDays'));
    }
    
    /**
     * Update setting retention days
     */
    public function updateRetention(Request $request)
    {
        $request->validate([
            'retention_days' => 'required|integer|min:1|max:30'
        ]);
        
        Setting::setValue('visit_retention_days', $request->retention_days);
        
        return redirect()->back()->with('success', 'Setting retention berhasil diupdate ke ' . $request->retention_days . ' hari');
    }
    
    /**
     * Manual reset semua data tracking
     */
    public function manualReset()
    {
        $result = $this->visitService->resetAllVisits();
        
        if ($result['status'] === 'success') {
            $message = "Reset berhasil: {$result['database_reset']} database, {$result['redis_reset']} Redis keys";
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
    
    /**
     * Force sync buffer ke database
     */
    public function forceSync()
    {
        $result = $this->visitService->syncToDatabase();
        
        if ($result['status'] === 'success') {
            $message = "Sync selesai: {$result['synced']} item berhasil";
            if (!empty($result['errors'])) {
                $message .= ", " . count($result['errors']) . " error";
            }
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
    
    /**
     * Auto cleanup data lama
     */
    public function autoCleanup()
    {
        $result = $this->visitService->autoCleanup();
        
        if ($result['status'] === 'success') {
            $message = "Cleanup selesai: {$result['deleted_keys']} key dihapus (retention: {$result['retention_days']} hari)";
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
    
    /**
     * Reset cache aplikasi
     */
    public function resetCache()
    {
        $result = $this->visitService->resetCache();
        
        if ($result['status'] === 'success') {
            $message = "Cache berhasil di-reset: {$result['deleted_keys']} keys dihapus";
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
    
    /**
     * Export data untuk debugging
     */
    public function exportData()
    {
        $data = [
            'stats' => $this->visitService->getStats(),
            'buffer' => $this->visitService->getBufferData(),
            'most_visited' => $this->visitService->getMostVisited(20),
            'settings' => [
                'retention_days' => Setting::getValue('visit_retention_days', 7)
            ],
            'exported_at' => now()->toDateTimeString()
        ];
        
        $filename = 'visit_tracking_export_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
}
