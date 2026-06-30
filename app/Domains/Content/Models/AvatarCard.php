<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Concerns\HasContentStatus;
use App\Domains\Content\Concerns\TracksContentAudit;
use App\Domains\Content\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Model;

class AvatarCard extends Model
{
    use HasContentStatus;
    use TracksContentAudit;

    protected $table = 'content_avatar_cards';

    protected $fillable = [
        'slug',
        'headline',
        'subtext',
        'cta_label',
        'pathway_slug',
        'image_path',
        'sort_order',
        'is_published',
        'status',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_published' => 'boolean',
        'status' => ContentStatus::class,
    ];

    public function imageUrl(): ?string
    {
        if (empty($this->image_path)) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset('storage/'.$this->image_path);
    }
}
