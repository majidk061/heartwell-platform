<?php

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;

class AvatarCard extends Model
{
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
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_published' => 'boolean',
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
