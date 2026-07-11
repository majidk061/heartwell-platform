<?php

namespace App\Domains\Content\Support;

class SectionDesignRegistry
{
    /**
     * @return array<string, string>
     */
    public static function variantsFor(string $sectionType): array
    {
        return match ($sectionType) {
            'hero' => [
                'default' => 'Split — image right (classic)',
                'split_image_right' => 'Split — image right (client mock)',
                'image_left' => 'Split — image left',
                'full_bleed_overlay' => 'Full-width image with text overlay',
                'centered_overlay' => 'Centered text over image',
                'minimal' => 'Minimal — text only band',
                'responsive_art' => 'Responsive artwork — desktop/mobile hero',
                'split_image_quotes' => 'Split hero with floating quotes (Why HeartWell)',
                'journey_split_hero' => 'Split hero — wellness journey (copy + quotes)',
            ],
            'avatar_intro' => [
                'default' => 'Vertical portrait cards (classic)',
                'horizontal_split_cards' => 'Horizontal cards — image left (client mock)',
                'heading_band' => 'Heading band + cards below',
                'quote_emphasis' => 'Quote emphasis above cards',
            ],
            'pathway_bar' => [
                'divided_bar' => 'Divided bar (classic)',
                'labeled_inline_dividers' => 'Inline labels + coral dividers (client mock)',
                'pill_row' => 'Pill row — scroll on mobile',
                'card_strip' => 'Card strip',
            ],
            'founder_teaser' => [
                'default' => 'Photo left (classic)',
                'photo_left' => 'Photo left (client mock)',
                'photo_right' => 'Photo right',
            ],
            'cta' => [
                'default' => 'Inline action bar (client mock)',
                'inline_action_bar' => 'Inline action bar — text left, buttons right',
                'elevated_card' => 'Elevated card in section',
                'split_guidance' => 'Split editorial — copy left, action panel right',
                'centered_band' => 'Centered cream band (client mock)',
                'band_full_width' => 'Full-width inline bar',
                'single_primary' => 'Single primary button',
            ],
            'intro' => [
                'default' => 'Centered text + optional image',
                'compliance_callout' => 'Clinical intake & clearance callout',
                'image_side' => 'Text and image side by side',
                'image_below' => 'Image below text',
                'reflective_quotes' => 'Reflective private thoughts',
            ],
            'pathways_teaser' => [
                'accordion' => 'Accordion list (classic)',
                'pathway_cards' => 'Guided pathway cards (Support Pathways page)',
                'compact_list' => 'Compact linked list',
                'editorial_panels' => 'Editorial pathway panels (Wellness Journey)',
                'journey_pathway_grid' => 'Bordered pathway grid (Wellness Journey)',
            ],
            'journey' => [
                'default' => 'Horizontal numbered cards',
                'vertical_timeline' => 'Vertical timeline',
            ],
            'features' => [
                'default' => 'Three-column grid',
                'two_column' => 'Two-column large cards',
                'five_column_dividers' => 'Five columns with dividers (Why HeartWell)',
            ],
            'testimonials' => [
                'default' => 'Grid or carousel',
            ],
            'faq' => [
                'default' => 'Accordion',
            ],
            'rich_text' => [
                'default' => 'Prose block',
                'image_inset' => 'Image inset left',
                'journey_step' => 'Journey step prose',
                'image_feature' => 'Prose with feature image',
                'warm_close' => 'Warm closing prose',
                'journey_expect_split' => 'Split copy + expect panel (Wellness Journey)',
                'journey_cta_split' => 'Split copy + CTA panel (Wellness Journey)',
                'editorial_bridge' => 'Editorial bridge — centered accent lines',
                'three_column_narrative' => 'Three-column narrative with dividers',
            ],
            'group_individual' => [
                'default' => 'Side-by-side columns',
                'dual_start_options' => 'Dual balanced start options',
                'dual_start_cream' => 'Dual start options — cream band (Wellness Journey)',
            ],
            'forms' => [
                'default' => 'Tabbed contact forms',
            ],
            default => [
                'default' => 'Standard layout',
            ],
        };
    }

    public static function defaultVariant(string $sectionType): string
    {
        $variants = self::variantsFor($sectionType);

        if (isset($variants['default'])) {
            return 'default';
        }

        return array_key_first($variants) ?? 'default';
    }

    /**
     * @param  array<string, mixed>  $content
     */
    public static function resolveVariant(string $sectionType, array $content): string
    {
        $requested = $content['design_variant'] ?? null;
        $variants = self::variantsFor($sectionType);

        if (is_string($requested) && isset($variants[$requested])) {
            return $requested;
        }

        return self::defaultVariant($sectionType);
    }

    public static function viewName(string $sectionType, array $content): string
    {
        $variant = self::resolveVariant($sectionType, $content);
        $candidate = "components.sections.{$sectionType}.{$variant}";

        if (view()->exists($candidate)) {
            return $candidate;
        }

        $fallback = "components.sections.{$sectionType}.default";

        return view()->exists($fallback) ? $fallback : '';
    }
}
