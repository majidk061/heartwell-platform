<?php

namespace App\Filament\Concerns;

use App\Domains\Content\Support\CmsImage;
use App\Domains\Content\Support\SectionDesignRegistry;
use Filament\Forms;

trait MutatesSectionContent
{
    /**
     * @return array<int, Forms\Components\Component>
     */
    protected static function sectionContentFormSchema(): array
    {
        return [
            Forms\Components\Select::make('content_design_variant')
                ->label('Design variant')
                ->options(fn (Forms\Get $get): array => \App\Domains\Content\Support\SectionDesignRegistry::variantsFor(
                    (string) ($get('section_type') ?: 'hero')
                ))
                ->default(fn (Forms\Get $get): string => \App\Domains\Content\Support\SectionDesignRegistry::defaultVariant(
                    (string) ($get('section_type') ?: 'hero')
                ))
                ->helperText('Choose the visual layout for this section. Preview before publishing.')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_intro_question')
                ->label('Intro question line')
                ->helperText('Optional line above body, e.g. “Feeling exhausted? Stuck?”')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero')
                ->columnSpanFull(),
            Forms\Components\Select::make('content_pathway_bar_variant')
                ->label('Pathway bar style')
                ->options(\App\Domains\Content\Support\SectionDesignRegistry::variantsFor('pathway_bar'))
                ->default('labeled_inline_dividers')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero')
                ->helperText('Divided pathway links shown below this hero on the home page when pathways exist.'),
            Forms\Components\TextInput::make('content_pathway_bar_heading')
                ->label('Pathway bar heading')
                ->default('Support Pathways Include:')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero'),
            Forms\Components\Toggle::make('content_show_pathway_bar')
                ->label('Show pathway bar below hero')
                ->default(true)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero')
                ->helperText('When enabled, support pathway links appear under this hero on the home page only.'),
            Forms\Components\Toggle::make('content_show_consultation_link')
                ->label('Show consultation link under hero buttons')
                ->default(true)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero'),
            Forms\Components\TextInput::make('content_subheading')
                ->label('Subheading')
                ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser', 'avatar_intro', 'cta']))
                ->prefixIcon('heroicon-o-chat-bubble-bottom-center-text'),
            Forms\Components\Textarea::make('content_body')
                ->label('Body text')
                ->rows(4)
                ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser', 'cta', 'avatar_intro']))
                ->columnSpanFull(),
            Forms\Components\RichEditor::make('content_rich_body')
                ->label('Content')
                ->toolbarButtons(['h2', 'bold', 'italic', 'link', 'bulletList', 'orderedList'])
                ->extraInputAttributes(['class' => 'prose prose-hw'])
                ->helperText('Headings, paragraph spacing, and lists match the public site preview.')
                ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['rich_text', 'group_individual']))
                ->columnSpanFull(),
            Forms\Components\FileUpload::make('content_image')
                ->label('Section image')
                ->disk('public')
                ->directory('cms/sections')
                ->visibility('public')
                ->image()
                ->imageEditor()
                ->imageEditorAspectRatios(['4:3', '1:1'])
                ->maxSize(static::sectionImageMaxSizeKb())
                ->helperText(static::imageUploadHelper())
                ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser', 'rich_text'])
                    && ! ($get('section_type') === 'hero' && $get('content_design_variant') === 'minimal'))
                ->columnSpanFull(),
            Forms\Components\Placeholder::make('content_image_preview')
                ->label('Current image')
                ->content(function (?object $record): \Illuminate\Support\HtmlString {
                    $path = is_array($record?->content ?? null) ? ($record->content['image_url'] ?? null) : null;

                    if (blank($path)) {
                        return new \Illuminate\Support\HtmlString('<span class="text-sm text-gray-500">No image uploaded.</span>');
                    }

                    $url = CmsImage::url($path);

                    return new \Illuminate\Support\HtmlString(
                        '<div class="space-y-2">'
                        .'<img src="'.e((string) $url).'" alt="" class="max-h-48 rounded-lg border border-gray-200 object-cover" />'
                        .(CmsImage::isExternalUrl($path)
                            ? '<p class="text-xs text-gray-500">External image — upload a new file above to replace it.</p>'
                            : '')
                        .'</div>'
                    );
                })
                ->visible(fn (Forms\Get $get, ?object $record): bool => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser', 'rich_text'])
                    && ! ($get('section_type') === 'hero' && $get('content_design_variant') === 'minimal')
                    && filled(is_array($record?->content ?? null) ? ($record->content['image_url'] ?? null) : null))
                ->columnSpanFull(),
            Forms\Components\Repeater::make('content_steps')
                ->label('Journey steps')
                ->schema([
                    Forms\Components\TextInput::make('title')->label('Step title')->required(),
                    Forms\Components\Textarea::make('description')->label('Step description')->rows(2),
                ])
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'journey')
                ->columnSpanFull(),
            Forms\Components\Repeater::make('content_features')
                ->label('Feature cards')
                ->schema([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\Textarea::make('body')->rows(2),
                ])
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'features')
                ->columnSpanFull(),
            Forms\Components\Repeater::make('content_columns')
                ->label('Comparison columns')
                ->schema([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\Textarea::make('body')->rows(3),
                ])
                ->maxItems(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'group_individual')
                ->columnSpanFull(),
            Forms\Components\Repeater::make('content_narrative_columns')
                ->label('Narrative columns')
                ->schema([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\TextInput::make('anchor')->label('Anchor ID (optional)'),
                    Forms\Components\RichEditor::make('body')->toolbarButtons(['bold', 'italic', 'link', 'bulletList'])->required(),
                ])
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'rich_text' && $get('content_design_variant') === 'three_column_narrative')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_eyebrow')
                ->label('Eyebrow label')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'journey_split_hero')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_hero_title')
                ->label('Hero title')
                ->helperText('Also editable via Section headline above.')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'journey_split_hero')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_lead_question')
                ->label('Lead question (italic line)')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'journey_split_hero')
                ->columnSpanFull(),
            Forms\Components\Toggle::make('content_show_floating_quotes')
                ->label('Overlay floating quotes on hero image')
                ->helperText('Leave off when the uploaded hero image already includes quote artwork (client composite). Enable only with a photo-only image.')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero'
                    && in_array($get('content_design_variant'), ['split_image_quotes', 'journey_split_hero'], true)),
            Forms\Components\Repeater::make('content_quotes')
                ->label('Floating quotes')
                ->schema([
                    Forms\Components\Textarea::make('text')->required()->rows(2),
                    Forms\Components\Select::make('position')
                        ->options([
                            'center-left' => 'Center left',
                            'top-right' => 'Top right',
                            'bottom-right' => 'Bottom right',
                        ])
                        ->required(),
                ])
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero'
                    && in_array($get('content_design_variant'), ['split_image_quotes', 'journey_split_hero'], true)
                    && (bool) $get('content_show_floating_quotes'))
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_title_lead')
                ->label('Title — lead text')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'split_image_quotes'),
            Forms\Components\TextInput::make('content_title_emphasis')
                ->label('Title — emphasized phrase')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'split_image_quotes'),
            Forms\Components\TextInput::make('content_title_tail')
                ->label('Title — closing phrase')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'split_image_quotes'),
            Forms\Components\TextInput::make('content_lower_heading')
                ->label('Lower block heading')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'split_image_quotes')
                ->columnSpanFull(),
            Forms\Components\RichEditor::make('content_lower_body')
                ->label('Lower block body')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'split_image_quotes')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_secondary_label')
                ->label('Secondary button label')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'split_image_quotes'),
            Forms\Components\TextInput::make('content_secondary_url')
                ->label('Secondary button URL')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero' && $get('content_design_variant') === 'split_image_quotes'),
            Forms\Components\Textarea::make('content_intro_text')
                ->label('Bridge intro line')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'rich_text' && $get('content_design_variant') === 'editorial_bridge')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('content_accent_line')
                ->label('Bridge accent line')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'rich_text' && $get('content_design_variant') === 'editorial_bridge')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('content_headline')
                ->label('Bridge headline')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'rich_text' && $get('content_design_variant') === 'editorial_bridge')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('content_emphasis_line')
                ->label('Bridge emphasis line')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'rich_text' && $get('content_design_variant') === 'editorial_bridge')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_closing_line')
                ->label('Closing line')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'features' && $get('content_design_variant') === 'five_column_dividers')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_closing_emphasis')
                ->label('Closing emphasis line')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'features' && $get('content_design_variant') === 'five_column_dividers')
                ->columnSpanFull(),
            Forms\Components\TagsInput::make('content_credentials')
                ->label('Credentials (e.g. BSN, RN, MBA)')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'founder_teaser')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_founder_name')
                ->label('Founder name')
                ->default('Jacquie Wilson')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'founder_teaser'),
            Forms\Components\TextInput::make('content_founder_role')
                ->label('Role / title')
                ->default('Founder & Director of Care')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'founder_teaser'),
            Forms\Components\TextInput::make('content_founder_pronunciation')
                ->label('Name pronunciation')
                ->placeholder('Pronounced Jack-Kwa')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'founder_teaser'),
            Forms\Components\Repeater::make('content_founder_subsections')
                ->label('Bio subsections')
                ->schema([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\Textarea::make('body')->rows(3)->required(),
                ])
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'founder_teaser')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_unifying_message')
                ->label('Unifying emotional line')
                ->helperText('e.g. "I don\'t feel like myself anymore."')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'avatar_intro')
                ->columnSpanFull(),
            Forms\Components\Toggle::make('content_enabled')
                ->label('Show section')
                ->default(true)
                ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['avatar_intro', 'testimonials', 'pathways_teaser', 'faq'])),
            Forms\Components\Select::make('content_avatar_columns')
                ->label('Card columns')
                ->options(['2' => '2 columns', '3' => '3 columns'])
                ->default('3')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'avatar_intro'),
            Forms\Components\Select::make('content_avatar_display_mode')
                ->label('Card layout')
                ->options(['grid' => 'Standard grid', 'compact' => 'Compact grid'])
                ->default('grid')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'avatar_intro'),
            Forms\Components\TextInput::make('content_max_cards')
                ->label('Max cards to show')
                ->numeric()
                ->minValue(1)
                ->maxValue(6)
                ->default(6)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'avatar_intro'),
            Forms\Components\Toggle::make('content_show_unifying_message')
                ->label('Show unifying message')
                ->default(true)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'avatar_intro'),
            Forms\Components\Select::make('content_variant')
                ->label('Button layout')
                ->options([
                    'dual' => 'Book + Waitlist',
                    'primary_only' => 'Primary button only',
                    'waitlist_only' => 'Waitlist button only',
                ])
                ->default('dual')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta'),
            Forms\Components\TextInput::make('content_primary_label')
                ->label('Primary button label')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta'),
            Forms\Components\TextInput::make('content_primary_url')
                ->label('Primary button URL')
                ->placeholder('/contact#book')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta'),
            Forms\Components\TextInput::make('content_waitlist_label')
                ->label('Waitlist button label')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta'),
            Forms\Components\TextInput::make('content_waitlist_url')
                ->label('Waitlist button URL')
                ->placeholder('/contact#waitlist')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta'),
            Forms\Components\Toggle::make('content_show_consultation_link')
                ->label('Show consultation link')
                ->default(true)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta'),
            Forms\Components\TextInput::make('content_consultation_prefix')
                ->label('Consultation prefix text')
                ->default('Prefer to talk first?')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta'),
            Forms\Components\TextInput::make('content_consultation_label')
                ->label('Consultation link label')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta'),
            Forms\Components\TextInput::make('content_consultation_url')
                ->label('Consultation link URL')
                ->placeholder('/contact#consultation')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta'),
            Forms\Components\TextInput::make('content_section_subtitle')
                ->label('Section subtitle')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('content_waitlist_title')
                ->label('Waitlist form title')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms'),
            Forms\Components\Textarea::make('content_waitlist_subtitle')
                ->label('Waitlist form subtitle')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms'),
            Forms\Components\TextInput::make('content_consultation_title')
                ->label('Consultation form title')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms'),
            Forms\Components\Textarea::make('content_consultation_subtitle')
                ->label('Consultation form subtitle')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms'),
            Forms\Components\TextInput::make('content_group_title')
                ->label('Group inquiry title')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms'),
            Forms\Components\Textarea::make('content_group_subtitle')
                ->label('Group inquiry subtitle')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms'),
            Forms\Components\Textarea::make('content_contact_disclaimer')
                ->label('Form disclaimer')
                ->rows(3)
                ->helperText('Shown below all contact forms on this page.')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('content_privacy_summary')
                ->label('Privacy summary')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('content_clinical_portal_note')
                ->label('Book appointment note')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('content_group_intake_note')
                ->label('Group inquiry note')
                ->rows(2)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms')
                ->columnSpanFull(),
            Forms\Components\Toggle::make('content_include_unassigned')
                ->label('Include unassigned FAQs')
                ->helperText('Also show FAQs with no page assigned.')
                ->default(false)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'faq'),
            Forms\Components\TextInput::make('content_faq_subtitle')
                ->label('FAQ subtitle')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'faq')
                ->columnSpanFull(),
            Forms\Components\Toggle::make('content_testimonials_enabled')
                ->label('Show testimonials')
                ->default(true)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'testimonials'),
            Forms\Components\TextInput::make('content_testimonials_subtitle')
                ->label('Testimonials subtitle')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'testimonials'),
            Forms\Components\Select::make('content_display_mode')
                ->label('Display mode')
                ->options(['grid' => 'Grid', 'carousel' => 'Carousel'])
                ->default('grid')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'testimonials'),
            Forms\Components\TextInput::make('content_count')
                ->label('Number to show')
                ->numeric()
                ->minValue(1)
                ->maxValue(24)
                ->default(6)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'testimonials'),
            Forms\Components\TextInput::make('content_carousel_visible')
                ->label('Slides visible (carousel)')
                ->numeric()
                ->minValue(1)
                ->maxValue(3)
                ->default(1)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'testimonials' && $get('content_display_mode') === 'carousel'),
            Forms\Components\Toggle::make('content_carousel_autoplay')
                ->label('Carousel autoplay')
                ->default(false)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'testimonials' && $get('content_display_mode') === 'carousel'),
            Forms\Components\TextInput::make('content_carousel_interval')
                ->label('Autoplay interval (seconds)')
                ->numeric()
                ->default(6)
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'testimonials' && $get('content_display_mode') === 'carousel'),
            Forms\Components\CheckboxList::make('content_forms')
                ->label('Forms to show')
                ->options([
                    'waitlist' => 'Waitlist',
                    'consultation' => 'Consultation',
                    'book' => 'Book appointment (Acuity)',
                    'group_inquiry' => 'Group inquiry',
                ])
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms')
                ->columnSpanFull(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected static function mutateSectionContent(array $data): array
    {
        $content = is_array($data['content'] ?? null) ? $data['content'] : [];

        if (! empty($data['content_subheading'])) {
            $content['subheading'] = $data['content_subheading'];
        }

        if (! empty($data['content_body'])) {
            $content['body'] = $data['content_body'];
        }

        if (! empty($data['content_rich_body'])) {
            $content['body'] = $data['content_rich_body'];
        }

        if (! empty($data['content_image'])) {
            $path = is_array($data['content_image'])
                ? ($data['content_image'][0] ?? null)
                : $data['content_image'];
            $content['image_url'] = static::normalizeSectionStoragePath($path);
        } elseif (filled($content['image_url'] ?? null)) {
            $content['image_url'] = static::normalizeSectionStoragePath($content['image_url']);
        }

        if (! empty($data['content_steps'])) {
            $content['steps'] = collect($data['content_steps'])
                ->map(fn (array $step) => [
                    'title' => $step['title'] ?? '',
                    'description' => $step['description'] ?? null,
                ])
                ->filter(fn (array $step) => $step['title'] !== '')
                ->values()
                ->all();
        }

        if (! empty($data['content_features'])) {
            $content['features'] = $data['content_features'];
        }

        if (! empty($data['content_columns'])) {
            $content['columns'] = $data['content_columns'];
        }

        if (! empty($data['content_narrative_columns'])) {
            $content['columns'] = $data['content_narrative_columns'];
        }

        if (! empty($data['content_quotes'])) {
            $content['quotes'] = $data['content_quotes'];
        }

        if (! empty($data['content_lower_body'])) {
            $content['lower_body'] = $data['content_lower_body'];
        }

        if (! empty($data['content_credentials'])) {
            $content['credentials'] = $data['content_credentials'];
        }

        if (! empty($data['content_founder_name'])) {
            $content['name'] = $data['content_founder_name'];
        }

        if (! empty($data['content_founder_role'])) {
            $content['role'] = $data['content_founder_role'];
        }

        if (! empty($data['content_founder_pronunciation'])) {
            $content['pronunciation'] = $data['content_founder_pronunciation'];
        }

        if (! empty($data['content_founder_subsections'])) {
            $content['subsections'] = collect($data['content_founder_subsections'])
                ->map(fn (array $block) => [
                    'title' => $block['title'] ?? '',
                    'body' => $block['body'] ?? '',
                ])
                ->filter(fn (array $block) => $block['title'] !== '')
                ->values()
                ->all();
        }

        if (! empty($data['content_forms'])) {
            $content['forms'] = $data['content_forms'];
        }

        foreach ([
            'design_variant' => 'content_design_variant',
            'intro_question' => 'content_intro_question',
            'pathway_bar_variant' => 'content_pathway_bar_variant',
            'pathway_bar_heading' => 'content_pathway_bar_heading',
            'show_pathway_bar' => 'content_show_pathway_bar',
            'enabled' => 'content_enabled',
            'card_columns' => 'content_avatar_columns',
            'max_cards' => 'content_max_cards',
            'show_unifying_message' => 'content_show_unifying_message',
            'unifying_message' => 'content_unifying_message',
            'variant' => 'content_variant',
            'primary_label' => 'content_primary_label',
            'primary_url' => 'content_primary_url',
            'waitlist_label' => 'content_waitlist_label',
            'waitlist_url' => 'content_waitlist_url',
            'show_consultation_link' => 'content_show_consultation_link',
            'show_floating_quotes' => 'content_show_floating_quotes',
            'consultation_prefix' => 'content_consultation_prefix',
            'consultation_label' => 'content_consultation_label',
            'consultation_url' => 'content_consultation_url',
            'section_subtitle' => 'content_section_subtitle',
            'waitlist_title' => 'content_waitlist_title',
            'waitlist_subtitle' => 'content_waitlist_subtitle',
            'consultation_title' => 'content_consultation_title',
            'consultation_subtitle' => 'content_consultation_subtitle',
            'group_title' => 'content_group_title',
            'group_subtitle' => 'content_group_subtitle',
            'contact_disclaimer' => 'content_contact_disclaimer',
            'privacy_summary' => 'content_privacy_summary',
            'clinical_portal_note' => 'content_clinical_portal_note',
            'group_intake_note' => 'content_group_intake_note',
            'include_unassigned' => 'content_include_unassigned',
            'faq_subtitle' => 'content_faq_subtitle',
            'subtitle' => 'content_testimonials_subtitle',
            'count' => 'content_count',
            'carousel_visible' => 'content_carousel_visible',
            'carousel_autoplay' => 'content_carousel_autoplay',
            'carousel_interval' => 'content_carousel_interval',
            'title_lead' => 'content_title_lead',
            'title_emphasis' => 'content_title_emphasis',
            'title_tail' => 'content_title_tail',
            'lower_heading' => 'content_lower_heading',
            'secondary_label' => 'content_secondary_label',
            'secondary_url' => 'content_secondary_url',
            'intro_text' => 'content_intro_text',
            'accent_line' => 'content_accent_line',
            'headline' => 'content_headline',
            'emphasis_line' => 'content_emphasis_line',
            'closing_line' => 'content_closing_line',
            'closing_emphasis' => 'content_closing_emphasis',
            'eyebrow' => 'content_eyebrow',
            'hero_title' => 'content_hero_title',
            'lead_question' => 'content_lead_question',
        ] as $contentKey => $formKey) {
            if (! array_key_exists($formKey, $data)) {
                continue;
            }

            $value = $data[$formKey];

            if (is_bool($value) || ($value !== null && $value !== '')) {
                $content[$contentKey] = $value;
            }
        }

        if (array_key_exists('content_display_mode', $data) && filled($data['content_display_mode'])) {
            $content['display_mode'] = $data['content_display_mode'];
        }

        if (array_key_exists('content_avatar_display_mode', $data) && filled($data['content_avatar_display_mode'])) {
            $content['display_mode'] = $data['content_avatar_display_mode'];
        }

        if (array_key_exists('content_testimonials_enabled', $data)) {
            $content['enabled'] = (bool) $data['content_testimonials_enabled'];
        }

        if (($data['section_type'] ?? '') === 'avatar_intro') {
            unset($content['columns']);
        }

        if (($content['design_variant'] ?? null) === 'journey_split_hero' && filled($data['heading'] ?? null)) {
            $content['hero_title'] = $data['heading'];
        }

        $data['content'] = $content;
        $data = static::mergeLayoutIntoContent($data);

        $layout = is_array($data['content']['layout'] ?? null) ? $data['content']['layout'] : [];
        $data['layout'] = $layout;

        unset(
            $data['content_subheading'],
            $data['content_body'],
            $data['content_rich_body'],
            $data['content_image'],
            $data['content_steps'],
            $data['content_features'],
            $data['content_columns'],
            $data['content_credentials'],
            $data['content_forms'],
            $data['content_unifying_message'],
            $data['content_variant'],
            $data['content_primary_label'],
            $data['content_primary_url'],
            $data['content_waitlist_label'],
            $data['content_waitlist_url'],
            $data['content_show_consultation_link'],
            $data['content_consultation_prefix'],
            $data['content_consultation_label'],
            $data['content_consultation_url'],
            $data['content_section_subtitle'],
            $data['content_waitlist_title'],
            $data['content_waitlist_subtitle'],
            $data['content_consultation_title'],
            $data['content_consultation_subtitle'],
            $data['content_group_title'],
            $data['content_group_subtitle'],
            $data['content_contact_disclaimer'],
            $data['content_privacy_summary'],
            $data['content_clinical_portal_note'],
            $data['content_group_intake_note'],
            $data['content_include_unassigned'],
            $data['content_faq_subtitle'],
            $data['content_testimonials_subtitle'],
            $data['content_testimonials_enabled'],
            $data['content_enabled'],
            $data['content_avatar_columns'],
            $data['content_avatar_display_mode'],
            $data['content_max_cards'],
            $data['content_show_unifying_message'],
            $data['content_display_mode'],
            $data['content_count'],
            $data['content_carousel_visible'],
            $data['content_carousel_autoplay'],
            $data['content_carousel_interval'],
            $data['content_design_variant'],
            $data['content_intro_question'],
            $data['content_pathway_bar_variant'],
            $data['content_pathway_bar_heading'],
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
    protected static function hydrateSectionContent(array $data): array
    {
        $content = is_array($data['content'] ?? null) ? $data['content'] : [];
        $layout = is_array($data['layout'] ?? null) && $data['layout'] !== []
            ? $data['layout']
            : ($content['layout'] ?? []);

        if ($layout !== [] && ! isset($content['layout'])) {
            $content['layout'] = $layout;
            $data['content'] = $content;
        }

        $data['content_subheading'] = $content['subheading'] ?? null;
        $data['content_body'] = $content['body'] ?? null;
        $data['content_rich_body'] = in_array($data['section_type'] ?? '', ['rich_text', 'group_individual'])
            ? ($content['body'] ?? null)
            : null;
        $data['content_image'] = isset($content['image_url'])
            ? (CmsImage::isExternalUrl($content['image_url'])
                ? []
                : [static::normalizeSectionStoragePath($content['image_url'])])
            : null;
        $data['content_steps'] = $content['steps'] ?? [];
        $data['content_features'] = $content['features'] ?? [];
        if (($data['section_type'] ?? '') === 'group_individual') {
            $data['content_columns'] = is_array($content['columns'] ?? null) ? $content['columns'] : [];
        } else {
            $data['content_columns'] = [];
        }

        $data['content_narrative_columns'] = ($data['section_type'] ?? '') === 'rich_text'
            ? (is_array($content['columns'] ?? null) ? $content['columns'] : [])
            : [];
        $data['content_quotes'] = is_array($content['quotes'] ?? null) ? $content['quotes'] : [];
        $data['content_show_floating_quotes'] = (bool) ($content['show_floating_quotes'] ?? false);
        $data['content_lower_body'] = $content['lower_body'] ?? null;
        $data['content_title_lead'] = $content['title_lead'] ?? null;
        $data['content_title_emphasis'] = $content['title_emphasis'] ?? null;
        $data['content_title_tail'] = $content['title_tail'] ?? null;
        $data['content_lower_heading'] = $content['lower_heading'] ?? null;
        $data['content_secondary_label'] = $content['secondary_label'] ?? null;
        $data['content_secondary_url'] = $content['secondary_url'] ?? null;
        $data['content_intro_text'] = $content['intro_text'] ?? null;
        $data['content_accent_line'] = $content['accent_line'] ?? null;
        $data['content_headline'] = $content['headline'] ?? null;
        $data['content_emphasis_line'] = $content['emphasis_line'] ?? null;
        $data['content_closing_line'] = $content['closing_line'] ?? null;
        $data['content_closing_emphasis'] = $content['closing_emphasis'] ?? null;
        $data['content_eyebrow'] = $content['eyebrow'] ?? null;
        $data['content_hero_title'] = $content['hero_title'] ?? null;
        $data['content_lead_question'] = $content['lead_question'] ?? null;

        $data['content_credentials'] = is_array($content['credentials'] ?? null) ? $content['credentials'] : [];
        $data['content_founder_name'] = $content['name'] ?? 'Jacquie Wilson';
        $data['content_founder_role'] = $content['role'] ?? 'Founder & Director of Care';
        $data['content_founder_pronunciation'] = $content['pronunciation'] ?? null;
        $data['content_founder_subsections'] = $content['subsections'] ?? [];
        $data['content_forms'] = is_array($content['forms'] ?? null) ? $content['forms'] : [];
        $data['content_unifying_message'] = $content['unifying_message'] ?? null;
        $data['content_enabled'] = $content['enabled'] ?? true;

        if (($data['section_type'] ?? '') === 'avatar_intro') {
            $cardColumns = $content['card_columns'] ?? null;

            if ($cardColumns === null && isset($content['columns']) && ! is_array($content['columns'])) {
                $cardColumns = $content['columns'];
            }

            $data['content_avatar_columns'] = (string) ($cardColumns ?? '3');
        } else {
            $data['content_avatar_columns'] = '3';
        }
        $data['content_avatar_display_mode'] = $content['display_mode'] ?? 'grid';
        $data['content_max_cards'] = $content['max_cards'] ?? 6;
        $data['content_show_unifying_message'] = $content['show_unifying_message'] ?? true;
        $data['content_variant'] = $content['variant'] ?? 'dual';
        $data['content_primary_label'] = $content['primary_label'] ?? null;
        $data['content_primary_url'] = $content['primary_url'] ?? null;
        $data['content_waitlist_label'] = $content['waitlist_label'] ?? null;
        $data['content_waitlist_url'] = $content['waitlist_url'] ?? null;
        $data['content_show_consultation_link'] = $content['show_consultation_link'] ?? true;
        $data['content_consultation_prefix'] = $content['consultation_prefix'] ?? 'Prefer to talk first?';
        $data['content_consultation_label'] = $content['consultation_label'] ?? null;
        $data['content_consultation_url'] = $content['consultation_url'] ?? null;
        $data['content_section_subtitle'] = $content['section_subtitle'] ?? null;
        $data['content_waitlist_title'] = $content['waitlist_title'] ?? null;
        $data['content_waitlist_subtitle'] = $content['waitlist_subtitle'] ?? null;
        $data['content_consultation_title'] = $content['consultation_title'] ?? null;
        $data['content_consultation_subtitle'] = $content['consultation_subtitle'] ?? null;
        $data['content_group_title'] = $content['group_title'] ?? null;
        $data['content_group_subtitle'] = $content['group_subtitle'] ?? null;
        $data['content_contact_disclaimer'] = $content['contact_disclaimer'] ?? null;
        $data['content_privacy_summary'] = $content['privacy_summary'] ?? null;
        $data['content_clinical_portal_note'] = $content['clinical_portal_note'] ?? null;
        $data['content_group_intake_note'] = $content['group_intake_note'] ?? null;
        $data['content_include_unassigned'] = $content['include_unassigned'] ?? false;
        $data['content_faq_subtitle'] = $content['faq_subtitle'] ?? null;
        $data['content_testimonials_subtitle'] = $content['subtitle'] ?? null;
        $data['content_testimonials_enabled'] = $content['enabled'] ?? true;
        $data['content_display_mode'] = $content['display_mode'] ?? 'grid';
        $data['content_count'] = $content['count'] ?? 6;
        $data['content_carousel_visible'] = $content['carousel_visible'] ?? 1;
        $data['content_carousel_autoplay'] = $content['carousel_autoplay'] ?? false;
        $data['content_carousel_interval'] = $content['carousel_interval'] ?? 6;
        $data['content_design_variant'] = $content['design_variant'] ?? SectionDesignRegistry::defaultVariant((string) ($data['section_type'] ?? 'hero'));
        $data['content_intro_question'] = $content['intro_question'] ?? null;
        $data['content_pathway_bar_variant'] = $content['pathway_bar_variant'] ?? 'labeled_inline_dividers';
        $data['content_pathway_bar_heading'] = $content['pathway_bar_heading'] ?? 'Support Pathways Include:';
        $data['content_show_pathway_bar'] = $content['show_pathway_bar'] ?? true;

        return static::hydrateLayoutFromContent($data);
    }

    protected static function normalizeSectionStoragePath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (CmsImage::isExternalUrl($path)) {
            return $path;
        }

        return ltrim(str_replace('/storage/', '', ltrim($path, '/')), '/');
    }
}
