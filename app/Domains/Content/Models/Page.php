<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Actions\GenerateSitemapAction;
use App\Domains\Content\Concerns\HasContentRevisions;
use App\Domains\Content\Concerns\HasContentStatus;
use App\Domains\Content\Concerns\TracksContentAudit;
use App\Domains\Content\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasContentRevisions;
    use HasContentStatus;
    use TracksContentAudit;

    protected $table = 'content_pages';

    protected $fillable = [
        'slug',
        'title',
        'meta_title',
        'meta_description',
        'og_image',
        'robots_index',
        'canonical_url',
        'og_type',
        'twitter_card',
        'focus_keyword',
        'schema_type',
        'include_in_sitemap',
        'sitemap_priority',
        'sitemap_changefreq',
        'is_published',
        'status',
        'sort_order',
        'extras',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'robots_index' => 'boolean',
        'include_in_sitemap' => 'boolean',
        'sitemap_priority' => 'float',
        'sort_order' => 'integer',
        'extras' => 'array',
        'status' => ContentStatus::class,
    ];

    protected static function booted(): void
    {
        static::saved(fn () => GenerateSitemapAction::forgetCache());
        static::deleted(fn () => GenerateSitemapAction::forgetCache());
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class, 'page_id')->orderBy('sort_order');
    }

    public function publishedSections(): HasMany
    {
        return $this->sections()->where('is_published', true);
    }
}
