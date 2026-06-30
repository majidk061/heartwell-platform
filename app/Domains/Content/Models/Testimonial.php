<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Concerns\HasContentRevisions;
use App\Domains\Content\Concerns\HasContentStatus;
use App\Domains\Content\Concerns\TracksContentAudit;
use App\Domains\Content\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasContentRevisions;
    use HasContentStatus;
    use TracksContentAudit;

    protected $table = 'content_testimonials';

    protected $fillable = [
        'author_name',
        'image_path',
        'quote',
        'attribution',
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
}
