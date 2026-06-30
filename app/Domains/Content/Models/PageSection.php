<?php

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    protected $table = 'content_page_sections';

    protected $fillable = [
        'page_id',
        'section_template_id',
        'section_type',
        'heading',
        'sort_order',
        'content',
        'is_published',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'content' => 'array',
        'is_published' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(SectionTemplate::class, 'section_template_id');
    }

    public function isLinkedToLibrary(): bool
    {
        return filled($this->section_template_id);
    }

    public function getTypeAttribute(): string
    {
        return $this->section_type;
    }

    public function getBodyAttribute(): ?string
    {
        return $this->content['body'] ?? null;
    }

    public function getSubheadingAttribute(): ?string
    {
        return $this->content['subheading'] ?? null;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->content['image_url'] ?? null;
    }

    protected static function booted(): void
    {
        static::saving(function (PageSection $section): void {
            if (! $section->section_template_id) {
                return;
            }

            $section->heading = null;
            $section->content = null;

            if (blank($section->section_type) && $section->template) {
                $section->section_type = $section->template->section_type;
            }
        });
    }
}
