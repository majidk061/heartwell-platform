<?php

namespace App\Domains\Content\Support;

use App\Domains\CRM\Enums\AvatarType;

class ClientCopyCatalog
{
    public const CLINICAL_WORKFLOW_INTAKE = 'Before receiving services, clients complete required clinical intake, health history, consent forms, and provider screening through the Hydreight clinical workflow. Clinical clearance is required before treatment is provided.';

    public const CLINICAL_WORKFLOW_SEPARATE = 'Required clinical information is collected separately through the Hydreight clinical workflow.';

    public const CLINICAL_SAFETY_ONE_LINER = 'Required clinical screening and provider clearance are completed through the Hydreight clinical workflow before services are provided.';

    public const GROUP_INTAKE_GUESTS = 'Each guest participating in a HeartWell wellness gathering completes their own required clinical intake and provider screening through the Hydreight clinical workflow before receiving services.';

    public const COMPLIANCE_INTAKE = self::CLINICAL_WORKFLOW_INTAKE;

    public const PRE_FORM_GUIDANCE = 'Please complete the form below to share your general interest in HeartWell. This form is for general inquiry purposes only. Please do not include diagnoses, medical history, medication information, urgent concerns, or other sensitive health information.';

    public const FORM_THANK_YOU = 'Thank you for reaching out to HeartWell. Your message has been received. HeartWell will follow up with you soon to learn more about your interest and help guide the next step. Please do not use this form for urgent medical concerns. If you are experiencing a medical emergency, call 911.';

