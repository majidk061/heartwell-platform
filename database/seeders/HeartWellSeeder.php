<?php

namespace Database\Seeders;

use App\Domains\Automation\Models\AutomationRule;
use App\Domains\Content\Models\AvatarCard;
use App\Domains\Content\Models\Faq;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Models\SiteSetting;
use App\Domains\Content\Models\SupportPathway;
use App\Domains\Content\Support\ClientCopyCatalog;
use App\Domains\Content\Support\SectionLayout;
use App\Models\User;
use Illuminate\Database\Seeder;

class HeartWellSeeder extends Seeder
{
    private const IMG_HERO = 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=900&q=80';

    private const IMG_FOUNDER = 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=900&q=80';

    private const IMG_DEPLETED = 'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?w=800&q=80';

    private const IMG_METABOLIC = 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800&q=80';

    private const IMG_CONFIDENCE = 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&q=80';

    private const IMG_WELLNESS = 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800&q=80';

    public function run(): void
    {
        $this->seedAdminUser();
        $this->seedSiteSettings();
        $this->seedSectionTemplates();
        $this->seedPages();
        $this->seedAvatarCards();
        $this->seedSupportPathways();
        $this->seedFaqs();
        $this->seedAutomationRules();
    }

    private function seedAdminUser(): void
    {
        $user = User::query()->firstOrNew(['email' => 'admin@heartwellwellness.com']);
        $user->name = 'HeartWell Admin';
        $user->password = 'password';
        $user->email_verified_at = now();
        $user->is_active = true;
        $user->save();
    }

    private function seedPages(): void
    {
        $placements = [
            'home' => [
                'title' => 'Home',
                'sort_order' => 1,
                'templates' => [
                    'Hero — home banner',
                    'Avatar intro block',
                    'Intro — home nurse-led care',
                    'Pathways teaser',
                    'Testimonials — grid',
                    'Founder teaser',
                    'Standard CTA band',
                ],
            ],
            'support-pathways' => [
                'title' => 'Support Pathways',
                'sort_order' => 2,
                'templates' => [
                    'Hero — support pathways',
                    'Intro — clinical intake clearance',
                    'Rich text — IV injection add-ons',
                    'Pathways teaser — guided cards',
                    'Rich text — final treatment selection',
                    'Journey — Hydreight portal flow',
                    'CTA — support pathways',
                ],
            ],
            'your-experience' => [
                'title' => 'Your Experience',
                'sort_order' => 3,
                'templates' => [
                    'Hero — your experience',
                    'Journey steps — 5 steps',
                    'Group vs individual comparison',
                    'Intro — safety and clinical care',
                    'CTA — your experience',
                ],
            ],
            'why-heartwell' => [
                'title' => 'Why HeartWell',
                'sort_order' => 4,
                'templates' => [
                    'Hero — why heartwell',
                    'Features — differentiators',
                    'CTA — start with conversation',
                ],
            ],
            'wellness-journey' => [
                'title' => 'Wellness Journey',
                'sort_order' => 5,
                'templates' => [
                    'Hero — wellness journey',
                    'Rich text — wellness journey intro',
                    'Features — tailored to your life',
                    'FAQ block',
                    'CTA — wellness journey',
                ],
            ],
            'meet-the-founder' => [
                'title' => 'Meet the Founder',
                'sort_order' => 6,
                'templates' => [
                    'Hero — meet the founder',
                    'Founder teaser — full page',
                    'CTA — connect with team',
                ],
            ],
            'contact' => [
                'title' => 'Contact',
                'sort_order' => 7,
                'templates' => [
                    'Hero — contact',
                    'Contact forms block',
                ],
            ],
        ];

        foreach ($placements as $slug => $pageData) {
            $templateNames = $pageData['templates'];
            unset($pageData['templates']);

            $page = Page::query()->updateOrCreate(
                ['slug' => $slug],
                array_merge($pageData, [
                    'meta_title' => $pageData['title'].' | HeartWell',
                    'meta_description' => 'HeartWell Aesthetics & Wellness — '.$pageData['title'],
                    'is_published' => true,
                ]),
            );

            PageSection::query()->where('page_id', $page->id)->delete();

            foreach ($templateNames as $index => $templateName) {
                $template = SectionTemplate::query()->where('name', $templateName)->firstOrFail();

                PageSection::query()->create([
                    'page_id' => $page->id,
                    'section_template_id' => $template->id,
                    'section_type' => $template->section_type,
                    'heading' => null,
                    'content' => null,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ]);
            }
        }
    }

