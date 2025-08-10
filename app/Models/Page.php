<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'template',
        'is_published',
        'meta_title',
        'meta_description',
        'sort_order',
        'builder_data'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'builder_data' => 'array',
    ];

    /**
     * Scope for published pages only
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for draft pages only
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Get page by slug
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Search pages by title or content
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('meta_title', 'like', "%{$search}%");
        });
    }

    /**
     * Order by sort_order and then by title
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Get the route key name for Laravel
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get formatted publish status
     */
    public function getStatusAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    /**
     * Get excerpt from content
     */
    public function getExcerptAttribute($length = 150)
    {
        return strlen($this->content) > $length 
            ? substr(strip_tags($this->content), 0, $length) . '...'
            : strip_tags($this->content);
    }

    /**
     * Render builder content
     */
    public function renderBuilderContent()
    {
        if (empty($this->builder_data)) {
            return $this->content ?? '<div class="text-center py-5"><p class="text-muted">No content available.</p></div>';
        }

        // If builder_data is already clean HTML string, return it
        if (is_string($this->builder_data) && !$this->isJsonString($this->builder_data)) {
            return $this->builder_data;
        }

        // If it's JSON or array, try to process it (legacy support)
        if (is_array($this->builder_data) && isset($this->builder_data['components'])) {
            $html = '';
            foreach ($this->builder_data['components'] as $component) {
                $html .= $this->renderComponent($component);
            }
            return $html ?: '<div class="text-center py-5"><p class="text-muted">No content available.</p></div>';
        }

        return $this->content ?? '<div class="text-center py-5"><p class="text-muted">No content available.</p></div>';
    }

    /**
     * Check if string is JSON
     */
    private function isJsonString($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Render individual component (legacy support)
     */
    private function renderComponent($component)
    {
        if (!isset($component['type'])) {
            return '';
        }

        switch ($component['type']) {
            case 'heading':
                $level = $component['level'] ?? 'h2';
                $text = $component['text'] ?? 'Heading';
                $classes = $component['classes'] ?? '';
                return "<{$level} class=\"{$classes}\">{$text}</{$level}>";

            case 'text':
                $text = $component['text'] ?? 'Text content';
                $classes = $component['classes'] ?? '';
                return "<p class=\"{$classes}\">{$text}</p>";

            case 'button':
                $text = $component['text'] ?? 'Button';
                $href = $component['href'] ?? '#';
                $classes = $component['classes'] ?? 'btn btn-primary';
                return "<a href=\"{$href}\" class=\"{$classes}\">{$text}</a>";

            case 'card':
                $title = $component['title'] ?? 'Card Title';
                $text = $component['text'] ?? 'Card content';
                $classes = $component['classes'] ?? 'card';
                return "
                    <div class=\"{$classes}\">
                        <div class=\"card-body\">
                            <h5 class=\"card-title\">{$title}</h5>
                            <p class=\"card-text\">{$text}</p>
                        </div>
                    </div>
                ";

            default:
                return '';
        }
    }
}
