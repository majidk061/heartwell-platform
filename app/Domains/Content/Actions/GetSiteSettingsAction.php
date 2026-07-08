<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\SiteSetting;
use App\Domains\Content\Support\SectionLayout;
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
            'footer_columns' => config('heartwell.footer_columns'),
            'social' => [],
            'contact_forms' => config('heartwell.contact_forms', []),
            'home' => [],
            'theme' => [
                'site_width' => 'standard',
                'default_container_width' => 'default',
                'default_section_padding' => 'normal',
                'default_section_background' => 'white',
                'header_mode' => 'sticky',
                'header_style' => 'solid_cream',
                'header_show_border' => true,
                'colors' => SectionLayout::defaultThemeColors(),
                'navigation_style' => [
                    'hover_effect' => 'color',
                    'hover_color' => '#a69488',
                    'active_style' => 'underline',
                    'active_color' => '#a69488',
                    'header_cta_count' => 2,
                ],
            ],
            'seo' => [
                'ga4_measurement_id' => $resolver->get('ga4_measurement_id', 'HEARTWELL_GA4_MEASUREMENT_ID'),
                'default_meta_title' => config('heartwell.brand.name'),
                'default_meta_description' => null,
                'default_og_image' => null,
                'robots_index' => true,
                'robots_txt_content' => SectionLayout::defaultRobotsTxt(),
                'sitemap_enabled' => true,
                'sitemap_extra_urls' => [
                    ['path' => '/clinical-intake', 'priority' => 0.5, 'changefreq' => 'monthly'],
                    ['path' => '/privacy', 'priority' => 0.4, 'changefreq' => 'yearly'],
                ],
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
