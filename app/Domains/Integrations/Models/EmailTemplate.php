<?php

namespace App\Domains\Integrations\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'key',
        'name',
        'audience',
        'subject',
        'heading',
        'body',
        'logo_path',
        'button_label',
        'button_url',
        'footer_text',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
