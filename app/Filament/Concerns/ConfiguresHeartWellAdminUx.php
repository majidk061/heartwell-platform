<?php

namespace App\Filament\Concerns;

trait ConfiguresHeartWellAdminUx
{
    /**
     * @return array<string, string>
     */
    public static function sectionTypeOptions(): array
    {
        return [
            'hero' => 'Hero — large banner at the top (Home, inner pages)',
            'intro' => 'Intro — centered text block with optional image',
            'avatar_intro' => 'Avatar intro — heading above the three audience cards (Home)',
            'journey' => 'Journey steps — numbered step cards (Your Experience)',
            'founder_teaser' => 'Founder teaser — photo + short bio with link',
            'features' => 'Features — differentiator cards (Why HeartWell)',
            'group_individual' => 'Group vs individual — two-column comparison',
            'faq' => 'FAQ — questions and answers on this page',
            'rich_text' => 'Rich text — longer story or educational content',
            'testimonials' => 'Testimonials — client quotes (Home or trust pages)',
            'pathways_teaser' => 'Pathways teaser — preview of support pathways (Home)',
            'cta' => 'Call to action — button band at bottom of section',
            'forms' => 'Contact forms — waitlist, consultation, booking (Contact page)',
        ];
    }

    public static function imageUploadHelper(): string
    {
        return 'Recommended 1200×630 for hero images. JPG or WebP, max 2 MB.';
    }
}
