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
        return 'Crop to 4:3 (1200×900) for hero sections. JPG or WebP, max 2 MB.';
    }

    public static function logoUploadHelper(): string
    {
        return 'Upload the approved HeartWell logo PNG. Vertical stacked logos: use 3:4 or 2:3 — avoid cropping off text. Max 2 MB.';
    }

    public static function faviconUploadHelper(): string
    {
        return 'Crop to square 1:1. Displays in browser tabs. Max 512 KB.';
    }

    public static function ogImageUploadHelper(): string
    {
        return 'Crop to 1200×630 (social share). Max 2 MB.';
    }

    public static function avatarCardUploadHelper(): string
    {
        return 'Crop to 4:5 portrait. Max 2 MB.';
    }

    public static function pathwayUploadHelper(): string
    {
        return 'Crop to 16:9 landscape. Max 2 MB.';
    }

    public static function testimonialUploadHelper(): string
    {
        return 'Crop to square 1:1. Max 2 MB.';
    }
}
