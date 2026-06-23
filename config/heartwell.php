<?php

return [

  'brand' => [
    'name' => 'HeartWell Aesthetics & Wellness',
    'tagline' => 'For Every Stage of Life',
    'promise' => 'Thoughtful, Compassionate Care You Can Trust',
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
    'footer_note' => 'HeartWell Aesthetics & Wellness provides wellness education and coordination. Clinical intake and medical services are delivered through our licensed clinical partner. HeartWell is your primary brand contact for scheduling and support.',
    'contact_disclaimer' => 'Information submitted through this form is used to coordinate your wellness journey. It is not a substitute for emergency medical care. If you are experiencing a medical emergency, call 911.',
    'hydreight_note' => 'Individual clinical intake is completed through our secure clinical partner portal. Each guest must complete their own intake before services are provided.',
    'privacy_summary' => 'We respect your privacy. Your information is used only to respond to your request and coordinate care. We do not sell your personal information.',
  ],

  'ga4_measurement_id' => env('HEARTWELL_GA4_MEASUREMENT_ID'),

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
      'label' => 'Contact',
      'route' => 'contact',
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
