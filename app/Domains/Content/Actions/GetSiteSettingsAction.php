<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\SiteSetting;
use App\Domains\Integrations\Services\SettingsResolver;

class GetSiteSettingsAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        $resolver = app(SettingsResolver::class);

        $defaults = [
            'brand' => config('heartwell.brand'),
            'branding' => [
                'logo_mode' => 'text',
                'logo_text' => 'HeartWell',
                'logo_tagline' => config('heartwell.brand.tagline'),
                'logo_image_path' => null,
                'logo_white_path' => null,
                'favicon_path' => null,
            ],
            'navigation' => config('heartwell.navigation'),
            'ctas' => config('heartwell.ctas'),
            'compliance' => config('heartwell.compliance'),
            'footer' => ['email' => null, 'phone' => null, 'address' => null],
            'social' => [],
            'contact_forms' => [],
            'home' => [],
            'seo' => [
                'ga4_measurement_id' => $resolver->get('ga4_measurement_id', 'HEARTWELL_GA4_MEASUREMENT_ID'),
                'default_meta_title' => config('heartwell.brand.name'),
                'default_meta_description' => null,
                'default_og_image' => null,
                'robots_index' => true,
            ],
        ];

        $stored = SiteSetting::query()->pluck('value', 'key');

        foreach ($stored as $key => $value) {
            if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key])) {
                $defaults[$key] = array_replace_recursive($defaults[$key], $value);
            } elseif ($value !== null) {
                $defaults[$key] = $value;
            }
        }

        if (! empty($defaults['seo']['ga4_measurement_id'])) {
            config(['heartwell.ga4_measurement_id' => $defaults['seo']['ga4_measurement_id']]);
        }

        return $defaults;
    }
}
