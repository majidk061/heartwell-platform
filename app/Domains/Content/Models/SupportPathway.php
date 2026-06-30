<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Concerns\HasContentStatus;
use App\Domains\Content\Concerns\TracksContentAudit;
use App\Domains\Content\Enums\ContentStatus;
use App\Domains\CRM\Enums\AvatarType;
use Illuminate\Database\Eloquent\Model;

class SupportPathway extends Model
{
    use HasContentStatus;
    use TracksContentAudit;

    protected $table = 'content_support_pathways';

    protected $fillable = [
        'slug',
        'title',
        'intro',
        'image_path',
        'accordion_content',
        'avatar_type',
        'cta_label',
        'cta_url',
        'sort_order',
        'is_published',
        'status',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'accordion_content' => 'array',
        'sort_order' => 'integer',
        'is_published' => 'boolean',
        'status' => ContentStatus::class,
        'avatar_type' => AvatarType::class,
    ];

    public function getBodyAttribute(): ?string
    {
        if (is_array($this->accordion_content) && count($this->accordion_content) > 0) {
            return $this->accordion_content[0]['body'] ?? null;
        }

        return null;
    }
}
