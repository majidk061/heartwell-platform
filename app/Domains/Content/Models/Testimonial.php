<?php

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'content_testimonials';

    protected $fillable = [
        'author_name',
        'quote',
        'attribution',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_published' => 'boolean',
    ];
}
