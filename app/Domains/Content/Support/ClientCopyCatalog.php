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
                    ['label' => 'Safety & Standards', 'route' => 'why-heartwell', 'anchor' => 'safe-compliant-care'],
                ],
            ],
            [
                'title' => 'WHY HEARTWELL',
                'links' => [
                    ['label' => 'Whole-Person Care', 'route' => 'why-heartwell', 'anchor' => 'personalized-attention'],
                    ['label' => 'Our Approach', 'route' => 'why-heartwell', 'anchor' => 'compassion-care'],
                    ['label' => 'Expert-Guided Care', 'route' => 'why-heartwell', 'anchor' => 'nursing-experience'],
                    ['label' => 'Flexible & Convenient', 'route' => 'why-heartwell', 'anchor' => 'safe-compliant-care'],
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
                'slug' => 'individualized-collaborative-care',
                'title' => 'Individualized & Collaborative Care',
                'short_title' => 'Individualized',
                'tagline' => 'When your concerns do not fit neatly into one category',
                'intro' => 'This pathway is designed for women whose wellness goals or concerns do not fit neatly into a single category. Together, we explore what matters most to you and identify a thoughtful place to begin with guided decision-making and nurse-led support.',
                'options_may_include' => [
                    'Consultation-based individualized wellness support',
                    'NAD+ support when available and clinically appropriate',
                    'A collaborative approach guided by provider review',
                ],
                'common_support' => null,
                'portal_cue' => "This pathway may appear as NAD, NAD+, NAD IV, or NAD IM, depending on what is available through the Hydreight clinical workflow.\n\nSome options may require additional screening or provider guidance before treatment is approved.",
                'avatar_type' => AvatarType::Frustrated,
                'cta_label' => 'Begin with a Private Wellness Conversation',
                'cta_url' => '/contact#consultation',
                'migrate_from_slug' => 'specialized-support',
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
     * @return array<string, string>
     */
    public static function pathwayImagePaths(): array
    {
        return [
            'recovery-hydration' => 'cms/pathways/recovery-hydration.png',
            'energy-wellness' => 'cms/pathways/energy-wellness.png',
            'metabolic-weight' => 'cms/pathways/metabolic-weight.png',
            'individualized-collaborative-care' => 'cms/pathways/individualized-collaborative-care.png',
            'precision-glow-therapy' => 'cms/pathways/precision-glow-therapy.png',
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
                'answer' => 'HeartWell offers nurse-led wellness support through pathways focused on Recovery & Hydration, Energy & Wellness, Metabolic & Weight Support, Individualized & Collaborative Care, and Precision Glow Therapy. Available options depend on required screening, provider review, and clinical appropriateness.',
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
        $pathwayImages = self::pathwayImagePaths();

        $whyBecauseImFineLowerHtml = <<<'HTML'
<p>Sometimes &ldquo;I&rsquo;m fine&rdquo; is the answer we give before we have fully figured out how to explain what we are feeling.</p>
<p>You may still be getting through the day. Still working. Still caring for everyone else. Still doing the things you have always done.</p>
<p>And yet, somewhere underneath all of that, you may know that something feels different.</p>
<p>Maybe you are more tired than you used to be.</p>
<p>Maybe your body is not responding the way you expected.</p>
<p>Maybe recovery takes longer.</p>
<p>Maybe your weight, energy, sleep, skin, focus, or sense of well-being has changed in ways you were not prepared for.</p>
<p>Or maybe you simply do not feel quite like yourself.</p>
<p>That is part of why HeartWell was created.</p>
HTML;

        $whyCreatedColumnHtml = <<<'HTML'
<p>HeartWell was created for the moments when something feels off, but you are not sure why.</p>
<p>It was created for women who may still be functioning, still managing, and still showing up for everyone around them &mdash; while quietly wondering why they do not feel like themselves anymore.</p>
<p>You deserve a place to be heard, to be supported, and to take the next step at a pace that feels right for you.</p>
<p>At HeartWell, the goal is not to rush you toward a treatment or expect you to arrive already knowing what you need.</p>
<p>The goal is to begin with what you are noticing, help you understand the options that may be available, and support you in deciding what feels appropriate for you.</p>
HTML;

        $whyCompassionColumnHtml = <<<'HTML'
<p>We listen without assuming.</p>
<p>We explain without overwhelming.</p>
<p>We respect what you are feeling without telling you what you should be feeling.</p>
<p>Compassion does not replace clinical judgment. It shapes how care is delivered.</p>
<p>That means making room for questions, recognizing that every woman brings a different history and set of priorities, and understanding that sometimes the most important first step is simply feeling heard.</p>
HTML;

        $whyNursingColumnHtml = <<<'HTML'
<p>HeartWell is founded by Jacquie Wilson, BSN, RN, MBA.</p>
<p>Nursing experience across a range of care settings shapes our approach to listening carefully, explaining clearly, supporting appropriate screening, and recognizing when another next step may be more appropriate.</p>
<p>That experience also shapes something just as important: the understanding that good care is not only about what is offered.</p>
<p>It is also about how a person is treated while receiving it.</p>
<p>HeartWell brings together clinical awareness, thoughtful guidance, and a calm, personalized approach designed to help women feel supported rather than rushed.</p>
HTML;

        $whyBecauseImFineHtml = <<<'HTML'
<h2>Because &ldquo;I&rsquo;m Fine&rdquo; Can Mean So Many Things</h2>
<p>Sometimes &ldquo;I&rsquo;m fine&rdquo; is the answer we give before we have fully figured out how to explain what we are feeling.</p>
<p>You may still be getting through the day. Still working. Still caring for everyone else. Still doing the things you have always done.</p>
<p>And yet, somewhere underneath all of that, you may know that something feels different.</p>
<p>Maybe you are more tired than you used to be.</p>
<p>Maybe your body is not responding the way you expected.</p>
<p>Maybe recovery takes longer.</p>
<p>Maybe your weight, energy, sleep, skin, focus, or sense of well-being has changed in ways you were not prepared for.</p>
<p>Or maybe you simply do not feel quite like yourself.</p>
<p>That is part of why HeartWell was created.</p>
HTML;

        $whyBridgePermissionHtml = <<<'HTML'
<p>You do not need to know exactly what you need before you begin.</p>
<p>You can just start with what you are noticing.</p>
HTML;

        $whyCreatedHtml = <<<'HTML'
<h2>Why HeartWell Was Created</h2>
<p>HeartWell was created for the moments when something feels off, but you are not sure why.</p>
<p>It was created for women who may still be functioning, still managing, and still showing up for everyone around them &mdash; while quietly wondering why they do not feel like themselves anymore.</p>
<p>You deserve a place to be heard, to be supported, and to take the next step at a pace that feels right for you.</p>
<p>At HeartWell, the goal is not to rush you toward a treatment or expect you to arrive already knowing what you need.</p>
<p>The goal is to begin with what you are noticing, help you understand the options that may be available, and support you in deciding what feels appropriate for you.</p>
HTML;

        $whyCompassionHtml = <<<'HTML'
<h2>Compassion Is Not Separate from Good Care</h2>
<p>We listen without assuming.</p>
<p>We explain without overwhelming.</p>
<p>We respect what you are feeling without telling you what you should be feeling.</p>
<p>Compassion does not replace clinical judgment. It shapes how care is delivered.</p>
<p>That means making room for questions, recognizing that every woman brings a different history and set of priorities, and understanding that sometimes the most important first step is simply feeling heard.</p>
HTML;

        $whyNursingExperienceHtml = <<<'HTML'
<h2>Guided by Nursing Experience</h2>
<p>HeartWell is founded by Jacquie Wilson, BSN, RN, MBA.</p>
<p>Nursing experience across a range of care settings shapes our approach to listening carefully, explaining clearly, supporting appropriate screening, and recognizing when another next step may be more appropriate.</p>
<p>That experience also shapes something just as important: the understanding that good care is not only about what is offered.</p>
<p>It is also about how a person is treated while receiving it.</p>
<p>HeartWell brings together clinical awareness, thoughtful guidance, and a calm, personalized approach designed to help women feel supported rather than rushed.</p>
HTML;

        $whyClosingPermissionHtml = <<<'HTML'
<h2>You Do Not Have to Have It All Figured Out</h2>
<p>You do not have to have it all figured out to begin.</p>
<p>You do not need to arrive with the right words.</p>
<p>You do not need to know which service fits.</p>
<p>You do not need to decide everything at once.</p>
<p>You just have to be willing to listen to what you are noticing.</p>
HTML;

        $wjHeroMessageHtml = <<<'HTML'
<h2>Your Wellness Journey Can Begin with One Simple Question</h2>
<p class="hw-journey-lead-question">What have you been noticing?</p>
<p>Maybe you only know that something has changed &mdash; your energy feels different, your weight is harder to manage, you need more time to recover after a busy day, or you simply do not feel quite like yourself.</p>
<p>You do not need to diagnose yourself, choose a treatment, or arrive with the perfect words.</p>
<p>You do not need to know exactly what you need before you begin.</p>
<p>You can just start with what you are noticing.</p>
HTML;

        $wjStep1NoticingHtml = <<<'HTML'
<p class="hw-journey-step-eyebrow">Step 1 &mdash; Start with What You Are Noticing</p>
<p>You may not know exactly why you feel different. You may only know that something is no longer working the way you expected.</p>
<p>Sometimes the first step is simply paying attention to the thoughts you keep coming back to:</p>
<ul class="hw-reflective-quotes-list">
<li>&ldquo;I&rsquo;m doing the things I thought would help, but my body still isn&rsquo;t responding the way I expected.&rdquo;</li>
<li>&ldquo;I thought I would feel better by now.&rdquo;</li>
<li>&ldquo;I look in the mirror and notice changes I wasn&rsquo;t prepared for.&rdquo;</li>
</ul>
<p>That is enough to begin.</p>
HTML;

        $wjStep2NotAloneHtml = <<<'HTML'
<p class="hw-journey-step-eyebrow">Step 2 &mdash; You Do Not Have to Figure It Out Alone</p>
<p>It&rsquo;s easy to live with changes in your body for so long that they simply become part of the background noise.</p>
<p>You may get used to feeling tired, needing more time to recover, struggling with changes in your weight despite your efforts, or simply pushing through because life keeps moving.</p>
<p>You might not have even thought of these changes as connected &mdash; or as something worth paying closer attention to.</p>
<h3>When You Are Not Sure Where to Turn Next</h3>
<p>Once you begin paying closer attention to how you feel, it can be overwhelming to figure out what matters, what is connected, or how to move forward.</p>
<p>It&rsquo;s easy to find yourself researching endless supplements, comparing online services, reading conflicting advice, and trying to piece together your own plan &mdash; all while you are already tired of carrying the problem.</p>
<p>You should not have to become your own investigator, care coordinator, and wellness expert just to figure out where to begin.</p>
<h3>This Is Where the HeartWell Journey Becomes Different</h3>
<p>We do not expect you to arrive with a diagnosis, a chosen treatment, or a perfectly organized explanation of what you have been experiencing.</p>
<p>Instead, we meet you right where you are. Together, we will make sense of the subtle shifts you are noticing, honor what you have already tried on your own, and identify what feels most important to address first.</p>
<p>From there, we help you explore which areas of support align with what you are experiencing and what matters most to you &mdash; guiding you toward the next step that best fits your goals.</p>
<p>You are not expected to figure out the entire path before you begin. We are here to help you navigate it, one thoughtful step at a time.</p>
HTML;

        $wjStep5CareHtml = <<<'HTML'
<p class="hw-journey-step-eyebrow">Step 5 &mdash; Your Care Comes to You</p>
<p>Once you are ready to move forward, HeartWell brings your visit directly to you.</p>
<p>Your experience is thoughtfully prepared around your needs, with time to ask questions, understand what is being provided, and receive care in a setting that feels private, comfortable, and personal.</p>
<p>We take care of the details &mdash; from setup through follow-up &mdash; so you are not rushing to another appointment, navigating another waiting room, or trying to fit one more task into an already full day.</p>
<p>Instead, you can focus on the experience in front of you, knowing you will be informed, supported, and cared for along the way.</p>
HTML;

        $wjStep6ConnectedHtml = <<<'HTML'
<p class="hw-journey-step-eyebrow">Step 6 &mdash; We Stay Connected</p>
<p>Your first visit is not where the HeartWell journey ends.</p>
<p>We stay connected after your visit &mdash; checking in, listening to what you are noticing now, and helping you understand what may make sense as you move forward.</p>
<p>Sometimes that means continuing in the same direction. Other times, new questions, priorities, or concerns may come into focus.</p>
<p class="hw-journey-emphasis">What matters is that you are not left to figure out what comes next on your own.</p>
HTML;

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
                    'body' => 'HeartWell Support Pathways are designed to help you begin with how you feel and what you hope to address — not with a confusing treatment menu. Each pathway offers a starting point for exploring the type of support that may fit your goals. Final care options depend on required clinical intake, provider screening, and clinical appropriateness.',
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
                'heading' => 'Thoughtful Wellness Care for the Moments When Something Just Feels Off',
                'description' => 'Why HeartWell page hero — split layout with floating quotes and Because I\'m Fine lower block.',
                'content' => [
                    'design_variant' => 'split_image_quotes',
                    'eyebrow' => 'WHY HEARTWELL',
                    'title_lead' => 'Thoughtful Wellness Care',
                    'title_emphasis' => 'for the Moments',
                    'title_tail' => 'When Something Just Feels Off',
                    'lower_heading' => 'Because "I\'m Fine" Can Mean So Many Things',
                    'lower_body' => $whyBecauseImFineLowerHtml,
                    'quotes' => [
                        ['text' => 'I slept, but I still feel tired.', 'position' => 'center-left'],
                        ['text' => 'I\'m doing what I always do, but something still feels off.', 'position' => 'top-right'],
                        ['text' => 'I can handle what\'s already on my plate. I just don\'t have room for one more thing.', 'position' => 'bottom-right'],
                    ],
                    'primary_label' => 'Explore Support Pathways',
                    'primary_url' => '/support-pathways',
                    'secondary_label' => 'Begin with a Private Wellness Conversation',
                    'secondary_url' => '/contact#consultation',
                    'show_pathway_bar' => false,
                    'show_cta_buttons' => false,
                    'show_consultation_link' => false,
                    'show_floating_quotes' => false,
                    'image_url' => 'cms/sections/why-heartwell-hero.png',
                    'layout' => ['container_width' => 'extra_wide', 'background' => 'white', 'text_align' => 'left', 'section_padding' => 'none'],
                ],
            ],
            'Intro — private thoughts' => [
                'section_type' => 'intro',
                'heading' => null,
                'description' => 'Why HeartWell reflective private-thought quotes.',
                'content' => [
                    'design_variant' => 'reflective_quotes',
                    'quotes' => [
                        'I slept, but I still feel tired.',
                        'I\'m doing what I always do, but something still feels off.',
                        'I can handle what\'s already on my plate. I just don\'t have room for one more thing.',
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'section_padding' => 'spacious'],
                ],
            ],
            'Rich text — because im fine' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Why HeartWell — Because I\'m Fine narrative.',
                'content' => [
                    'body' => $whyBecauseImFineHtml,
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Rich text — bridge permission' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Why HeartWell bridge — permission to begin without knowing.',
                'content' => [
                    'design_variant' => 'editorial_bridge',
                    'headline' => 'You do not need to know exactly what you need before you begin.',
                    'emphasis_line' => 'You can just start with what you are noticing.',
                    'section_class' => 'hw-rich-text-section--bridge',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'center', 'section_padding' => 'spacious'],
                ],
            ],
            'Rich text — three column narrative' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Why HeartWell three-column narrative — created, compassion, nursing.',
                'content' => [
                    'design_variant' => 'three_column_narrative',
                    'columns' => [
                        [
                            'title' => 'Why HeartWell Was Created',
                            'body' => $whyCreatedColumnHtml,
                        ],
                        [
                            'title' => 'Compassion Is Not Separate from Good Care',
                            'anchor' => 'compassion-care',
                            'body' => $whyCompassionColumnHtml,
                        ],
                        [
                            'title' => 'Guided by Nursing Experience',
                            'anchor' => 'nursing-experience',
                            'body' => $whyNursingColumnHtml,
                        ],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left', 'section_padding' => 'spacious'],
                ],
            ],
            'Rich text — why created' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Why HeartWell Was Created section.',
                'content' => [
                    'body' => $whyCreatedHtml,
                    'layout' => ['container_width' => 'narrow', 'background' => 'dusty_blue', 'text_align' => 'left'],
                ],
            ],
            'Rich text — compassion' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Why HeartWell — compassion and good care.',
                'content' => [
                    'body' => $whyCompassionHtml,
                    'section_anchor' => 'compassion-care',
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Rich text — nursing experience' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Why HeartWell — guided by nursing experience.',
                'content' => [
                    'body' => $whyNursingExperienceHtml,
                    'section_anchor' => 'nursing-experience',
                    'layout' => ['container_width' => 'narrow', 'background' => 'taupe', 'text_align' => 'left'],
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
                'description' => 'Why HeartWell page CTA — single consultation button.',
                'content' => [
                    'design_variant' => 'single_primary',
                    'variant' => 'primary',
                    'primary_label' => $ctas['secondary']['consultation']['label'],
                    'primary_url' => '/contact#consultation',
                    'show_consultation_link' => false,
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue', 'section_padding' => 'spacious'],
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
            'Features — home trust pillars' => [
                'section_type' => 'features',
                'heading' => 'What You Can Expect',
                'description' => 'Trust-building pillars for the home page.',
                'content' => [
                    'features' => [
                        ['title' => 'Nurse-Led Care', 'body' => 'Every visit is guided by clinical experience, screening, and thoughtful support.'],
                        ['title' => 'Private Mobile Visits', 'body' => 'Care is brought to you in a calm, comfortable setting.'],
                        ['title' => 'Support That Feels Personal', 'body' => 'Your wellness plan is shaped around your goals, your season of life, and how you are feeling.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Features — what you can expect' => [
                'section_type' => 'features',
                'heading' => 'What You Can Expect from HeartWell',
                'description' => 'Five expectation pillars for Why HeartWell.',
                'content' => [
                    'design_variant' => 'five_column_dividers',
                    'features' => [
                        [
                            'title' => 'Personalized Attention',
                            'anchor' => 'personalized-attention',
                            'body' => 'We take time to understand you as a whole person, not just a list of concerns. Your goals, preferences, health history, and what you are currently noticing all matter.',
                        ],
                        [
                            'title' => 'Thoughtful Recommendations',
                            'anchor' => 'thoughtful-recommendations',
                            'body' => 'Care options are explained clearly so you can make informed choices. You will not be expected to know exactly what treatment you need before you begin.',
                        ],
                        [
                            'title' => 'Safe, Compliant Care',
                            'anchor' => 'safe-compliant-care',
                            'body' => 'Clinical screening and clearance are always completed before treatment. When clinical services are appropriate, required intake, health history, consent, screening, and provider clearance are completed through HeartWell\'s secure clinical process.',
                        ],
                        [
                            'title' => 'Flexible Options',
                            'anchor' => 'flexible-options',
                            'body' => 'HeartWell is designed around real life. Care options may include private mobile visits, thoughtfully planned support, and group wellness gatherings in appropriate settings.',
                        ],
                        [
                            'title' => 'Ongoing Support',
                            'anchor' => 'ongoing-support',
                            'body' => 'We stay connected to support your progress over time. Your needs may change, your goals may evolve, and your plan does not have to remain exactly the same simply because that is where you started.',
                        ],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'section_padding' => 'spacious'],
                ],
            ],
            'Rich text — closing permission' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Why HeartWell warm closing permission section.',
                'content' => [
                    'body' => $whyClosingPermissionHtml,
                    'section_class' => 'hw-rich-text-section--closing',
                    'layout' => ['container_width' => 'narrow', 'background' => 'cream', 'text_align' => 'center', 'section_padding' => 'spacious'],
                ],
            ],
            'Hero — wellness journey split' => [
                'section_type' => 'hero',
                'heading' => 'Your Wellness Journey Can Begin with One Simple Question',
                'description' => 'Wellness Journey split hero — copy left, image right, floating quotes.',
                'content' => [
                    'design_variant' => 'journey_split_hero',
                    'eyebrow' => 'The HeartWell Wellness Journey',
                    'hero_title' => 'Your Wellness Journey Can Begin with One Simple Question',
                    'lead_question' => 'What have you been noticing?',
                    'body' => 'The journey is designed to help you move from uncertainty toward a clearer next step. You do not have to have all the answers. You can begin right where you are, with what you are noticing.',
                    'quotes' => [
                        ['text' => 'When did my clothes start fitting differently?'],
                        ['text' => 'I look in the mirror and notice changes I wasn\'t prepared for.'],
                    ],
                    'show_floating_quotes' => false,
                    'show_pathway_bar' => false,
                    'show_cta_buttons' => false,
                    'show_consultation_link' => false,
                    'image_url' => 'cms/sections/wellness-journey-hero-desktop.png',
                    'image_url_clean' => 'cms/sections/wellness-journey-hero-clean.png',
                    'image_url_mobile' => 'cms/sections/wellness-journey-hero-mobile.png',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'section_padding' => 'none', 'text_align' => 'left'],
                ],
            ],
            'Intro — wellness journey orientation' => [
                'section_type' => 'intro',
                'heading' => 'Wellness Journey',
                'description' => 'Wellness Journey page orientation copy.',
                'content' => [
                    'content_eyebrow' => 'Wellness Journey',
                    'body' => 'The journey is designed to help you move from uncertainty toward a clearer next step. You do not need to arrive knowing which treatment to choose, which service to request, or how to explain everything perfectly. The HeartWell Wellness Journey begins simply with what you are noticing. We are here to help you make sense of your concerns, identify what feels most important to address, and understand exactly where to begin.',
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Hero — wellness journey artwork' => [
                'section_type' => 'hero',
                'heading' => null,
                'description' => 'Wellness Journey responsive hero artwork — desktop and mobile, no text overlay.',
                'content' => [
                    'design_variant' => 'responsive_art',
                    'show_pathway_bar' => false,
                    'show_cta_buttons' => false,
                    'show_consultation_link' => false,
                    'image_url' => 'cms/sections/wellness-journey-hero-desktop.png',
                    'image_url_mobile' => 'cms/sections/wellness-journey-hero-mobile.png',
                    'image_url_clean' => 'cms/sections/wellness-journey-hero-clean.png',
                    'layout' => ['container_width' => 'comfortable', 'background' => 'white', 'section_padding' => 'spacious'],
                ],
            ],
            'Rich text — wellness journey hero message' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Wellness Journey hero copy block below artwork.',
                'content' => [
                    'body' => $wjHeroMessageHtml,
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Rich text — step 1 noticing' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Wellness Journey Step 1 — start with what you are noticing.',
                'content' => [
                    'design_variant' => 'journey_step',
                    'body' => $wjStep1NoticingHtml,
                    'section_class' => 'hw-journey-step',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left', 'section_padding' => 'normal'],
                ],
            ],
            'Rich text — step 2 not alone' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Wellness Journey Step 2 — you do not have to figure it out alone.',
                'content' => [
                    'design_variant' => 'journey_step',
                    'body' => $wjStep2NotAloneHtml,
                    'section_class' => 'hw-journey-step hw-wj-step--cream',
                    'layout' => ['container_width' => 'default', 'background' => 'cream', 'text_align' => 'left', 'section_padding' => 'normal'],
                ],
            ],
            'Pathways — wellness journey editorial' => [
                'section_type' => 'pathways_teaser',
                'heading' => 'Step 3 — Explore Where Support May Begin',
                'description' => 'Wellness Journey Step 3 — bordered pathway grid cards.',
                'content' => [
                    'design_variant' => 'journey_pathway_grid',
                    'intro' => 'Once you identify what you are noticing and what matters most, your next step isn\'t to choose a treatment. Instead, we help you step back and explore broader areas of support that naturally align with your daily life and personal wellness goals. Our Support Pathways are designed to give you a calm, clearer place to start — removing the pressure to diagnose yourself or decide on a specific service before you are ready. Depending on what you are noticing, what matters most to you, and where you feel ready to begin, one or more of these starting points may feel relevant.',
                    'panels' => [
                        [
                            'title' => 'Recovery & Hydration',
                            'body' => 'Designed for physical replenishment — helping your body rehydrate, recover, and regain a sense of balance after travel, demanding routines, or seasons of high stress.',
                        ],
                        [
                            'title' => 'Energy & Wellness',
                            'body' => 'Designed for women who are tired of running on empty. When low energy affects your focus at work, your presence at home, and your ability to feel like yourself, this is where we begin looking at the bigger picture together.',
                        ],
                        [
                            'title' => 'Metabolic & Weight Support',
                            'body' => 'Designed for women navigating stubborn weight changes, stalled progress, or changes in how their body responds to food, movement, and previous weight-loss efforts.',
                        ],
                        [
                            'title' => 'Individualized & Collaborative Care',
                            'body' => 'For concerns or wellness goals that do not fit neatly into a single category and deserve a more individualized conversation about what matters most and where to begin.',
                        ],
                        [
                            'title' => 'Precision Glow Therapy',
                            'body' => 'When your reflection catches you off guard, those unexpected changes can be frustrating. Precision Glow Therapy is designed for women navigating visible shifts in their skin, hair, or overall appearance, offering a thoughtful, individualized approach to the concerns they are noticing.',
                        ],
                    ],
                    'closing' => '<p><strong>&ldquo;I see myself in more than one of these. What is my next step?&rdquo;</strong></p><p>That is completely okay. Your concerns do not have to fit neatly into one category before you begin.</p><p>The next step is simply choosing how you would like to start.</p>',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'center', 'section_padding' => 'spacious'],
                ],
            ],
            'Group — step 4 start options' => [
                'section_type' => 'group_individual',
                'heading' => 'Step 4 — Choose How You\'d Like to Begin',
                'description' => 'Wellness Journey Step 4 — dual balanced start options with exact CTAs.',
                'content' => [
                    'design_variant' => 'dual_start_cream',
                    'body' => '<p>You do not need to have everything figured out to take the next step.</p><p>Some women want a private, open space to talk through what they are experiencing before deciding on a direction. Others already have a clearer sense of what they need and feel ready to move forward.</p><p>Wherever you are in your journey, HeartWell meets you there.</p>',
                    'columns' => [
                        [
                            'title' => 'Start with a Private Wellness Conversation',
                            'subtitle' => 'For clarity, guidance, and peace of mind.',
                            'body' => "If you aren't sure what you need yet, start here.\n\nThis is a private space to share what you've been noticing, what you've already tried, and how you hope to feel moving forward.\n\nNo pressure to have it all figured out — just a supportive conversation to answer your questions, help you make sense of your concerns, and guide you toward a clearer next step.",
                            'cta_label' => 'Begin with a Private Wellness Conversation',
                            'cta_url' => '/contact#consultation',
                        ],
                        [
                            'title' => 'Request a Private Mobile Visit',
                            'subtitle' => 'For personalized care delivered directly to you.',
                            'body' => "If you feel ready to take the next step toward your wellness goals, you may request a private mobile visit.\n\nWe will guide you through the next steps, including your secure clinical intake, health history, required screening, and provider review through HeartWell's clinical portal, powered by Hydreight.\n\nOnce that process is complete, you will have a clearer understanding of the care options available to you and what comes next.",
                            'cta_label' => 'Request a Private Mobile Visit',
                            'cta_url' => '/contact#book',
                        ],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'cream', 'text_align' => 'center', 'section_padding' => 'spacious'],
                ],
            ],
            'Rich text — step 5 care comes to you' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Wellness Journey Step 5 — your care comes to you.',
                'content' => [
                    'design_variant' => 'journey_expect_split',
                    'body' => $wjStep5CareHtml,
                    'expect_heading' => 'What You Can Expect:',
                    'expect_items' => [
                        ['title' => 'Nurse-led care', 'body' => 'Every visit is guided by clinical experience, screening, and thoughtful support.'],
                        ['title' => 'Private, mobile visits', 'body' => 'Care is brought to you in a calm, comfortable setting.'],
                        ['title' => 'Support that feels personal', 'body' => 'Your wellness plan is shaped around your goals, your season of life, and how you are feeling.'],
                    ],
                    'section_class' => 'hw-journey-step',
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left', 'section_padding' => 'spacious'],
                ],
            ],
            'Rich text — step 6 stay connected' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Wellness Journey Step 6 — warm stay connected close.',
                'content' => [
                    'design_variant' => 'journey_cta_split',
                    'body' => $wjStep6ConnectedHtml,
                    'cta_heading' => 'You Deserve to Feel Like Yourself Again',
                    'cta_body' => "Whether you're feeling depleted, stuck, or simply unlike yourself, support is available.",
                    'primary_label' => 'Book a Visit',
                    'primary_url' => '/contact#book',
                    'waitlist_label' => $ctas['secondary']['waitlist']['label'],
                    'waitlist_url' => '/contact#waitlist',
                    'consultation_prefix' => $ctas['tertiary_prefix'],
                    'consultation_label' => 'Request Consultation',
                    'consultation_url' => '/contact#consultation',
                    'section_class' => 'hw-journey-step',
                    'layout' => ['container_width' => 'default', 'background' => 'cream', 'text_align' => 'left', 'section_padding' => 'spacious'],
                ],
            ],
            'Group vs individual comparison' => [
                'section_type' => 'group_individual',
                'heading' => 'Individual visits vs group gatherings',
                'description' => 'Two-column comparison for Your Experience.',
                'content' => [
                    'body' => '<p>Individual visits are one-on-one wellness support tailored to your goals. Group gatherings are private hosted experiences — each guest still completes their own required clinical intake.</p>',
                    'columns' => [
                        [
                            'title' => 'Individual visit',
                            'body' => 'A private, nurse-led wellness visit designed around your goals, questions, and what you hope to address. Care is brought to you in an appropriate private setting.',
                            'image_url' => 'cms/sections/private-wellness-visit.png',
                        ],
                        [
                            'title' => 'Group gathering',
                            'body' => 'A thoughtfully planned wellness experience for a small group or community. Each guest completes their own required clinical intake and provider screening through the Hydreight clinical workflow before receiving services.',
                            'image_url' => 'cms/sections/group-gathering.png',
                        ],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
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
            'Features — home trust pillars',
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
            'home' => self::homeDesignStack(),
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
            'why-heartwell' => [
                'Hero — why heartwell',
                'Intro — private thoughts',
                'Rich text — bridge permission',
                'Rich text — three column narrative',
                'Features — what you can expect',
                'Rich text — closing permission',
                'CTA — start with conversation',
            ],
            'wellness-journey' => [
                'Hero — wellness journey split',
                'Rich text — step 1 noticing',
                'Rich text — step 2 not alone',
                'Pathways — wellness journey editorial',
                'Group — step 4 start options',
                'Rich text — step 5 care comes to you',
                'Rich text — step 6 stay connected',
            ],
        ];
    }
}
