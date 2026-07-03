<?php

namespace App\Domains\Content\Support;

use App\Domains\CRM\Enums\AvatarType;

class ClientCopyCatalog
{
    public const COMPLIANCE_INTAKE = 'To comply with New Jersey medical regulations and ensure client safety, all clients receiving services must complete a secure clinical intake, health history, consent forms, and provider screening through HeartWell\'s HIPAA-compliant clinical portal powered by Hydreight. Clinical clearance is required before receiving treatment. To continue receiving services, clinical screening and provider clearance must be renewed every 6 months, or sooner if required by the provider.';

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
                'intro' => 'This pathway may be a good fit if you are looking for foundational hydration and replenishment support during busy seasons, after travel, after increased activity, or during times when your body may need extra support.',
                'options_may_include' => [
                    'IV hydration support',
                    'Targeted nutrient add-ons when available and clinically appropriate',
                    'Injection support if a focused nutrient option better fits your goals',
                ],
                'common_support' => "IV hydration fluids support fluid replenishment and foundational hydration. When available and appropriate, electrolyte, vitamin, or mineral support may be added based on your goals and provider review.",
                'portal_cue' => "This pathway may appear as Hydration.\n\nHydration may include Lactated Ringer's or Sodium Chloride 0.9%, depending on availability, provider guidance, and clinical appropriateness.",
                'avatar_type' => AvatarType::Depleted,
                'cta_label' => 'Book a Visit',
                'cta_url' => '/contact#book',
            ],
            [
                'slug' => 'energy-wellness',
                'title' => 'Energy & Wellness',
                'tagline' => 'For feeling depleted, foggy, sluggish, or not like yourself',
                'intro' => 'This pathway may be a good fit if you feel worn down, low-energy, foggy, overextended, or like you are not bouncing back the way you normally do.',
                'options_may_include' => [
                    'Energy-focused IV wellness support',
                    'B12 injection support',
                    'B-Complex / BPlex injection support',
                    'Targeted add-ons to hydration therapy when available and clinically appropriate',
                ],
                'common_support' => "B12 supports energy, metabolism, and nervous system wellness.\n\nB-Complex / BPlex supports energy production, stress response, and overall wellness.",
                'selection_note' => 'You usually do not need both B12 and B-Complex / BPlex. Choose the one that best fits your main goal, and provider review will help confirm what is appropriate.',
                'portal_cue' => 'This pathway may appear as Energy, B12, B-Complex, or BPlex, depending on the option selected and what is available through the secure portal.',
                'avatar_type' => AvatarType::Depleted,
                'cta_label' => 'Request Consultation',
                'cta_url' => '/contact#consultation',
            ],
            [
                'slug' => 'metabolic-weight',
                'title' => 'Metabolic & Weight Support',
                'tagline' => 'For body changes, weight frustration, and metabolic wellness support',
                'intro' => 'This pathway may be a good fit if you are doing many of the "right" things, but your body is not responding the way you hoped it would.',
                'options_may_include' => [
                    'Nutrient-based metabolic injection support',
                    'MIC+B12 or Lipo-style support when available',
                    'Consultation-based weight support',
                    'GLP-1 / Semaglutide or Tirzepatide workflow when clinically appropriate',
                ],
                'common_support' => "B12 supports energy, metabolism, and nervous system wellness.\n\nMIC / Lipo blends support metabolic wellness and nutrient-based weight-support goals.\n\nCarnitine, when available, supports fat metabolism and energy production when clinically appropriate.",
                'selection_note' => 'If you are choosing a metabolic injection option, you usually do not need to add another similar B-vitamin option unless provider guidance indicates otherwise. Choose the option that best matches your primary goal.',
                'portal_cue' => 'This pathway may appear as MIC+B12, Lipo, Lipo Mino, Lipo Stat Plus, GLP-1, Semaglutide, or Tirzepatide, depending on the option selected and what is available through the secure portal.',
                'avatar_type' => AvatarType::Frustrated,
                'cta_label' => 'Request Consultation',
                'cta_url' => '/contact#consultation',
            ],
            [
                'slug' => 'specialized-support',
                'title' => 'Specialized Support',
                'tagline' => 'For advanced cellular wellness and consultation-based support',
                'intro' => 'This pathway may be a good fit if you are interested in more specialized wellness support, including NAD+ or other consultation-based therapies when available and clinically appropriate.',
                'options_may_include' => [
                    'NAD+ support',
                    'Advanced wellness support',
                    'Consultation-based therapies when available and clinically appropriate',
                ],
                'common_support' => 'NAD+ supports cellular energy and advanced wellness goals.',
                'portal_cue' => "This pathway may appear as NAD, NAD+, NAD IV, or NAD IM, depending on what is available through the secure portal.\n\nSpecialized options may require additional screening or provider guidance before treatment is approved.",
                'avatar_type' => AvatarType::Frustrated,
                'cta_label' => 'Book a Visit',
                'cta_url' => '/contact#book',
                'migrate_from_slug' => 'advanced-cellular',
            ],
            [
                'slug' => 'precision-glow-therapy',
                'title' => 'Precision Glow Therapy',
                'tagline' => 'For visible changes in the mirror',
                'intro' => 'This pathway may be a good fit for the woman noticing visible changes in the mirror — including changes to her skin, eyes, or hair. HeartWell offers thoughtful care designed to address her concerns.',
                'options_may_include' => [
                    'Beauty or glow-focused IV wellness support',
                    'Glutathione support',
                    'Biotin support',
                    'Targeted nutrient injections or add-ons when available and clinically appropriate',
                    'Select aesthetic services when available',
                ],
                'common_support' => "Glutathione supports antioxidant wellness and cellular health.\n\nBiotin supports hair, skin, and nail wellness.\n\nVitamin C, when available, supports antioxidant wellness and collagen-related wellness.",
                'portal_cue' => 'This pathway may appear as Beauty/Youth, Glutathione, Biotin, or related wellness options depending on what is available through the secure portal.',
                'coming_soon' => 'Select aesthetic services, including neurotoxins, will be available soon.',
                'avatar_type' => AvatarType::Confidence,
                'cta_label' => 'Book a Visit',
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
                'cta_label' => 'Explore Metabolic Support',
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
                'answer' => 'No. Many women reach out knowing they want to feel better or address a specific goal, but they are not sure where to begin. HeartWell starts with a private wellness conversation so we can discuss your goals, answer your questions, and help you understand what type of support may be appropriate.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'clinical-screening',
                'question' => 'Is clinical screening required before I receive care?',
                'answer' => 'Yes. To support your safety and privacy, all clients must complete a secure intake, health history review, consent forms, and provider screening through HeartWell\'s HIPAA-compliant medical intake portal before receiving services. Clinical clearance is required before care is provided.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'visit-location',
                'question' => 'Where do HeartWell visits take place?',
                'answer' => 'HeartWell offers private mobile wellness visits in the privacy of home, a quiet office setting, or another appropriate, pre-arranged private location.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'support-types',
                'question' => 'What types of wellness support does HeartWell offer?',
                'answer' => 'HeartWell offers wellness support that may include hydration, nutrient support, targeted vitamin injections, metabolic and weight-focused support, and specialized wellness pathways. Select aesthetic services, including neurotoxins, will be available soon.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'wellness-gathering',
                'question' => 'Can I host a private wellness gathering?',
                'answer' => 'Yes. HeartWell offers private wellness gatherings for small groups of friends, boutique fitness studios, salons, women\'s groups, workplaces, and community partners.',
                'page_slug' => 'wellness-journey',
            ],
            [
                'key' => 'guaranteed-results',
                'question' => 'Are specific health or aesthetic results guaranteed?',
                'answer' => 'No. HeartWell does not guarantee specific health, wellness, weight, or aesthetic outcomes. Recommendations are guided by your goals, health history, and required clinical screening to help determine what may be safe and appropriate for you.',
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
            .'<p><strong>Helpful selection note:</strong> Please choose the option that best matches your primary goal. You usually do not need to select multiple similar vitamin options, such as both B12 and B-Complex / BPlex. Your secure intake and provider screening will help determine what is clinically appropriate before treatment is provided.</p>';

        $finalNoteBody = '<p>The pathway you choose helps HeartWell understand your goals and helps you recognize what you may see inside the secure Hydreight portal.</p>'
            .'<p>Your final treatment plan depends on your health history, required intake, provider screening, clinical clearance, and what is available through the secure portal.</p>'
            .'<p>All services are provided only after required intake, provider screening, and clinical clearance through HeartWell\'s secure medical intake portal powered by Hydreight.</p>';

        return [
            'Hero — support pathways' => [
                'section_type' => 'hero',
                'heading' => 'Support Pathways',
                'description' => 'Support Pathways page hero.',
                'content' => [
                    'subheading' => 'Thoughtful Wellness Support, Guided by Your Goals',
                    'body' => "HeartWell pathways are designed to help you choose the type of support that best matches how you are feeling and what you are hoping to address. Some options may be offered as IV wellness support, targeted injections, or add-ons to hydration therapy when available and clinically appropriate.\n\nAfter choosing a HeartWell pathway, you may be directed to a secure medical intake and booking portal powered by Hydreight. The portal may use more clinical treatment names than the HeartWell pathway names shown here. Each pathway below includes guidance on what you may see in the secure portal. Your selection helps us understand your goals, but it does not replace clinical screening. Before treatment is provided, your health history, intake, and provider review help confirm what is appropriate for you.",
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Intro — clinical intake clearance' => [
                'section_type' => 'intro',
                'heading' => 'Required Clinical Intake & Clearance',
                'description' => 'Prominent NJ compliance callout for Support Pathways.',
                'content' => [
                    'design_variant' => 'compliance_callout',
                    'body' => self::COMPLIANCE_INTAKE,
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
                'description' => 'Four-step Hydreight portal flow.',
                'content' => [
                    'steps' => [
                        ['title' => 'Secure Hydreight Portal', 'description' => 'You may see clinical treatment names that differ from HeartWell pathway names.'],
                        ['title' => 'Intake + Consent Forms', 'description' => 'Complete your health history and consent forms through the secure portal.'],
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
                    'body' => 'Not sure where to start? Request a consultation — we will guide you without pressure.',
                    'variant' => 'dual',
                    'primary_label' => 'Book a Visit',
                    'primary_url' => '/contact#book',
                    'waitlist_label' => 'Join the Waitlist',
                    'waitlist_url' => '/contact#waitlist',
                    'show_consultation_link' => true,
                    'consultation_label' => 'Request Consultation',
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            'Hero — your experience' => [
                'section_type' => 'hero',
                'heading' => 'Your HeartWell Experience',
                'description' => 'Your Experience page hero.',
                'content' => [
                    'body' => 'From your very first message to your wellness visit, HeartWell is designed to feel thoughtful, supportive, and easy to understand.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Journey steps — 5 steps' => [
                'section_type' => 'journey',
                'heading' => 'What to expect',
                'description' => 'Five-step journey for Your Experience.',
                'content' => [
                    'steps' => [
                        ['title' => 'Connecting & Understanding', 'description' => 'Your care begins with a private wellness conversation. This is our time to connect, listen to your goals, and understand the kind of support you are looking for.'],
                        ['title' => 'Secure Clinical Intake', 'description' => 'Before your first service, you will complete a secure clinical intake and provider screening through HeartWell\'s HIPAA-compliant portal. This important step helps protect your privacy, support your safety, and ensure clinical clearance before care is provided.'],
                        ['title' => 'Dedicated, Nurse-Led Care', 'description' => 'Every visit is nurse-led, clinically supported, and centered around you. Our goal is to help you feel cared for, informed, and comfortable every step of the way.'],
                        ['title' => 'Your Private HeartWell Visit', 'description' => 'Your visit is designed to feel calm, professional, and personal. HeartWell brings nurse-led wellness support to you, with thoughtful setup, attentive care, and a visit experience centered on your comfort.'],
                        ['title' => 'Thoughtful Follow-Up', 'description' => 'Your care does not end when the visit is complete. HeartWell provides thoughtful follow-up and guidance so your wellness support can continue to reflect your needs, goals, and next steps.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Hero — why heartwell' => [
                'section_type' => 'hero',
                'heading' => 'Why HeartWell',
                'description' => 'Why HeartWell page hero.',
                'content' => [
                    'body' => 'HeartWell was created for women who want care that feels personal, thoughtful, and clinically grounded. Our approach blends nurse-led wellness support with comfort, privacy, and a deep respect for each woman\'s story.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Features — differentiators' => [
                'section_type' => 'features',
                'heading' => 'What makes HeartWell different',
                'description' => 'Four trust pillars for Why HeartWell.',
                'content' => [
                    'features' => [
                        ['title' => 'Nurse-Led Care', 'body' => 'Your care is personally provided by Jacquie Wilson, BSN, RN, MBA. With years of nursing experience across hospital care, home health, health advocacy, and patient-centered support, Jacquie brings clinical knowledge, compassion, and a calm, reassuring presence to every interaction.'],
                        ['title' => 'Clinically Supported', 'body' => 'Your safety and peace of mind matter. Before receiving services, clients complete a secure intake, health history review, consent forms, and provider screening through HeartWell\'s HIPAA-compliant medical intake portal. Clinical clearance is required before care is provided.'],
                        ['title' => 'Designed Around Real Life', 'body' => 'Wellness support should fit into your life, not add more stress to it. HeartWell offers mobile visits designed to meet women where they are — whether in the privacy of home, a quiet office setting, or a small wellness gathering with friends.'],
                        ['title' => 'Compassion at the Center', 'body' => 'At HeartWell, true care begins with listening first. We are here to support the whole person with thoughtful, respectful care that helps women feel heard, supported, and cared for through every stage of life.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Hero — wellness journey' => [
                'section_type' => 'hero',
                'heading' => 'Your Wellness Journey',
                'description' => 'Wellness Journey page hero.',
                'content' => [
                    'body' => 'Your wellness needs can change over time, and your care should reflect where you are today. At HeartWell, there is no one-size-fits-all approach. Your support begins with listening to your goals, understanding your concerns, and reviewing your health history.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Rich text — wellness journey intro' => [
                'section_type' => 'rich_text',
                'heading' => null,
                'description' => 'Wellness Journey intro paragraph.',
                'content' => [
                    'body' => '<p>Together, we focus on the type of care that feels thoughtful, appropriate, and aligned with this stage of life.</p>',
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Features — tailored to your life' => [
                'section_type' => 'features',
                'heading' => 'Tailored to Your Life',
                'description' => 'Four journey subsections for Wellness Journey.',
                'content' => [
                    'features' => [
                        ['title' => 'Start Where You Are', 'body' => 'You do not need to have everything figured out before you reach out. Many women begin with a simple concern, a specific goal, or the feeling that something in their body has changed. HeartWell begins by listening first, helping you understand your options and what type of support may be appropriate.'],
                        ['title' => 'Choose the Right Level of Support', 'body' => 'Your care is guided by your needs, goals, and required clinical screening. Depending on what is appropriate for you, your wellness support may include hydration, nutrient support, targeted vitamin injections, metabolic and weight-focused support, or a more specialized wellness pathway.'],
                        ['title' => 'Adjust as Your Needs Change', 'body' => 'Wellness needs can change over time, and your care should be able to adapt with you. As your goals, concerns, or needs shift, HeartWell can help you consider thoughtful next steps that continue to feel appropriate and aligned with your life.'],
                        ['title' => 'Personal, Not Generic', 'body' => 'HeartWell is not built around a one-size-fits-all menu. Your care is guided by real conversation, clinical screening, and a thoughtful understanding of what kind of support makes sense for you.'],
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
            'Hero — meet the founder' => [
                'section_type' => 'hero',
                'heading' => 'Meet the Founder',
                'description' => 'Meet the Founder page hero.',
                'content' => [
                    'body' => 'Jacquie Wilson brings nurse-led, clinically grounded care to every HeartWell experience.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Founder teaser — full page' => [
                'section_type' => 'founder_teaser',
                'heading' => 'Meet the Founder',
                'description' => 'Extended founder bio for Meet the Founder page.',
                'content' => [
                    'design_variant' => 'photo_left',
                    'name' => 'Jacquie Wilson',
                    'role' => 'Founder & Director of Care',
                    'body' => 'HeartWell Aesthetics & Wellness was founded by Jacquie Wilson, a registered nurse with a deep commitment to compassionate, thoughtful care. With years of diverse nursing experience across emergency care, critical care, home health, and patient advocacy, Jacquie understands how important it is for women to feel heard, respected, and cared for — especially when navigating changes in their energy, weight, appearance, or overall well-being.',
                    'credentials' => ['BSN', 'RN', 'MBA'],
                    'pronunciation' => 'Pronounced Jack-Kwa',
                    'subsections' => [
                        ['title' => 'A Nurse-Led Approach', 'body' => 'Jacquie brings a calm, supportive presence to every HeartWell visit. Her approach begins with listening first. By taking time to understand each client\'s goals and concerns, she helps women feel informed, comfortable, and supported throughout the care process.'],
                        ['title' => 'Why HeartWell Was Created', 'body' => 'HeartWell was created to support women who may not feel like themselves and are looking for care that feels personal, clinically grounded, and easy to understand. Jacquie envisioned a wellness experience that combines nurse-led care with the comfort, privacy, and compassion of mobile support.'],
                        ['title' => 'The HeartWell Promise', 'body' => 'At HeartWell, care is never meant to feel rushed, confusing, or transactional. Every interaction is guided by respect, professional integrity, and the belief that women deserve thoughtful support through every stage of life.'],
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
                    'consultation_label' => 'Request Consultation',
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            'Hero — contact' => [
                'section_type' => 'hero',
                'heading' => 'Connect with HeartWell',
                'description' => 'Contact page hero.',
                'content' => [
                    'subheading' => 'Begin with a Private Wellness Conversation',
                    'body' => 'You do not need to have a specific service picked out before you reach out. HeartWell begins with a private conversation so we can discuss your goals, answer your questions, and help you understand what type of support may be appropriate.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            'Contact forms block' => [
                'section_type' => 'forms',
                'heading' => 'How Would You Like to Experience HeartWell?',
                'description' => 'Waitlist, consultation, booking, and group inquiry forms.',
                'content' => [
                    'section_subtitle' => 'Please select the option below that best fits what you are looking for.',
                    'waitlist_title' => 'Join the Waitlist',
                    'waitlist_subtitle' => 'Be notified when private consultation openings or mobile visit availability becomes available in your area.',
                    'consultation_title' => 'Request a Private Mobile Visit',
                    'consultation_subtitle' => 'For women interested in personalized, one-on-one mobile wellness support in the privacy of home, a quiet office, or another appropriate private location.',
                    'group_title' => 'Plan a Wellness Gathering',
                    'group_subtitle' => 'For friends, boutique studios, salons, women\'s groups, workplaces, or community partners interested in hosting a private HeartWell wellness gathering.',
                    'forms' => ['waitlist', 'consultation', 'group_inquiry'],
                    'contact_disclaimer' => config('heartwell.compliance.contact_disclaimer'),
                    'privacy_summary' => config('heartwell.compliance.privacy_summary'),
                    'clinical_portal_note' => 'To support your safety and privacy, all clients must complete a secure intake, health history review, consent forms, and provider screening through HeartWell\'s HIPAA-compliant medical intake portal before receiving services. Clinical clearance is required before care is provided.',
                    'group_intake_note' => config('heartwell.compliance.group_intake_note'),
                    'layout' => ['container_width' => 'default', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            'Intro — safety and clinical care' => [
                'section_type' => 'intro',
                'heading' => 'Safety and clinical care',
                'description' => 'Clinical safety intro for Your Experience.',
                'content' => [
                    'body' => self::COMPLIANCE_INTAKE,
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
        ];
    }

    /**
     * @return array<string, list<string>>
     */
    public static function pageSectionStacks(): array
    {
        return [
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
