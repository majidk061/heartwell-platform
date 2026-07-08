<?php

use App\Domains\Content\Support\ClientCopyCatalog;

return [

    'brand' => [
        'name' => 'HeartWell Aesthetics & Wellness',
        'tagline' => 'For Every Stage of Life',
        'promise' => 'Thoughtful, Compassionate Care You Can Trust',
        'footer_tagline' => 'Compassionate Care for Every Stage of Life',
        'domain' => env('HEARTWELL_DOMAIN', 'heartwellwellness.com'),
    ],

    'ctas' => ClientCopyCatalog::siteCtas(),

    'compliance' => ClientCopyCatalog::complianceDefaults(),

    'contact_forms' => ClientCopyCatalog::contactFormsDefaults(),

    'ga4_measurement_id' => env('HEARTWELL_GA4_MEASUREMENT_ID'),

    'cms' => [
        'max_revisions' => 10,
    ],

    'navigation' => ClientCopyCatalog::navigation(),

    'footer_columns' => ClientCopyCatalog::footerColumns(),

    'avatar_cards' => [
        'depleted' => [
            'type' => 'depleted',
            'headline' => "I'm functioning… but exhausted.",
            'subtext' => 'Low energy, fatigue, burnout, and brain fog — you deserve support that meets you where you are.',
            'cta_label' => 'Explore Energy & Wellness',
            'pathway_slug' => 'energy-wellness',
        ],
        'frustrated' => [
            'type' => 'frustrated',
            'headline' => "I'm trying, but I feel stuck.",
            'subtext' => 'Weight changes, metabolism shifts, and resistance despite effort — clarity is possible.',
            'cta_label' => 'Explore Metabolic & Weight Support',
            'pathway_slug' => 'metabolic-weight',
        ],
        'confidence' => [
            'type' => 'confidence',
            'headline' => 'How I see myself is changing.',
            'subtext' => 'Visible changes in skin, eyes, or hair — thoughtful support for every stage of life.',
            'cta_label' => 'Explore Precision Glow Therapy',
            'pathway_slug' => 'precision-glow-therapy',
        ],
    ],

];