    /**
     * @return list<array<string, mixed>>
     */
    public static function navigation(): array
    {
        return [
            ['label' => 'Home', 'route' => 'home'],
            ['label' => 'Support Pathways', 'route' => 'support-pathways'],
            ['label' => 'Your Experience', 'route' => 'your-experience'],
            ['label' => 'Why HeartWell', 'route' => 'why-heartwell'],
            ['label' => 'Meet Jacquie', 'route' => 'meet-the-founder'],
            ['label' => 'Connect', 'route' => 'contact'],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function footerColumns(): array
    {
        return [
            [
                'title' => 'YOUR EXPERIENCE',
                'links' => [
                    ['label' => 'Wellness Journey', 'route' => 'wellness-journey'],
                    ['label' => 'How It Works', 'route' => 'your-experience', 'anchor' => 'what-to-expect'],
                    ['label' => 'What to Expect', 'route' => 'your-experience', 'anchor' => 'what-to-expect'],
                    ['label' => 'Safety & Standards', 'route' => 'why-heartwell', 'anchor' => 'clinically-supported'],
                ],
            ],
            [
                'title' => 'WHY HEARTWELL',
                'links' => [
                    ['label' => 'Whole-Person Care', 'route' => 'why-heartwell', 'anchor' => 'nurse-led-care'],
                    ['label' => 'Our Approach', 'route' => 'why-heartwell', 'anchor' => 'compassion-at-the-center'],
                    ['label' => 'Expert-Guided Care', 'route' => 'why-heartwell', 'anchor' => 'clinically-supported'],
                    ['label' => 'Flexible & Convenient', 'route' => 'your-experience', 'anchor' => 'designed-around-real-life'],
                ],
            ],
            [
                'title' => 'COMPANY',
                'links' => [
                    ['label' => 'Meet Jacquie', 'route' => 'meet-the-founder'],
                    ['label' => 'About HeartWell', 'route' => 'why-heartwell'],
                    ['label' => 'Connect', 'route' => 'contact'],
                    ['label' => 'Privacy Policy', 'route' => 'privacy'],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function siteCtas(): array
    {
        return [
            'primary' => [
                'label' => 'Request a Private Mobile Visit',
                'route' => 'contact',
                'anchor' => '#book',
            ],
            'secondary' => [
                'waitlist' => [
                    'label' => 'Join the Waitlist',
                    'route' => 'contact',
                    'anchor' => '#waitlist',
                ],
                'consultation' => [
                    'label' => 'Begin with a Private Wellness Conversation',
                    'route' => 'contact',
                    'anchor' => '#consultation',
                ],
                'gathering' => [
                    'label' => 'Plan a Wellness Gathering',
                    'route' => 'contact',
                    'anchor' => '#group-inquiry',
                ],
            ],
            'tertiary_prefix' => 'Prefer to talk first?',
            'tertiary_label' => 'Begin with a Private Wellness Conversation',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function complianceDefaults(): array
    {
        return [
            'footer_note' => 'HeartWell Aesthetics & Wellness provides nurse-led wellness support in New Jersey. '.self::CLINICAL_WORKFLOW_SEPARATE.' '.self::CLINICAL_WORKFLOW_INTAKE,
            'contact_disclaimer' => 'Information submitted through this form is used for general inquiry purposes only. It is not a substitute for emergency medical care. If you are experiencing a medical emergency, call 911.',
            'clinical_portal_note' => self::CLINICAL_WORKFLOW_SEPARATE.' '.self::CLINICAL_WORKFLOW_INTAKE,
            'group_intake_note' => self::GROUP_INTAKE_GUESTS,
            'privacy_summary' => 'HeartWell respects your privacy. Information submitted through the HeartWell website is used to respond to general inquiries, manage waitlist and visit interest, coordinate wellness gathering requests, and communicate about next steps. HeartWell does not sell your personal information.',
            'privacy_policy_title' => 'Privacy Policy',
            'privacy_policy_last_updated' => null,
            'privacy_policy_body' => self::privacyPolicyBodyHtml(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function contactFormsDefaults(): array
    {
        return [
            'waitlist_title' => 'Join the Waitlist',
            'waitlist_subtitle' => 'Receive updates about Private Wellness Conversation openings and mobile visit availability.',
            'consultation_title' => 'Begin with a Private Wellness Conversation',
            'consultation_subtitle' => 'Start with a low-pressure conversation about your general goals, questions, and where you may want to begin.',
            'book_subtitle' => 'Share your general interest and location so HeartWell can follow up regarding availability and next steps.',
            'group_title' => 'Plan a Wellness Gathering',
            'group_subtitle' => 'Explore a private wellness experience for a small group or community.',
        ];
    }

    public static function privacyPolicyBodyHtml(): string
    {
        return <<<'HTML'
<h2>Privacy Overview</h2>
<p>HeartWell respects your privacy. Information submitted through the HeartWell website is used to respond to general inquiries, manage waitlist and visit interest, coordinate wellness gathering requests, and communicate about next steps. HeartWell does not sell your personal information.</p>
<h2>Information Submitted Through This Website</h2>
<p>When you contact HeartWell, join the waitlist, request a visit, or express interest in a wellness gathering, we may collect general contact and inquiry information such as your name, email address, phone number, service interest, and communication preferences.</p>
<p>Please do not submit diagnoses, medical history, medication information, urgent concerns, or other sensitive health information through general website forms.</p>
<h2>How Website Information Is Used</h2>
<p>Information submitted through the HeartWell website may be used to respond to inquiries, manage waitlist and visit interest, coordinate wellness gathering requests, and communicate about next steps. General website forms are not used for clinical intake, diagnosis, or treatment documentation.</p>
<h2>Clinical Information</h2>
<p>Required clinical information is collected separately through the Hydreight clinical workflow.</p>
<p>Before services are provided, clients complete required clinical intake, health history, consent forms, and provider screening through that separate clinical workflow.</p>
<h2>Wellness Gatherings</h2>
<p>Each guest participating in a HeartWell wellness gathering completes their own required clinical intake and provider screening through the Hydreight clinical workflow before receiving services.</p>
<h2>Contact</h2>
<p>Questions about this website Privacy Policy may be submitted through the HeartWell Connect page.</p>
HTML;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function supportPathways(): array
    {
        return [
            [
                'slug' => 'recovery-hydration',
                'title' => 'Recovery & Hydration',
                'tagline' => 'For replenishment, travel, busy seasons, or feeling run down',
                'intro' => 'This pathway may be a good fit when you are looking for hydration and replenishment support after travel, increased activity, busy seasons, or times when your body may need extra support.',
                'options_may_include' => [
                    'IV hydration support',
                    'Targeted nutrient add-ons when available and clinically appropriate',
                    'Focused injection support based on your goals and provider review',
                ],
                'common_support' => null,
                'portal_cue' => "This pathway may appear as Hydration.\n\nHydration may include Lactated Ringer's or Sodium Chloride 0.9%, depending on availability, provider guidance, and clinical appropriateness.",
                'avatar_type' => AvatarType::Depleted,
                'cta_label' => 'Request a Private Mobile Visit',
                'cta_url' => '/contact#book',
            ],
            [
                'slug' => 'energy-wellness',
                'title' => 'Energy & Wellness',
                'tagline' => 'For low energy, busy seasons, and times when you do not feel like yourself',
                'intro' => 'This pathway may be a good fit when you are feeling depleted, less energized than usual, or stretched by the demands of work, family, caregiving, or everyday life.',
                'options_may_include' => [
                    'Nutrient-focused IV wellness support',
                    'Targeted vitamin injection support',
                    'A combination approach based on your goals and provider review',
                ],
                'common_support' => null,
                'portal_cue' => 'This pathway may appear as Energy, B12, B-Complex, or BPlex, depending on the option selected and what is available through the Hydreight clinical workflow.',
                'avatar_type' => AvatarType::Depleted,
                'cta_label' => 'Begin with a Private Wellness Conversation',
                'cta_url' => '/contact#consultation',
            ],
            [
                'slug' => 'metabolic-weight',
                'title' => 'Metabolic & Weight Support',
                'tagline' => 'For women feeling stuck despite their efforts',
                'intro' => 'This pathway may be a good fit when changes in weight, metabolism, appetite, or progress have left you feeling frustrated or unsure where to begin. Support starts with understanding your goals and completing the required clinical screening.',
                'options_may_include' => [
                    'Consultation-based metabolic and weight support',
                    'Clinically appropriate medication pathways when prescribed through the clinical workflow',
                    'Ongoing nurse-led education and support',
                ],
                'common_support' => null,
                'portal_cue' => 'This pathway may appear as MIC+B12, Lipo, Lipo Mino, Lipo Stat Plus, GLP-1, Semaglutide, or Tirzepatide, depending on the option selected and what is available through the Hydreight clinical workflow.',
                'avatar_type' => AvatarType::Frustrated,
                'cta_label' => 'Begin with a Private Wellness Conversation',
                'cta_url' => '/contact#consultation',
            ],
            [
                'slug' => 'specialized-support',
                'title' => 'Specialized Support',
                'tagline' => 'For more focused wellness goals that may require additional evaluation',
                'intro' => 'This pathway is designed for women exploring more specialized wellness support beyond foundational hydration or nutrient-based care. Options are considered individually and depend on your goals, required clinical screening, provider review, and clinical appropriateness.',
                'options_may_include' => [
                    'Consultation-based specialized wellness support',
                    'NAD+ support when available and clinically appropriate',
                    'A personalized approach guided by provider review',
                ],
                'common_support' => null,
                'portal_cue' => "This pathway may appear as NAD, NAD+, NAD IV, or NAD IM, depending on what is available through the Hydreight clinical workflow.\n\nSpecialized options may require additional screening or provider guidance before treatment is approved.",
                'avatar_type' => AvatarType::Frustrated,
                'cta_label' => 'Request a Private Mobile Visit',
                'cta_url' => '/contact#book',
                'migrate_from_slug' => 'advanced-cellular',
            ],
            [
                'slug' => 'precision-glow-therapy',
                'title' => 'Precision Glow Therapy',
                'tagline' => 'For visible changes in the mirror',
                'intro' => 'This pathway may be a good fit when changes in skin, hair, eyes, or overall glow are affecting how you feel when you look in the mirror. Support is guided by your goals, required screening, and the options currently available through HeartWell.',
                'options_may_include' => [
                    'Wellness support selected with visible changes in mind',
                    'Targeted options based on your goals and provider review',
                    'Select aesthetic services as they become available',
                ],
                'common_support' => null,
                'portal_cue' => 'This pathway may appear as Beauty/Youth, Glutathione, Biotin, or related wellness options depending on what is available through the Hydreight clinical workflow.',
                'coming_soon' => 'Select aesthetic services, including neurotoxins, will be available soon.',
                'avatar_type' => AvatarType::Confidence,
                'cta_label' => 'Request a Private Mobile Visit',
                'cta_url' => '/contact#book',
                'migrate_from_slug' => 'confidence-aesthetic',
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function avatarCards(): array
    {
        return [
            [
                'slug' => 'depleted',
                'headline' => "I'm functioning… but exhausted.",
                'subtext' => 'Low energy, fatigue, burnout, and brain fog — you deserve support that meets you where you are.',
                'cta_label' => 'Explore Energy & Wellness',
                'pathway_slug' => 'energy-wellness',
            ],
            [
                'slug' => 'frustrated',
                'headline' => "I'm trying, but I feel stuck.",
                'subtext' => 'Weight changes, metabolism shifts, and resistance despite effort — clarity is possible.',
                'cta_label' => 'Explore Metabolic & Weight Support',
                'pathway_slug' => 'metabolic-weight',
            ],
            [
                'slug' => 'confidence',
                'headline' => 'How I see myself is changing.',
                'subtext' => 'Visible changes in skin, eyes, or hair — thoughtful support for every stage of life.',
                'cta_label' => 'Explore Precision Glow Therapy',
                'pathway_slug' => 'precision-glow-therapy',
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function faqs(): array
    {
        return [
            [
                'key' => 'service-selection',
                'question' => 'Do I need to know exactly what service I want before reaching out?',
                'answer' => 'No. You do not need to choose a treatment or know exactly what you need before contacting HeartWell. A Private Wellness Conversation gives you an opportunity to share your general goals, ask questions, and better understand which next step may be appropriate.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'clinical-screening',
                'question' => 'Is clinical screening required before I receive care?',
                'answer' => 'Yes. Before receiving services, clients complete required clinical intake, health history, consent forms, and provider screening through the Hydreight clinical workflow. Clinical clearance is required before treatment is provided.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'visit-location',
                'question' => 'Where do HeartWell visits take place?',
                'answer' => 'HeartWell provides mobile wellness visits in appropriate private settings based on service area, scheduling, and visit requirements. This may include your home or another suitable private location.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'support-types',
                'question' => 'What types of wellness support does HeartWell offer?',
                'answer' => 'HeartWell offers nurse-led wellness support through pathways focused on Recovery & Hydration, Energy & Wellness, Metabolic & Weight Support, Specialized Support, and Precision Glow Therapy. Available options depend on required screening, provider review, and clinical appropriateness.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'wellness-gathering',
                'question' => 'Can I host a private wellness gathering?',
                'answer' => 'Yes. HeartWell offers private wellness gatherings for small groups and communities. Each guest completes their own required clinical intake and provider screening through the Hydreight clinical workflow before receiving services.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'guaranteed-results',
                'question' => 'Are specific health, wellness, weight-loss, or aesthetic results guaranteed?',
                'answer' => 'No. Individual experiences and outcomes vary, and specific results are not guaranteed. Available services depend on required clinical screening, provider review, clinical appropriateness, and individual response.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'private-conversation',
                'question' => 'What happens during a Private Wellness Conversation?',
                'answer' => 'A Private Wellness Conversation is a low-pressure opportunity to share your general goals, ask questions, and explore what type of HeartWell support may be worth considering. It is not a diagnosis, medical evaluation, or substitute for required clinical screening.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'after-visit',
                'question' => 'What should I expect after my visit?',
                'answer' => 'HeartWell provides thoughtful follow-up to help you understand appropriate next steps and stay connected to your wellness journey.',
                'page_slug' => 'wellness-journey',
            ],
        ];
    }

    /**
     * Section templates to create or update (by name).
     *
     * @return array<string, array<string, mixed>>
     */
    public static function sectionTemplates(): array
    {
        $ivGuidanceBody = '<p>A full IV wellness visit may be a good fit when you are looking for hydration, replenishment, and broader wellness support.</p>'
            .'<p>A targeted injection may be a good fit when you want focused nutrient support without a full IV visit.</p>'
            .'<p>An add-on may be considered when you are already choosing IV hydration and want additional support for energy, metabolism, antioxidant wellness, or skin, hair, and nail wellness.</p>'
            .'<p><strong>Helpful selection note:</strong> Please choose the option that best matches your primary goal. You usually do not need to select multiple similar vitamin options, such as both B12 and B-Complex / BPlex. Required clinical screening and provider review will help determine what is clinically appropriate before treatment is provided.</p>';

        $finalNoteBody = '<p>Your care is guided by your goals, health history, required clinical screening, and provider recommendations. Available options may vary based on individual needs and clinical appropriateness.</p>';

        $ctas = self::siteCtas();

        return [
            'Hero — full bleed overlay' => [
                'section_type' => 'hero',
                'heading' => 'Thoughtful, Compassionate Care You Can Trust',
                'description' => 'Home page hero — full bleed overlay (preserves existing design_variant in DB).',
                'content' => [
                    'subheading' => 'For Every Stage of Life',
                    'intro_question' => 'Feeling exhausted? Stuck? Not feeling like yourself?',
                    'body' => 'HeartWell provides personalized, nurse-led wellness support for women navigating changes in energy, recovery, metabolism, and overall well-being.',
                    'pathway_bar_heading' => 'Support Pathways Include:',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Intro — home nurse-led care' => [
                'section_type' => 'intro',
                'heading' => 'Nurse-Led Wellness Support',
                'description' => 'Home page nurse-led intro paragraph.',
                'content' => [
                    'body' => 'HeartWell is mobile wellness led by Jacquie Wilson, BSN, RN, MBA — warm, nurse-led, and built around your whole story. We are not a spa, med spa, or IV menu. We offer thoughtful guidance for women navigating midlife transitions, burnout, metabolic changes, and times when they simply do not feel like themselves.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Hero — support pathways' => [
                'section_type' => 'hero',
                'heading' => 'Support Pathways',
                'description' => 'Support Pathways page hero — text-only band (no image).',
                'content' => [
                    'design_variant' => 'minimal',
                    'subheading' => 'Thoughtful Wellness Support, Guided by Your Goals',
                    'show_pathway_bar' => false,
                    'pathway_bar_variant' => 'labeled_inline_dividers',
                    'pathway_bar_heading' => 'Support Pathways Include:',
                    'body' => "HeartWell Support Pathways are designed to help you begin with how you feel and what you hope to address — not with a confusing treatment menu. Each pathway offers a starting point for exploring the type of support that may fit your goals. Final care options depend on required clinical intake, provider screening, and clinical appropriateness.",
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Intro — clinical intake clearance' => [
                'section_type' => 'intro',
                'heading' => 'Required Clinical Intake & Clearance',
                'description' => 'Prominent NJ compliance callout for Support Pathways.',
                'content' => [
                    'design_variant' => 'compliance_callout',
                    'body' => self::CLINICAL_WORKFLOW_INTAKE,
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            'Rich text — IV injection add-ons' => [
                'section_type' => 'rich_text',
                'heading' => 'Choosing IV Support, Injections, or Add-Ons',
                'description' => 'IV, injection, and add-on guidance for Support Pathways.',
                'content' => [
                    'body' => $ivGuidanceBody,
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Pathways teaser — guided cards' => [
                'section_type' => 'pathways_teaser',
                'heading' => null,
                'description' => 'Guided pathway cards for Support Pathways page.',
                'content' => [
                    'design_variant' => 'pathway_cards',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Rich text — final treatment selection' => [
                'section_type' => 'rich_text',
                'heading' => 'A Note About Final Treatment Selection',
                'description' => 'Final treatment selection note for Support Pathways.',
                'content' => [
                    'body' => $finalNoteBody,
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Journey — Hydreight portal flow' => [
                'section_type' => 'journey',
                'heading' => 'What Happens After You Choose a Pathway',
                'description' => 'Four-step Hydreight clinical workflow.',
                'content' => [
                    'steps' => [
                        ['title' => 'Hydreight Clinical Workflow', 'description' => 'You may see clinical treatment names that differ from HeartWell pathway names.'],
                        ['title' => 'Intake + Consent Forms', 'description' => 'Complete your health history and consent forms through the Hydreight clinical workflow.'],
                        ['title' => 'Provider Screening', 'description' => 'A provider reviews your information to confirm clinical appropriateness.'],
                        ['title' => 'Clinical Clearance', 'description' => 'Treatment is provided only after required clearance is confirmed.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'taupe'],
                ],
            ],
            'CTA — support pathways' => [
                'section_type' => 'cta',
                'heading' => 'Find the pathway that fits you',
                'description' => 'Support Pathways page CTA.',
                'content' => [
                    'body' => 'Not sure where to start? Begin with a private wellness conversation — we will guide you without pressure.',
                    'variant' => 'dual',
                    'primary_label' => $ctas['primary']['label'],
                    'primary_url' => '/contact#book',
                    'waitlist_label' => $ctas['secondary']['waitlist']['label'],
                    'waitlist_url' => '/contact#waitlist',
                    'show_consultation_link' => true,
                    'consultation_prefix' => $ctas['tertiary_prefix'],
                    'consultation_label' => $ctas['tertiary_label'],
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            'Hero — your experience' => [
                'section_type' => 'hero',
                'heading' => 'Your HeartWell Experience',
                'description' => 'Your Experience page hero — text-only minimal band.',
                'content' => [
                    'design_variant' => 'minimal',
                    'show_pathway_bar' => false,
                    'show_consultation_link' => true,
                    'body' => 'From your very first message to your wellness visit, HeartWell is designed to feel thoughtful, supportive, and easy to understand.',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Journey steps — 5 steps' => [
                'section_type' => 'journey',
                'heading' => 'What to expect',
                'description' => 'Five-step journey for Your Experience.',
                'content' => [
                    'section_anchor' => 'what-to-expect',
                    'steps' => [
                        ['title' => 'Connecting & Understanding', 'description' => 'Your experience begins with a private conversation focused on your goals, questions, and what you are hoping to better understand.'],
                        ['title' => 'Secure Clinical Intake', 'description' => 'Before receiving services, you complete required clinical intake, health history, consent forms, and provider screening through the Hydreight clinical workflow.'],
                        ['title' => 'Individualized, Nurse-Led Care', 'description' => 'Every visit is nurse-led and guided by your goals, required screening, and the care plan approved for you.'],
                        ['title' => 'Your Private HeartWell Visit', 'description' => 'Your visit is designed to feel calm, personal, and convenient, with care provided in an appropriate private setting based on service availability and visit requirements.', 'anchor' => 'designed-around-real-life'],
                        ['title' => 'Thoughtful Follow-Up', 'description' => 'Your HeartWell experience does not end when the visit ends. Thoughtful follow-up helps you understand appropriate next steps and stay connected to your wellness journey.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Hero — why heartwell' => [
                'section_type' => 'hero',
                'heading' => 'Why HeartWell',
                'description' => 'Why HeartWell page hero — text-only minimal band.',
                'content' => [
                    'design_variant' => 'minimal',
                    'show_pathway_bar' => false,
                    'show_consultation_link' => true,
                    'body' => 'HeartWell was created for women who want care that feels personal, thoughtful, and clinically grounded. Our approach blends nurse-led wellness support with comfort, privacy, and a deep respect for each woman\'s story.',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Features — differentiators' => [
                'section_type' => 'features',
                'heading' => 'What makes HeartWell different',
                'description' => 'Four trust pillars for Why HeartWell.',
                'content' => [
                    'features' => [
                        ['title' => 'Nurse-Led Care', 'anchor' => 'nurse-led-care', 'body' => 'Your HeartWell experience is personally led by Jacquie Wilson, BSN, RN, MBA. Her nursing background and calm, attentive approach shape an experience that feels professional, personal, and grounded in listening first.'],
                        ['title' => 'Clinically Supported', 'anchor' => 'clinically-supported', 'body' => self::CLINICAL_WORKFLOW_INTAKE],
                        ['title' => 'Designed Around Real Life', 'anchor' => 'designed-around-real-life', 'body' => 'Wellness support should fit into your life, not add more stress to it. HeartWell offers mobile visits and private wellness experiences designed around real schedules, real responsibilities, and real life.'],
                        ['title' => 'Compassion at the Center', 'anchor' => 'compassion-at-the-center', 'body' => 'At HeartWell, support begins with listening. Every interaction is designed to help women feel respected, understood, and thoughtfully guided through each stage of life.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Hero — wellness journey' => [
                'section_type' => 'hero',
                'heading' => 'Your Wellness Journey',
                'description' => 'Wellness Journey page hero — text-only minimal band.',
                'content' => [
                    'design_variant' => 'minimal',
                    'show_pathway_bar' => false,
                    'show_consultation_link' => true,
                    'body' => 'Your wellness needs can change over time, and your support should reflect where you are today. At HeartWell, there is no one-size-fits-all approach. The journey begins with listening to your goals, understanding your concerns, and helping you explore an appropriate next step.',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Rich text — wellness journey intro' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Wellness Journey intro paragraph.',
                'content' => [
                    'body' => '<p>Your wellness needs can change over time, and your support should reflect where you are today. At HeartWell, there is no one-size-fits-all approach. The journey begins with listening to your goals, understanding your concerns, and helping you explore an appropriate next step.</p>',
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Features — tailored to your life' => [
                'section_type' => 'features',
                'heading' => 'You Are Not Alone in This',
                'description' => 'Emotional support subsection for Wellness Journey.',
                'content' => [
                    'features' => [
                        ['title' => '', 'body' => 'Hormonal shifts, burnout, metabolic changes, and life transitions can affect how you feel from one season of life to the next. Whether you are navigating perimenopause, caregiver fatigue, changes in energy, or simply not feeling like yourself, education and nurse-led guidance can help you feel seen, supported, and less alone in figuring out where to begin.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            'FAQ block' => [
                'section_type' => 'faq',
                'heading' => 'Questions Before You Begin',
                'description' => 'FAQ accordion — assign FAQs to page slug in Website Content → FAQs.',
                'content' => [
                    'include_unassigned' => false,
                    'layout' => ['container_width' => 'narrow', 'background' => 'taupe'],
                ],
            ],
            'CTA — wellness journey' => [
                'section_type' => 'cta',
                'heading' => 'Ready to take the next step?',
                'description' => 'Wellness Journey page CTA.',
                'content' => [
                    'body' => 'Begin with a private wellness conversation — we are here when you are ready.',
                    'variant' => 'dual',
                    'primary_label' => $ctas['primary']['label'],
                    'primary_url' => '/contact#book',
                    'waitlist_label' => $ctas['secondary']['waitlist']['label'],
                    'waitlist_url' => '/contact#waitlist',
                    'show_consultation_link' => true,
                    'consultation_prefix' => $ctas['tertiary_prefix'],
                    'consultation_label' => $ctas['tertiary_label'],
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            'Hero — meet the founder' => [
                'section_type' => 'hero',
                'heading' => 'Meet the Founder',
                'description' => 'Meet the Founder page hero — text-only minimal band.',
                'content' => [
                    'design_variant' => 'minimal',
                    'show_pathway_bar' => false,
                    'show_consultation_link' => true,
                    'body' => 'Jacquie Wilson brings nurse-led, clinically grounded care to every HeartWell experience.',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Founder teaser — full page' => [
                'section_type' => 'founder_teaser',
                'heading' => 'Meet the Founder',
                'description' => 'Extended founder bio for Meet the Founder page.',
                'content' => [
                    'design_variant' => 'photo_left',
                    'show_eyebrow' => false,
                    'name' => 'Jacquie Wilson',
                    'role' => 'Founder & Director of Care',
                    'body' => 'HeartWell Aesthetics & Wellness was founded by Jacquie Wilson, a registered nurse with experience across emergency care, critical care, home health, advocacy, and complex patient support. Across those settings, she repeatedly saw how important it is for women to feel heard, respected, and supported — especially when they know something has changed but are not sure where to begin.',
                    'credentials' => ['BSN', 'RN', 'MBA'],
                    'pronunciation' => 'Pronounced Jack-Kwa',
                    'subsections' => [
                        ['title' => 'A Nurse-Led Approach', 'body' => 'Jacquie brings a calm, supportive presence to every HeartWell experience. Her approach begins with listening first, followed by thoughtful education and guidance designed to help each woman feel informed, respected, and supported.'],
                        ['title' => 'Why HeartWell Was Created', 'body' => 'HeartWell was created for women who are still showing up for everyone else while quietly feeling depleted, stuck, or unlike themselves. Jacquie wanted to create a more personal experience — one that begins with listening, respects the whole story, and does not push women toward a service before understanding what they are trying to navigate.'],
                        ['title' => 'The HeartWell Promise', 'body' => 'At HeartWell, the experience is never meant to feel rushed, confusing, or transactional. Every interaction is guided by respect, professional integrity, thoughtful communication, and the belief that women deserve support that feels personal and trustworthy.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'CTA — connect with team' => [
                'section_type' => 'cta',
                'heading' => 'Connect with HeartWell',
                'description' => 'Meet the Founder page CTA.',
                'content' => [
                    'body' => 'Begin with a private wellness conversation — we are here when you are ready.',
                    'variant' => 'dual',
                    'show_consultation_link' => true,
                    'consultation_prefix' => $ctas['tertiary_prefix'],
                    'consultation_label' => $ctas['tertiary_label'],
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            'CTA — your experience' => [
                'section_type' => 'cta',
                'heading' => 'Ready when you are',
                'description' => 'Your Experience page CTA.',
                'content' => [
                    'body' => 'Take the first step — we will walk with you from hello to follow-up.',
                    'variant' => 'dual',
                    'primary_label' => $ctas['primary']['label'],
                    'primary_url' => '/contact#book',
                    'waitlist_label' => $ctas['secondary']['waitlist']['label'],
                    'waitlist_url' => '/contact#waitlist',
                    'show_consultation_link' => true,
                    'consultation_prefix' => $ctas['tertiary_prefix'],
                    'consultation_label' => $ctas['tertiary_label'],
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            'CTA — start with conversation' => [
                'section_type' => 'cta',
                'heading' => 'Begin with a Private Wellness Conversation',
                'description' => 'Why HeartWell page CTA.',
                'content' => [
                    'body' => 'We would love to hear your story and help you find the right support.',
                    'variant' => 'dual',
                    'show_consultation_link' => true,
                    'consultation_prefix' => $ctas['tertiary_prefix'],
                    'consultation_label' => $ctas['tertiary_label'],
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            'Hero — contact' => [
                'section_type' => 'hero',
                'heading' => 'Connect with HeartWell',
                'description' => 'Contact page hero — text-only minimal band.',
                'content' => [
                    'design_variant' => 'minimal',
                    'show_pathway_bar' => false,
                    'show_consultation_link' => true,
                    'subheading' => 'Begin with a Private Wellness Conversation',
                    'body' => 'You do not need to have a specific service picked out before you reach out. HeartWell begins with a private conversation so we can discuss your goals, answer your questions, and help you understand what type of support may be appropriate.',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Contact forms block' => [
                'section_type' => 'forms',
                'heading' => 'How Would You Like to Experience HeartWell?',
                'description' => 'Waitlist, consultation, booking, and group inquiry forms.',
                'content' => array_merge(self::contactFormsDefaults(), [
                    'section_subtitle' => 'Please select the option below that best fits what you are looking for.',
                    'forms' => ['waitlist', 'consultation', 'group_inquiry'],
                    'pre_form_guidance' => self::PRE_FORM_GUIDANCE,
                    'contact_disclaimer' => self::complianceDefaults()['contact_disclaimer'],
                    'privacy_summary' => self::complianceDefaults()['privacy_summary'],
                    'clinical_portal_note' => self::complianceDefaults()['clinical_portal_note'],
                    'group_intake_note' => self::GROUP_INTAKE_GUESTS,
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left'],
                ]),
            ],
            'Intro — safety and clinical care' => [
                'section_type' => 'intro',
                'heading' => 'Safety and clinical care',
                'description' => 'Clinical safety intro for Your Experience.',
                'content' => [
                    'body' => self::CLINICAL_SAFETY_ONE_LINER,
                    'layout' => ['container_width' => 'narrow', 'background' => 'white'],
                ],
            ],
            'Features — what you can expect' => [
                'section_type' => 'features',
                'heading' => 'What You Can Expect',
                'description' => 'Trust-building pillars for the home page (replaces placeholder testimonials).',
                'content' => [
                    'features' => [
                        ['title' => 'Nurse-Led Care', 'body' => 'Every visit is guided by clinical experience, screening, and thoughtful support.'],
                        ['title' => 'Private Mobile Visits', 'body' => 'Care is brought to you in a calm, comfortable setting.'],
                        ['title' => 'Support That Feels Personal', 'body' => 'Your wellness plan is shaped around your goals, your season of life, and how you are feeling.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Testimonials — grid' => [
                'section_type' => 'testimonials',
                'heading' => 'What You Can Expect',
                'description' => 'Home trust section — renders feature cards instead of placeholder testimonials.',
                'content' => [
                    'enabled' => false,
                    'trust_features' => [
                        ['title' => 'Nurse-Led Care', 'body' => 'Every visit is guided by clinical experience, screening, and thoughtful support.'],
                        ['title' => 'Private Mobile Visits', 'body' => 'Care is brought to you in a calm, comfortable setting.'],
                        ['title' => 'Support That Feels Personal', 'body' => 'Your wellness plan is shaped around your goals, your season of life, and how you are feeling.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Standard CTA band' => [
                'section_type' => 'cta',
                'heading' => 'You Deserve to Feel Like Yourself Again',
                'description' => 'Home closing CTA — warm ivory/cream band with dual CTAs.',
                'content' => [
                    'design_variant' => 'default',
                    'body' => "Whether you're feeling depleted, stuck, or simply unlike yourself, support is available.",
                    'variant' => 'dual',
                    'primary_label' => $ctas['primary']['label'],
                    'primary_url' => '/contact#book',
                    'waitlist_label' => $ctas['secondary']['waitlist']['label'],
                    'waitlist_url' => '/contact#waitlist',
                    'show_consultation_link' => true,
                    'consultation_prefix' => $ctas['tertiary_prefix'],
                    'consultation_label' => $ctas['tertiary_label'],
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'section_padding' => 'spacious', 'background' => 'cream'],
                ],
            ],
            'Founder teaser' => [
                'section_type' => 'founder_teaser',
                'heading' => 'Meet the Founder',
                'description' => 'Home page founder teaser with photo and bio.',
                'content' => [
                    'design_variant' => 'photo_left',
                    'show_eyebrow' => true,
                    'body' => 'Jacquie Wilson, BSN, RN, MBA founded HeartWell to offer thoughtful, nurse-led wellness support for women who feel depleted, stuck, or unlike themselves — with care that feels calm, personal, and trustworthy.',
                    'credentials' => ['BSN', 'RN', 'MBA'],
                    'pronunciation' => 'Pronounced Jack-Kwa',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'CTA — client pre-footer band' => [
                'section_type' => 'cta',
                'heading' => 'You Deserve to Feel Like Yourself Again',
                'description' => 'Home pre-footer CTA — warm ivory/cream band.',
                'content' => [
                    'design_variant' => 'centered_band',
                    'body' => "Whether you're feeling depleted, stuck, or simply unlike yourself, support is available.",
                    'variant' => 'dual',
                    'show_consultation_link' => false,
                    'layout' => ['container_width' => 'default', 'section_padding' => 'spacious', 'background' => 'cream'],
                ],
            ],
            'Pathways teaser' => [
                'section_type' => 'pathways_teaser',
                'heading' => 'Support Pathways',
                'description' => 'Home page pathway accordion preview.',
                'content' => [
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'section_padding' => 'compact'],
                ],
            ],
            'Hero — privacy' => [
                'section_type' => 'hero',
                'heading' => 'Privacy Policy',
                'description' => 'Privacy policy page hero — text only.',
                'content' => [
                    'design_variant' => 'minimal',
                    'show_pathway_bar' => false,
                    'show_consultation_link' => false,
                    'show_cta_buttons' => false,
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Rich text — privacy policy' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Privacy policy body — editable in Section Library.',
                'content' => [
                    'body' => self::defaultPrivacyPolicyHtml(),
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
        ];
    }

    public static function defaultPrivacyPolicyHtml(): string
    {
        return self::privacyPolicyBodyHtml();
    }

    /**
     * @return list<string>
     */
    public static function homeDesignStack(): array
    {
        return [
            'Hero — full bleed overlay',
            'Avatar intro — client horizontal',
            'Intro — home nurse-led care',
            'Pathways teaser',
            'Testimonials — grid',
            'Founder teaser',
            'Standard CTA band',
        ];
    }

    /**
     * @return list<string>
     */
    public static function homeLaunchStack(): array
    {
        return [
            'Hero — home banner',
            'Avatar intro block',
            'Intro — home nurse-led care',
            'Pathways teaser',
            'Features — what you can expect',
            'Founder teaser',
            'CTA — client pre-footer band',
        ];
    }

    /**
     * @return array<string, list<string>>
     */
    public static function pageSectionStacks(): array
    {
        return [
            'home' => self::homeLaunchStack(),
            'privacy' => [
                'Hero — privacy',
                'Rich text — privacy policy',
            ],
            'support-pathways' => [
                'Hero — support pathways',
                'Intro — clinical intake clearance',
                'Rich text — IV injection add-ons',
                'Pathways teaser — guided cards',
                'Rich text — final treatment selection',
                'Journey — Hydreight portal flow',
                'CTA — support pathways',
            ],
            'wellness-journey' => [
                'Hero — wellness journey',
                'Rich text — wellness journey intro',
                'Features — tailored to your life',
                'FAQ block',
                'CTA — wellness journey',
            ],
        ];
    }
}
