<?php

namespace App\Filament\Concerns;

use App\Domains\Content\Support\SectionLayout;
use Filament\Forms;

trait ConfiguresSectionFields
{
    /**
     * @return array<int, Forms\Components\Component>
     */
    protected static function layoutFieldsetSchema(): array
    {
        return [
            static::layoutFormSection(),
        ];
    }

    protected static function layoutFormSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Layout & appearance')
            ->icon('heroicon-o-paint-brush')
            ->schema([
                Forms\Components\Select::make('layout_container_width')
                    ->label('Container width')
                    ->options(static::layoutSelectOptions())
                    ->placeholder('Standard (site default — 72rem)'),
                Forms\Components\Select::make('layout_section_padding')
                    ->label('Section padding')
                    ->options([
                        'none' => 'None',
                        'compact' => 'Compact',
                        'normal' => 'Normal',
                        'spacious' => 'Spacious',
                    ])
                    ->placeholder('Use site default'),
                Forms\Components\Select::make('layout_background')
                    ->label('Background')
                    ->options([
                        'white' => 'White',
                        'cream' => 'Cream',
                        'blush' => 'Blush',
                        'dusty_blue' => 'Dusty blue',
                        'taupe' => 'Taupe',
                        'transparent' => 'Transparent',
                    ])
                    ->placeholder('Use site default'),
                Forms\Components\Select::make('layout_text_align')
                    ->label('Text alignment')
                    ->options([
                        'left' => 'Left',
                        'center' => 'Center',
                    ])
                    ->placeholder('Use section default'),
            ])
            ->columns(2)
            ->columnSpanFull();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected static function mergeLayoutIntoContent(array $data): array
    {
        $content = is_array($data['content'] ?? null) ? $data['content'] : [];
        $layout = is_array($content['layout'] ?? null) ? $content['layout'] : [];
        $changed = false;

        foreach ([
            'container_width' => 'layout_container_width',
            'section_padding' => 'layout_section_padding',
            'background' => 'layout_background',
            'text_align' => 'layout_text_align',
        ] as $layoutKey => $formKey) {
            if (! array_key_exists($formKey, $data)) {
                continue;
            }

            $changed = true;

            if (filled($data[$formKey])) {
                $layout[$layoutKey] = $data[$formKey];
            } else {
                unset($layout[$layoutKey]);
            }
        }

        if ($changed) {
            if ($layout === []) {
                unset($content['layout']);
            } else {
                $content['layout'] = $layout;
            }

            $data['content'] = $content;
            $data['layout'] = $layout;
        }

        unset(
            $data['layout_container_width'],
            $data['layout_section_padding'],
            $data['layout_background'],
            $data['layout_text_align'],
        );

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected static function hydrateLayoutFromContent(array $data): array
    {
        $contentLayout = is_array($data['content']['layout'] ?? null) ? $data['content']['layout'] : [];
        $columnLayout = is_array($data['layout'] ?? null) ? $data['layout'] : [];
        $layout = array_merge($contentLayout, $columnLayout);

        $data['layout_container_width'] = $layout['container_width'] ?? null;
        $data['layout_section_padding'] = $layout['section_padding'] ?? null;
        $data['layout_background'] = $layout['background'] ?? null;
        $data['layout_text_align'] = $layout['text_align'] ?? null;

        return $data;
    }

    /**
     * @return array<string, string>
     */
    public static function layoutSelectOptions(): array
    {
        return [
            'full' => 'Full width (100%)',
            'near_full' => 'Near full (120rem)',
            'extra_wide' => 'Extra wide (102rem)',
            'expanded' => 'Expanded (96rem)',
            'wide' => 'Wide (84rem)',
            'comfortable' => 'Comfortable (78rem)',
            'default' => 'Standard (72rem)',
            'narrow' => 'Narrow',
            'form' => 'Form width',
            'prose' => 'Prose / reading width',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function themeColorFields(): array
    {
        return [
            'navy' => 'Primary / Navy',
            'heading' => 'Heading color',
            'dusty_blue' => 'Accent / Dusty blue',
            'blush' => 'Blush',
            'taupe' => 'Taupe',
            'text' => 'Body text',
            'muted' => 'Muted text',
            'border' => 'Border',
            'blush_light' => 'Blush light',
            'dusty_blue_light' => 'Dusty blue light',
            'taupe_light' => 'Taupe light',
            'white' => 'Background white',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function defaultThemeColors(): array
    {
        return SectionLayout::defaultThemeColors();
    }
}