    private function seedSectionTemplates(): void
    {
        $templates = [
            [
                'name' => 'Hero — client split (home)',
                'section_type' => 'hero',
                'heading' => 'Thoughtful, Compassionate Care You Can Trust',
                'description' => 'Client mock — split hero with pathway bar settings.',
                'content' => [
                    'design_variant' => 'split_image_right',
                    'subheading' => 'For Every Stage of Life',
                    'intro_question' => 'Feeling exhausted? Stuck? Not feeling like yourself?',
                    'body' => 'HeartWell offers nurse-guided wellness support for every stage of life — personalized, compassionate, and led by a registered nurse.',
                    'image_url' => self::IMG_HERO,
                    'show_consultation_link' => false,
                    'pathway_bar_variant' => 'labeled_inline_dividers',
                    'pathway_bar_heading' => 'Support Options Include:',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Hero — home banner',
                'section_type' => 'hero',
                'heading' => 'Thoughtful, Compassionate Care You Can Trust',
                'description' => 'Classic split hero with consultation link.',
                'content' => [
                    'design_variant' => 'default',
                    'subheading' => 'For Every Stage of Life',
                    'body' => 'Feeling exhausted? Stuck? Not feeling like yourself? HeartWell offers nurse-guided wellness support for every stage of life.',
                    'image_url' => self::IMG_HERO,
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Hero — full bleed overlay',
                'section_type' => 'hero',
                'heading' => 'Thoughtful, Compassionate Care You Can Trust',
                'description' => 'Full-width background image with text overlay on the left (client mock).',
                'content' => [
                    'design_variant' => 'full_bleed_overlay',
                    'subheading' => 'For Every Stage of Life',
                    'intro_question' => 'Feeling exhausted? Stuck? Not feeling like yourself?',
                    'body' => "Many women continue showing up every day while quietly feeling exhausted, depleted, stressed, or unlike themselves.\n\nHeartWell provides personalized, nurse-guided wellness support designed to help restore energy, recovery, confidence, and overall well-being.",
                    'image_url' => self::IMG_HERO,
                    'show_consultation_link' => false,
                    'layout' => ['container_width' => 'full', 'section_padding' => 'none', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Hero — centered overlay',
                'section_type' => 'hero',
                'heading' => 'Thoughtful, Compassionate Care You Can Trust',
                'description' => 'Centered text over full-width image.',
                'content' => [
                    'design_variant' => 'centered_overlay',
                    'subheading' => 'For Every Stage of Life',
                    'image_url' => self::IMG_HERO,
                    'layout' => ['container_width' => 'full', 'section_padding' => 'none'],
                ],
            ],
            [
                'name' => 'Hero — minimal band',
                'section_type' => 'hero',
                'heading' => 'Page headline here',
                'description' => 'Text-only hero band for inner pages.',
                'content' => [
                    'design_variant' => 'minimal',
                    'body' => 'Short supporting line for this page.',
                    'layout' => ['container_width' => 'narrow', 'background' => 'blush', 'text_align' => 'center'],
                ],
            ],
            [
                'name' => 'Hero — inner page',
                'section_type' => 'hero',
                'heading' => 'Page headline here',
                'description' => 'Simpler hero for inner pages (headline + short intro).',
                'content' => [
                    'body' => 'Short supporting line for this page.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Intro — home nurse-led care',
                'section_type' => 'intro',
                'heading' => 'Nurse-led care that meets you where you are',
                'description' => 'Home page intro block.',
                'content' => [
                    'body' => 'HeartWell is mobile wellness led by Jacquie Wilson, BSN, RN, MBA — warm, clinically credentialed, and built around your whole story. We are not a spa, med spa, or IV menu. We offer thoughtful guidance for women navigating midlife transitions, burnout, and confidence shifts.',
                    'layout' => ['container_width' => 'narrow', 'background' => 'dusty_blue', 'text_align' => 'center'],
                ],
            ],
            [
                'name' => 'Avatar intro — client horizontal',
                'section_type' => 'avatar_intro',
                'heading' => "You're Not Alone. You Deserve Support.",
                'description' => 'Client mock — horizontal cards with image left.',
                'content' => [
                    'design_variant' => 'horizontal_split_cards',
                    'subheading' => 'Which of these feels most like you?',
                    'max_cards' => 3,
                    'show_unifying_message' => false,
                    'layout' => ['container_width' => 'extra_wide', 'text_align' => 'center'],
                ],
            ],
            [
                'name' => 'Avatar intro block',
                'section_type' => 'avatar_intro',
                'heading' => "You're Not Alone. You Deserve Support.",
                'description' => 'Classic vertical portrait audience cards.',
                'content' => [
                    'design_variant' => 'default',
                    'subheading' => 'Which of these feels most like you?',
                    'unifying_message' => "I don't feel like myself anymore.",
                    'card_columns' => '3',
                    'max_cards' => 3,
                    'layout' => ['container_width' => 'default', 'text_align' => 'center'],
                ],
            ],
            [
                'name' => 'Journey steps — 5 steps',
                'section_type' => 'journey',
                'heading' => 'What to expect',
                'description' => 'Numbered step cards for Your Experience-style pages.',
                'content' => [
                    'steps' => [
                        ['title' => 'Connect', 'description' => 'Reach out via waitlist, consultation, or booking.'],
                        ['title' => 'Consult', 'description' => 'A nurse-led conversation about your goals.'],
                        ['title' => 'Plan', 'description' => 'Together we map a pathway that fits your life.'],
                        ['title' => 'Experience', 'description' => 'Your visit — calm, mobile, and centered on you.'],
                        ['title' => 'Follow up', 'description' => 'Ongoing support so you never feel alone.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Founder teaser',
                'section_type' => 'founder_teaser',
                'heading' => 'Meet the Founder',
                'description' => 'Photo, bio, credentials, and pronunciation.',
                'content' => [
                    'design_variant' => 'photo_left',
                    'body' => 'Jacquie Wilson, BSN, RN, MBA founded HeartWell to bring thoughtful, compassionate care you can trust.',
                    'image_url' => self::IMG_FOUNDER,
                    'credentials' => ['BSN', 'RN', 'MBA'],
                    'pronunciation' => 'Pronounced Jack-Kwa',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Features — differentiators',
                'section_type' => 'features',
                'heading' => 'What makes us different',
                'description' => 'Grid of feature cards for Why HeartWell.',
                'content' => [
                    'features' => [
                        ['title' => 'Nurse-led care', 'body' => 'Clinically credentialed guidance you can trust — not a generic spa menu.'],
                        ['title' => 'Mobile wellness', 'body' => 'Care that comes to you with convenience and personalization.'],
                        ['title' => 'Whole-person support', 'body' => 'We see your full story — not just a single symptom or service.'],
                        ['title' => 'Compassion first', 'body' => 'Education without overwhelm. You are never just a transaction.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Group vs individual comparison',
                'section_type' => 'group_individual',
                'heading' => 'Individual visits vs group gatherings',
                'description' => 'Two-column comparison block.',
                'content' => [
                    'body' => '<p>Individual visits are one-on-one wellness support. Group gatherings are hosted experiences — each guest still completes their own clinical intake.</p>',
                    'columns' => [
                        ['title' => 'Individual visit', 'body' => 'Book for yourself via Acuity. One nurse-led experience tailored to you.'],
                        ['title' => 'Group gathering', 'body' => 'Host inquires here. Each guest completes their own secure HeartWell clinical intake before services.'],
                    ],
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            [
                'name' => 'FAQ block',
                'section_type' => 'faq',
                'heading' => 'Common questions',
                'description' => 'FAQ accordion — assign FAQs to this page slug in Website Content → FAQs.',
                'content' => [
                    'include_unassigned' => false,
                    'layout' => ['container_width' => 'narrow', 'background' => 'taupe'],
                ],
            ],
            [
                'name' => 'Rich text — educational content',
                'section_type' => 'rich_text',
                'heading' => 'You are not alone in this',
                'description' => 'Longer formatted content with optional image.',
                'content' => [
                    'body' => '<p>Hormonal shifts, burnout, and life transitions are common — and addressable. HeartWell connects how you feel with supportive pathways forward.</p>',
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            [
                'name' => 'Testimonials — grid',
                'section_type' => 'testimonials',
                'heading' => 'What Our Clients Say',
                'description' => 'Client quotes grid — manage quotes under Website Content → Testimonials.',
                'content' => [
                    'subtitle' => 'Real stories from women who found support with HeartWell.',
                    'display_mode' => 'grid',
                    'count' => 6,
                    'enabled' => true,
                    'layout' => ['container_width' => 'default', 'background' => 'taupe'],
                ],
            ],
            [
                'name' => 'Pathways teaser',
                'section_type' => 'pathways_teaser',
                'heading' => 'Support Pathways',
                'description' => 'Pathway accordion preview — pathways managed under Website Content → Support Pathways.',
                'content' => [
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'CTA — client pre-footer band',
                'section_type' => 'cta',
                'heading' => 'You Deserve to Feel Like Yourself Again',
                'description' => 'Client mock — cream band with dual CTAs.',
                'content' => [
                    'design_variant' => 'centered_band',
                    'body' => "Whether you're feeling depleted, stuck, or simply unlike yourself, support is available.",
                    'variant' => 'dual',
                    'show_consultation_link' => false,
                    'layout' => ['container_width' => 'default', 'section_padding' => 'spacious', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Standard CTA band',
                'section_type' => 'cta',
                'heading' => 'Ready to take the next step?',
                'description' => 'Dual-button CTA with consultation link.',
                'content' => [
                    'design_variant' => 'default',
                    'body' => 'Book a visit or join the waitlist — we are here when you are ready.',
                    'variant' => 'dual',
                    'primary_label' => 'Book a Visit',
                    'primary_url' => '/contact#book',
                    'waitlist_label' => 'Join the Waitlist',
                    'waitlist_url' => '/contact#waitlist',
                    'show_consultation_link' => true,
                    'consultation_prefix' => 'Prefer to talk first?',
                    'consultation_label' => 'Request Consultation',
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            [
                'name' => 'Contact forms block',
                'section_type' => 'forms',
                'heading' => 'How would you like to connect?',
                'description' => 'Waitlist, consultation, booking, and group inquiry forms.',
                'content' => [
                    'section_subtitle' => 'Choose a path below — we are here when you are ready.',
                    'waitlist_title' => 'Join the Waitlist',
                    'waitlist_subtitle' => 'Be the first to know when new appointments open.',
                    'consultation_title' => 'Request a Consultation',
                    'consultation_subtitle' => 'Tell us a little about yourself — we will reach out personally.',
                    'group_title' => 'Group Wellness Gathering',
                    'group_subtitle' => 'Planning a group experience? Start here.',
                    'forms' => ['waitlist', 'consultation', 'group_inquiry'],
                    'contact_disclaimer' => config('heartwell.compliance.contact_disclaimer'),
                    'privacy_summary' => config('heartwell.compliance.privacy_summary'),
                    'clinical_portal_note' => config('heartwell.compliance.clinical_portal_note'),
                    'group_intake_note' => config('heartwell.compliance.group_intake_note'),
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Hero — support pathways',
                'section_type' => 'hero',
                'heading' => 'Support Pathways',
                'description' => 'Support Pathways page hero.',
                'content' => [
                    'body' => 'Empathetic guidance — not a treatment catalog. Explore nurse-led pathways designed to help you understand your options and choose support with confidence.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Rich text — support pathways guidance',
                'section_type' => 'rich_text',
                'heading' => 'Guidance, not a menu',
                'description' => 'Support Pathways educational copy.',
                'content' => [
                    'body' => '<p>Every woman\'s experience is different. Support Pathways help you explore how HeartWell can meet you — whether you need recovery support, energy guidance, metabolic clarity, or confidence through transition.</p><p>Each pathway includes educational context and a clear next step. Your nurse-led team helps you decide what fits — without pressure or overwhelm.</p>',
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            [
                'name' => 'Pathways teaser — explore',
                'section_type' => 'pathways_teaser',
                'heading' => 'Explore your pathways',
                'description' => 'Pathway accordion with explore heading.',
                'content' => [
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'CTA — support pathways',
                'section_type' => 'cta',
                'heading' => 'Find the pathway that fits you',
                'description' => 'Support Pathways page CTA.',
                'content' => [
                    'body' => 'Not sure where to start? Request a consultation — we will guide you.',
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
            [
                'name' => 'Hero — your experience',
                'section_type' => 'hero',
                'heading' => 'Your Experience with HeartWell',
                'description' => 'Your Experience page hero.',
                'content' => [
                    'body' => 'A clear, supportive journey from first hello to ongoing care.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Intro — safety and clinical care',
                'section_type' => 'intro',
                'heading' => 'Safety and clinical care',
                'description' => 'Clinical safety intro for Your Experience.',
                'content' => [
                    'body' => 'Every client completes clinical intake, screening, and clearance before services. HeartWell coordinates your experience; our licensed clinical partner maintains secure medical records. Clinical clearance is renewed every 6 months, or sooner if required.',
                    'layout' => ['container_width' => 'narrow', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'CTA — your experience',
                'section_type' => 'cta',
                'heading' => 'Ready when you are',
                'description' => 'Your Experience page CTA.',
                'content' => [
                    'body' => 'Take the first step — we will walk with you from hello to follow-up.',
                    'variant' => 'dual',
                    'primary_label' => 'Book a Visit',
                    'primary_url' => '/contact#book',
                    'waitlist_label' => 'Join the Waitlist',
                    'waitlist_url' => '/contact#waitlist',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            [
                'name' => 'Hero — why heartwell',
                'section_type' => 'hero',
                'heading' => 'Why HeartWell',
                'description' => 'Why HeartWell page hero.',
                'content' => [
                    'body' => 'Nurse-led. Mobile-friendly. Rooted in whole-person wellness. For women navigating midlife transitions, burnout, and confidence shifts (typically ages 35–65). HeartWell is not a spa, med spa, or IV menu — we offer thoughtful guidance, education, and personalized support.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'CTA — start with conversation',
                'section_type' => 'cta',
                'heading' => 'Start with a conversation',
                'description' => 'Why HeartWell page CTA.',
                'content' => [
                    'body' => 'We would love to hear your story and help you find the right support.',
                    'variant' => 'dual',
                    'show_consultation_link' => true,
                    'consultation_label' => 'Request Consultation',
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            [
                'name' => 'Hero — wellness journey',
                'section_type' => 'hero',
                'heading' => 'Your Wellness Journey',
                'description' => 'Wellness Journey page hero.',
                'content' => [
                    'body' => 'Education and support for conditions and life transitions.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Rich text — wellness journey',
                'section_type' => 'rich_text',
                'heading' => 'You are not alone in this',
                'description' => 'Wellness Journey educational copy.',
                'content' => [
                    'body' => '<p>Hormonal shifts, burnout, metabolic changes, and life transitions are common — and addressable. HeartWell connects how you feel with supportive pathways forward.</p><p>Whether you are navigating perimenopause, caregiver burnout, or changes in energy and confidence, education and nurse-led guidance can help you feel seen and supported.</p>',
                    'layout' => ['container_width' => 'narrow', 'background' => 'white', 'text_align' => 'left'],
                ],
            ],
            [
                'name' => 'CTA — wellness journey',
                'section_type' => 'cta',
                'heading' => 'Take the next step on your journey',
                'description' => 'Wellness Journey page CTA.',
                'content' => [
                    'body' => 'Join the waitlist or request a consultation — we are here when you are ready.',
                    'variant' => 'dual',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            [
                'name' => 'Hero — meet the founder',
                'section_type' => 'hero',
                'heading' => 'Meet the Founder',
                'description' => 'Meet the Founder page hero.',
                'content' => [
                    'body' => 'Credentials: BSN, RN, MBA. A warm, clinical voice guiding your care.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'Founder teaser — full page',
                'section_type' => 'founder_teaser',
                'heading' => 'Led with heart and expertise',
                'description' => 'Extended founder bio for Meet the Founder page.',
                'content' => [
                    'body' => 'Jacquie Wilson brings decades of nursing leadership and a passion for whole-person wellness to every HeartWell experience. Her clinical background and compassionate approach shape how HeartWell educates, guides, and supports clients through every stage of life.',
                    'image_url' => self::IMG_FOUNDER,
                    'credentials' => ['BSN', 'RN', 'MBA'],
                    'pronunciation' => 'Pronounced Jack-Kwa',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
            [
                'name' => 'CTA — connect with team',
                'section_type' => 'cta',
                'heading' => 'Connect with Jacquie\'s team',
                'description' => 'Meet the Founder page CTA.',
                'content' => [
                    'body' => 'Request a consultation to learn how HeartWell can support your wellness journey.',
                    'variant' => 'dual',
                    'show_consultation_link' => true,
                    'consultation_label' => 'Request Consultation',
                    'consultation_url' => '/contact#consultation',
                    'layout' => ['container_width' => 'default', 'background' => 'dusty_blue'],
                ],
            ],
            [
                'name' => 'Hero — contact',
                'section_type' => 'hero',
                'heading' => 'Let\'s connect',
                'description' => 'Contact page hero.',
                'content' => [
                    'body' => 'Join the waitlist, request a consultation, or inquire about group experiences.',
                    'layout' => ['container_width' => 'default', 'background' => 'white'],
                ],
            ],
        ];

        foreach ($templates as $index => $template) {
            $content = $template['content'];
            $layout = $content['layout'] ?? [];
            unset($content['layout']);

            SectionTemplate::query()->updateOrCreate(
                ['name' => $template['name']],
                [
                    'section_type' => $template['section_type'],
                    'heading' => $template['heading'],
                    'description' => $template['description'],
                    'content' => $content,
                    'layout' => $layout,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ],
            );
        }

        $this->seedClientCopyTemplates();
    }

    private function seedClientCopyTemplates(): void
    {
        foreach (ClientCopyCatalog::sectionTemplates() as $name => $template) {
            $content = $template['content'];
            $layout = $content['layout'] ?? [];
            unset($content['layout']);

            SectionTemplate::query()->updateOrCreate(
                ['name' => $name],
                [
                    'section_type' => $template['section_type'],
                    'heading' => $template['heading'],
                    'description' => $template['description'],
                    'content' => $content,
                    'layout' => $layout,
                    'is_published' => true,
                ],
            );
        }
    }

    private function seedSiteSettings(): void
    {
        SiteSetting::query()->updateOrCreate(['key' => 'brand'], ['value' => config('heartwell.brand')]);
        SiteSetting::query()->updateOrCreate(['key' => 'navigation'], ['value' => config('heartwell.navigation')]);
        SiteSetting::query()->updateOrCreate(['key' => 'ctas'], ['value' => config('heartwell.ctas')]);
        SiteSetting::query()->updateOrCreate(['key' => 'compliance'], ['value' => config('heartwell.compliance')]);
        SiteSetting::query()->updateOrCreate(['key' => 'branding'], ['value' => [
            'logo_mode' => 'image',
            'logo_text' => 'HeartWell Aesthetics & Wellness',
            'logo_tagline' => 'Compassionate Care for Every Stage of Life',
            'logo_image_path' => 'cms/branding/heartwell-logo-trimmed.png',
            'logo_trimmed_path' => 'cms/branding/heartwell-logo-trimmed.png',
        ]]);
        SiteSetting::query()->updateOrCreate(['key' => 'home'], ['value' => [
            'testimonials_enabled' => false,
            'testimonials_count' => 6,
            'testimonials_display_mode' => 'grid',
        ]]);
        SiteSetting::query()->updateOrCreate(['key' => 'contact_forms'], ['value' => []]);
        $adminEmail = config('mail.from.address', 'admin@heartwellwellness.com');
        SiteSetting::query()->updateOrCreate(['key' => 'email_notifications'], ['value' => [
            'default_admin_emails' => [$adminEmail],
            'waitlist_admin_emails' => [$adminEmail],
            'consultation_admin_emails' => [$adminEmail],
            'group_inquiry_admin_emails' => [$adminEmail],
            'booking_admin_emails' => [$adminEmail],
            'new_lead_admin_emails' => [$adminEmail],
        ]]);
        SiteSetting::query()->updateOrCreate(['key' => 'seo'], ['value' => [
            'robots_index' => true,
            'default_meta_title' => config('heartwell.brand.name'),
            'robots_txt_content' => SectionLayout::defaultRobotsTxt(),
            'sitemap_enabled' => true,
            'sitemap_extra_urls' => [
                ['path' => '/clinical-intake', 'priority' => 0.5, 'changefreq' => 'monthly'],
                ['path' => '/my-visit', 'priority' => 0.6, 'changefreq' => 'monthly'],
            ],
        ]]);
        SiteSetting::query()->updateOrCreate(['key' => 'theme'], ['value' => [
            'site_width' => 'standard',
            'default_container_width' => 'default',
            'default_section_padding' => 'normal',
            'default_section_background' => 'white',
            'header_mode' => 'sticky',
            'header_style' => 'solid_cream',
            'header_show_border' => true,
            'colors' => SectionLayout::defaultThemeColors(),
            'navigation_style' => [
                'hover_effect' => 'color',
                'hover_color' => '#e8967a',
                'active_style' => 'underline',
                'active_color' => '#e8967a',
                'header_cta_count' => 2,
            ],
        ]]);
    }

    private function seedAvatarCards(): void
    {
        $imageMap = [
            'depleted' => self::IMG_DEPLETED,
            'frustrated' => self::IMG_METABOLIC,
            'confidence' => self::IMG_CONFIDENCE,
        ];

        foreach (ClientCopyCatalog::avatarCards() as $index => $card) {
            AvatarCard::query()->updateOrCreate(
                ['slug' => $card['slug']],
                array_merge($card, [
                    'image_path' => $imageMap[$card['slug']] ?? null,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ]),
            );
        }
    }

    private function seedSupportPathways(): void
    {
        $imageMap = [
            'recovery-hydration' => self::IMG_WELLNESS,
            'energy-wellness' => self::IMG_DEPLETED,
            'precision-glow-therapy' => self::IMG_CONFIDENCE,
        ];

        foreach (ClientCopyCatalog::supportPathways() as $index => $pathway) {
            unset($pathway['migrate_from_slug']);

            SupportPathway::query()->updateOrCreate(
                ['slug' => $pathway['slug']],
                array_merge($pathway, [
                    'image_path' => $imageMap[$pathway['slug']] ?? null,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ]),
            );
        }

        SupportPathway::query()
            ->whereIn('slug', ['advanced-cellular', 'confidence-aesthetic'])
            ->delete();
    }

    private function seedFaqs(): void
    {
        foreach (ClientCopyCatalog::faqs() as $index => $faq) {
            Faq::query()->updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'page_slug' => $faq['page_slug'],
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ],
            );
        }
    }

    private function seedAutomationRules(): void
    {
        $rules = [
            [
                'name' => 'Waitlist Welcome Email',
                'trigger_type' => 'waitlist_joined',
                'channel' => 'email',
                'template_ref' => 'waitlist_welcome',
                'delay_minutes' => 0,
            ],
            [
                'name' => 'Waitlist Nurture Day 3',
                'trigger_type' => 'waitlist_joined',
                'channel' => 'email',
                'template_ref' => 'waitlist_nurture_day3',
                'delay_minutes' => 4320,
            ],
            [
                'name' => 'Waitlist Nurture Day 7',
                'trigger_type' => 'waitlist_joined',
                'channel' => 'email',
                'template_ref' => 'waitlist_nurture_day7',
                'delay_minutes' => 10080,
            ],
            [
                'name' => 'Waitlist Mailchimp Subscribe',
                'trigger_type' => 'waitlist_joined',
                'channel' => 'mailchimp',
                'template_ref' => null,
                'delay_minutes' => 0,
            ],
            [
                'name' => 'Consultation Acknowledgement',
                'trigger_type' => 'consultation_requested',
                'channel' => 'email',
                'template_ref' => 'consultation_ack',
                'delay_minutes' => 0,
            ],
            [
                'name' => 'Booking Confirmation',
                'trigger_type' => 'booking_synced',
                'channel' => 'email',
                'template_ref' => 'booking_confirmation',
                'delay_minutes' => 0,
            ],
            [
                'name' => 'Lead Booked Confirmation',
                'trigger_type' => 'lead_status_changed',
                'channel' => 'email',
                'template_ref' => 'booking_confirmation',
                'conditions' => ['to_status' => 'booked'],
                'delay_minutes' => 0,
            ],
            [
                'name' => 'Lead Contacted Follow-up',
                'trigger_type' => 'lead_status_changed',
                'channel' => 'email',
                'template_ref' => 'lead_contacted_followup',
                'conditions' => ['to_status' => 'contacted'],
                'delay_minutes' => 2880,
            ],
            [
                'name' => 'Lead Completed Nurture',
                'trigger_type' => 'lead_status_changed',
                'channel' => 'email',
                'template_ref' => 'lead_completed_nurture',
                'conditions' => ['to_status' => 'completed'],
                'delay_minutes' => 1440,
            ],
            [
                'name' => 'Group Inquiry Acknowledgement',
                'trigger_type' => 'group_inquiry_submitted',
                'channel' => 'email',
                'template_ref' => 'group_inquiry_ack',
                'delay_minutes' => 0,
            ],
            [
                'name' => 'Group Inquiry Follow-up',
                'trigger_type' => 'group_inquiry_submitted',
                'channel' => 'email',
                'template_ref' => 'group_followup_resources',
                'delay_minutes' => 1440,
            ],
        ];

        foreach ($rules as $rule) {
            AutomationRule::query()->updateOrCreate(
                ['name' => $rule['name']],
                array_merge(['is_active' => true], $rule),
            );
        }
    }
}
