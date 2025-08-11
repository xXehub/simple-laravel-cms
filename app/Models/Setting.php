<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'description'];

    /**
     * Cache key for settings
     */
    const CACHE_KEY = 'app_settings';

    /**
     * Setting types
     */
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_NUMBER = 'number';
    const TYPE_EMAIL = 'email';
    const TYPE_URL = 'url';
    const TYPE_IMAGE = 'image';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_COLOR = 'color';

    /**
     * Setting groups
     */
    const GROUP_GENERAL = 'general';
    const GROUP_BRANDING = 'branding';
    const GROUP_SEO = 'seo';
    const GROUP_CONTACT = 'contact';
    const GROUP_SOCIAL = 'social';
    const GROUP_FEATURE = 'feature';

    /**
     * Get all settings cached
     */
    public static function getAllCached()
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::all()->keyBy('key');
        });
    }

    /**
     * Get a setting value by key with optional default.
     */
    public static function getValue(string $key, $default = null)
    {
        $settings = self::getAllCached();
        $setting = $settings->get($key);
        
        if (!$setting) {
            return $default;
        }

        // Cast boolean values
        if ($setting->type === self::TYPE_BOOLEAN) {
            return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
        }

        // Cast number values
        if ($setting->type === self::TYPE_NUMBER) {
            return is_numeric($setting->value) ? (int) $setting->value : $default;
        }

        return $setting->value ?: $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, $value, string $type = self::TYPE_TEXT, string $group = self::GROUP_GENERAL, string $description = null): bool
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description
            ]
        );

        // Clear cache
        Cache::forget(self::CACHE_KEY);

        return $setting->wasRecentlyCreated;
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when settings are modified
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget(self::CACHE_KEY);
        });
    }
}