<?php

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'content_faqs';

    protected $fillable = [
        'question',
        'answer',
        'page_slug',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_published' => 'boolean',
    ];
}
