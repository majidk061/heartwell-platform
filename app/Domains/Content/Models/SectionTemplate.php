<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Concerns\HasContentRevisions;
use App\Domains\Content\Concerns\HasContentStatus;
use App\Domains\Content\Concerns\TracksContentAudit;
use App\Domains\Content\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionTemplate extends Model
{
    use HasContentRevisions;
    use HasContentStatus;
    use TracksContentAudit;

    protected $table = 'content_section_templates';

    protected $fillable = [
        'name',
        'section_type',
        'heading',
        'content',
        'layout',
        'description',
        'sort_order',
        'is_published',
        'status',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'content' => 'array',
        'layout' => 'array',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
        'status' => ContentStatus::class,
    ];

    public function pageSections(): HasMany
    {
        return $this->hasMany(PageSection::class, 'section_template_id');
    }
}
