<?php

namespace App\Domains\Content\Support;

class SectionLayout
{
    /** @var list<string> */
    public const CONTAINER_WIDTHS = ['full', 'wide', 'default', 'narrow', 'form', 'prose'];

    /** @var list<string> */
    public const SECTION_PADDINGS = ['none', 'compact', 'normal', 'spacious'];

    /** @var list<string> */
    public const BACKGROUNDS = ['white', 'blush', 'dusty_blue', 'taupe', 'transparent'];

    /** @var list<string> */
    public const TEXT_ALIGNS = ['left', 'center'];

    /** @var list<string> */
    public const SITE_WIDTHS = ['standard', 'wide', 'full'];

    /** @var list<string> */
    public const HEADER_MODES = ['sticky', 'static'];

    /** @var list<string> */
    public const HEADER_STYLES = ['solid', 'transparent_blur'];

    /**
     * @param  array<string, mixed>  $content
     * @param  array<string, mixed>|null  $themeDefaults
     * @param  array<string, mixed>  $typeDefaults
     * @return array{container_width: string, section_padding: string, background: string, text_align: string}
     */
    public static function resolve(array $content, ?array $themeDefaults = null, ?string $sectionType = null, array $typeDefaults = []): array
    {
        $layout = is_array($content['layout'] ?? null) ? $content['layout'] : [];
        $theme = $themeDefaults ?? [];

        $containerWidth = $layout['container_width']
            ?? $typeDefaults['container_width']
            ?? ($theme['default_container_width'] ?? null)
            ?? self::defaultWidthForType($sectionType);

        $sectionPadding = $layout['section_padding']
            ?? $typeDefaults['section_padding']
            ?? ($theme['default_section_padding'] ?? 'normal');

        $background = $layout['background']
            ?? $typeDefaults['background']
            ?? ($theme['default_section_background'] ?? null)
            ?? self::defaultBackgroundForType($sectionType);

        $textAlign = $layout['text_align']
            ?? $typeDefaults['text_align']
            ?? self::defaultAlignForType($sectionType);

        return [
            'container_width' => in_array($containerWidth, self::CONTAINER_WIDTHS, true) ? $containerWidth : 'default',
            'section_padding' => in_array($sectionPadding, self::SECTION_PADDINGS, true) ? $sectionPadding : 'normal',
            'background' => in_array($background, self::BACKGROUNDS, true) ? $background : 'white',
            'text_align' => in_array($textAlign, self::TEXT_ALIGNS, true) ? $textAlign : 'center',
        ];
    }

    /**
     * @param  array{container_width: string, section_padding: string, background: string, text_align: string}  $layout
     */
    public static function sectionClasses(array $layout): string
    {
        $classes = ['hw-section'];

        $classes[] = match ($layout['section_padding']) {
            'none' => 'hw-section--padding-none',
            'compact' => 'hw-section--padding-compact',
            'spacious' => 'hw-section--padding-spacious',
            default => '',
        };

        $classes[] = match ($layout['background']) {
            'blush' => 'bg-hw-blush-light/40',
            'dusty_blue' => 'bg-hw-dusty-blue-light/40',
            'taupe' => 'bg-hw-taupe-light/30',
            'transparent' => 'bg-transparent',
            default => 'bg-hw-white',
        };

        if ($layout['text_align'] === 'center') {
            $classes[] = 'text-center';
        }

        return trim(implode(' ', array_filter($classes)));
    }

    public static function defaultWidthForType(?string $sectionType): string
    {
        return match ($sectionType) {
            'intro', 'rich_text', 'faq' => 'narrow',
            'forms' => 'default',
            default => 'default',
        };
    }

    public static function defaultBackgroundForType(?string $sectionType): string
    {
        return match ($sectionType) {
            'intro' => 'dusty_blue',
            'group_individual' => 'dusty_blue',
            'faq' => 'taupe',
            default => 'white',
        };
    }

    public static function defaultAlignForType(?string $sectionType): string
    {
        return match ($sectionType) {
            'rich_text' => 'left',
            default => 'center',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function defaultThemeColors(): array
    {
        return [
            'navy' => '#1b2b4b',
            'heading' => '#1b2b4b',
            'dusty_blue' => '#7ba7bc',
            'blush' => '#e8b4b8',
            'taupe' => '#c4b8ae',
            'text' => '#2d2d2d',
            'muted' => '#6b6b6b',
            'border' => '#e5e0dc',
            'blush_light' => '#f5e4e6',
            'dusty_blue_light' => '#e8f0f4',
            'taupe_light' => '#f0ebe8',
            'white' => '#ffffff',
        ];
    }

    public static function defaultRobotsTxt(): string
    {
        return "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /webhooks/\n\nSitemap: ".url('/sitemap.xml');
    }
}
