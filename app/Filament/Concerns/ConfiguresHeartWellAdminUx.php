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

    public static function sectionImageMaxSizeKb(): int
    {
        return 10240;
    }

    public static function imageUploadHelper(): string
    {
        return 'Crop to 4:3 (1200×900) for hero sections. PNG, JPG, or WebP, max 10 MB.';
    }

    public static function heroDesktopImageHelper(): string
    {
        return 'Desktop (≥1024px). Recommended size: 1680×940 px (landscape ~16:9) for split heroes such as Wellness Journey; ~1450×1100 for full-bleed banners (Why HeartWell). PNG, JPG, or WebP, max 10 MB.';
    }

    public static function heroMobileImageHelper(): string
    {
        return 'Mobile (≤1023px). Recommended size when you have a dedicated crop: 1125×1400 px (portrait ~4:5). If empty, the desktop image is used on mobile. For now you can upload the same desktop file. PNG, JPG, or WebP, max 10 MB.';
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
