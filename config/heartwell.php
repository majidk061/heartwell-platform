<?php

return [

    'brand' => [
        'name' => 'HeartWell Aesthetics & Wellness',
        'tagline' => 'For Every Stage of Life',
        'promise' => 'Thoughtful, Compassionate Care You Can Trust',
        'footer_tagline' => 'Compassionate Care for Every Stage of Life',
        'domain' => env('HEARTWELL_DOMAIN', 'heartwellwellness.com'),
    ],

    'ctas' => [
        'primary' => [
            'label' => 'Book a Visit',
            'route' => 'contact',
            'anchor' => '#book',
        ],
        'secondary' => [
            'waitlist' => [
                'label' => 'Join the Waitlist',
                'route' => 'contact',
                'anchor' => '#waitlist',
            ],
            'consultation' => [
                'label' => 'Request Consultation',
                'route' => 'contact',
                'anchor' => '#consultation',
            ],
        ],
    ],

    'compliance' => [
        'footer_note' => 'HeartWell Aesthetics & Wellness provides wellness education and coordination in New Jersey. Clinical intake, health history, consent forms, and provider screening are completed through HeartWell\'s HIPAA-compliant secure clinical portal. Clinical clearance is required before treatment and must be renewed every 6 months, or sooner if required. HeartWell remains your primary contact for scheduling and support.',
        'contact_disclaimer' => 'Information submitted through this form is used to coordinate your wellness journey. It is not a substitute for emergency medical care. If you are experiencing a medical emergency, call 911.',
        'clinical_portal_note' => 'Before your first visit, you will complete a secure clinical intake through HeartWell\'s HIPAA-compliant portal. Each guest at a group gathering must complete their own intake, health history, consent, and provider screening before receiving services.',
        'group_intake_note' => 'Booking a group wellness gathering does not satisfy medical clearance for participants. Each guest receiving services must individually complete intake, health history, consent, and provider screening before treatment.',
        'privacy_summary' => 'We respect your privacy. Your information is used only to respond to your request and coordinate care. We do not sell your personal information.',
    ],

    'ga4_measurement_id' => env('HEARTWELL_GA4_MEASUREMENT_ID'),

    'cms' => [
        'max_revisions' => 10,
    ],

    'navigation' => [
        [
            'label' => 'Home',
            'route' => 'home',
        ],
        [
            'label' => 'Support Pathways',
            'route' => 'support-pathways',
        ],
        [
            'label' => 'Your Experience',
            'route' => 'your-experience',
        ],
        [
            'label' => 'Why HeartWell',
            'route' => 'why-heartwell',
        ],
        [
            'label' => 'Wellness Journey',
            'route' => 'wellness-journey',
        ],
        [
            'label' => 'Meet the Founder',
            'route' => 'meet-the-founder',
        ],
        [
            'label' => 'Contact / Waitlist',
            'route' => 'contact',
        ],
    ],

    'footer_columns' => [
        [
            'title' => 'YOUR EXPERIENCE',
            'links' => [
                ['label' => 'Wellness Journey', 'route' => 'wellness-journey'],
                ['label' => 'How It Works', 'route' => 'your-experience'],
                ['label' => 'What to Expect', 'route' => 'your-experience'],
                ['label' => 'Safety & Standards', 'route' => 'why-heartwell'],
            ],
        ],
        [
            'title' => 'WHY HEARTWELL',
            'links' => [
                ['label' => 'Whole-Person Care', 'route' => 'why-heartwell'],
                ['label' => 'Our Approach', 'route' => 'why-heartwell'],
                ['label' => 'Expert-Guided Care', 'route' => 'why-heartwell'],
                ['label' => 'Flexible & Convenient', 'route' => 'your-experience'],
            ],
        ],
        [
            'title' => 'COMPANY',
            'links' => [
                ['label' => 'Meet the Founder', 'route' => 'meet-the-founder'],
                ['label' => 'About HeartWell', 'route' => 'why-heartwell'],
                ['label' => 'Contact / Waitlist', 'route' => 'contact'],
                ['label' => 'Privacy Policy', 'route' => 'contact'],
            ],
        ],
    ],

    'avatar_cards' => [
        'depleted' => [
            'type' => 'depleted',
            'headline' => "I'm functioning… but exhausted.",
            'subtext' => 'Low energy, fatigue, burnout, and brain fog — you deserve support that meets you where you are.',
            'cta_label' => 'Explore Energy & Recovery',
            'pathway_slug' => 'energy-wellness',
        ],
        'frustrated' => [
            'type' => 'frustrated',
            'headline' => "I'm trying, but I feel stuck.",
            'subtext' => 'Weight changes, metabolism shifts, and resistance despite effort — clarity is possible.',
            'cta_label' => 'Explore Metabolic Support',
            'pathway_slug' => 'metabolic-weight',
        ],
        'confidence' => [
            'type' => 'confidence',
            'headline' => 'How I see myself is changing.',
            'subtext' => 'Appearance changes, self-image shifts, and confidence concerns — support for every stage of life.',
            'cta_label' => 'Explore Confidence & Aesthetic Support',
            'pathway_slug' => 'confidence-aesthetic',
        ],
    ],

];
