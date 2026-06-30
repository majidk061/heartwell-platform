<?php

return [

    'acuity' => [
        'enabled' => env('ACUITY_ENABLED', false),
        'user_id' => env('ACUITY_USER_ID'),
        'api_key' => env('ACUITY_API_KEY'),
        'webhook_secret' => env('ACUITY_WEBHOOK_SECRET'),
        'embed_url' => env('ACUITY_EMBED_URL'),
        'api_base_url' => env('ACUITY_API_BASE_URL', 'https://acuityscheduling.com/api/v1'),
    ],

    'mailchimp' => [
        'enabled' => env('MAILCHIMP_ENABLED', false),
        'api_key' => env('MAILCHIMP_API_KEY'),
        'server_prefix' => env('MAILCHIMP_SERVER_PREFIX'),
        'audience_id' => env('MAILCHIMP_AUDIENCE_ID'),
        'default_tags' => array_filter(explode(',', env('MAILCHIMP_DEFAULT_TAGS', 'heartwell,website'))),
    ],

    'sendgrid' => [
        'enabled' => env('SENDGRID_ENABLED', false),
        'api_key' => env('SENDGRID_API_KEY'),
        'from_email' => env('SENDGRID_FROM_EMAIL', 'hello@heartwellwellness.com'),
        'from_name' => env('SENDGRID_FROM_NAME', 'HeartWell Aesthetics & Wellness'),
        'admin_alert_email' => env('SENDGRID_ADMIN_ALERT_EMAIL'),
        'templates' => [
            'waitlist_welcome' => env('SENDGRID_TEMPLATE_WAITLIST_WELCOME'),
            'consultation_ack' => env('SENDGRID_TEMPLATE_CONSULTATION_ACK'),
            'booking_confirmation' => env('SENDGRID_TEMPLATE_BOOKING_CONFIRMATION'),
            'admin_alert' => env('SENDGRID_TEMPLATE_ADMIN_ALERT'),
        ],
    ],

    'hydreight' => [
        'enabled' => env('HYDREIGHT_ENABLED', false),
        'portal_url' => env('HYDREIGHT_PORTAL_URL'),
        'handoff_route' => env('HYDREIGHT_HANDOFF_ROUTE', 'clinical-intake'),
    ],

    'twilio' => [
        'enabled' => env('TWILIO_ENABLED', false),
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'from_number' => env('TWILIO_FROM_NUMBER'),
    ],

];
