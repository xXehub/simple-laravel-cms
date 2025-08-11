<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Setting;

class SettingComposer
{
    /**
     * Create a new setting composer.
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $globalSettings = Setting::getAllCached();
        
        // Format settings untuk mudah diakses di view
        $settings = [];
        foreach ($globalSettings as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        $view->with('globalSettings', $settings);
    }
}
