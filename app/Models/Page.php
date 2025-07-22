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
        'sort_order'
    ];

    protected $casts = [
        'is_published' => 'boolean',
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
}
