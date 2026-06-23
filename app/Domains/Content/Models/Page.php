<?php

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $table = 'content_pages';

    protected $fillable = [
        'slug',
        'title',
        'meta_title',
        'meta_description',
        'og_image',
        'is_published',
        'sort_order',
        'extras',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
        'extras' => 'array',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class, 'page_id')->orderBy('sort_order');
    }

    public function publishedSections(): HasMany
    {
        return $this->sections()->where('is_published', true);
    }
}
