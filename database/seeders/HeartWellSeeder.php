<?php

namespace Database\Seeders;

use App\Domains\Automation\Models\AutomationRule;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SupportPathway;
use App\Domains\CRM\Enums\AvatarType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HeartWellSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdminUser();
        $this->seedPages();
        $this->seedSupportPathways();
        $this->seedAutomationRules();
    }

    private function seedAdminUser(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@heartwellwellness.com'],
            [
                'name' => 'HeartWell Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
    }

    private function seedPages(): void
    {
        $pages = [
            [
                'slug' => 'home',
                'title' => 'Home',
                'sort_order' => 1,
                'sections' => [
                    ['section_type' => 'hero', 'heading' => 'Thoughtful, Compassionate Care You Can Trust', 'content' => ['subheading' => 'For Every Stage of Life', 'body' => 'Feeling exhausted? Stuck? Not feeling like yourself? HeartWell offers nurse-guided wellness support for every stage of life.']],
                    ['section_type' => 'intro', 'heading' => "You're Not Alone. You Deserve Support.", 'content' => ['body' => 'HeartWell is nurse-led mobile wellness — warm, clinically credentialed, and built around your whole story.']],
                    ['section_type' => 'founder_teaser', 'heading' => 'Meet the Founder', 'content' => ['body' => 'Jacquie Wilson, BSN, RN, MBA founded HeartWell to bring thoughtful, compassionate care you can trust.']],
                ],
            ],
            [
                'slug' => 'support-pathways',
                'title' => 'Support Pathways',
                'sort_order' => 2,
                'sections' => [
                    ['section_type' => 'hero', 'heading' => 'Support Pathways', 'content' => ['body' => 'Empathetic guidance — not a treatment catalog.']],
                ],
            ],
            [
                'slug' => 'your-experience',
                'title' => 'Your Experience',
                'sort_order' => 3,
                'sections' => [
                    ['section_type' => 'hero', 'heading' => 'Your Experience with HeartWell', 'content' => ['body' => 'A clear, supportive journey from first hello to ongoing care.']],
                    ['section_type' => 'journey', 'heading' => 'What to expect', 'content' => ['steps' => ['Connect', 'Consult', 'Plan', 'Experience', 'Follow up']]],
                ],
            ],
            [
                'slug' => 'why-heartwell',
                'title' => 'Why HeartWell',
                'sort_order' => 4,
                'sections' => [
                    ['section_type' => 'hero', 'heading' => 'Why HeartWell', 'content' => ['body' => 'Nurse-led. Mobile-friendly. Rooted in whole-person wellness.']],
                ],
            ],
            [
                'slug' => 'wellness-journey',
                'title' => 'Wellness Journey',
                'sort_order' => 5,
                'sections' => [
                    ['section_type' => 'hero', 'heading' => 'Your Wellness Journey', 'content' => ['body' => 'Education and support for conditions and life transitions.']],
                ],
            ],
            [
                'slug' => 'meet-the-founder',
                'title' => 'Meet the Founder',
                'sort_order' => 6,
                'sections' => [
                    ['section_type' => 'hero', 'heading' => 'Meet the Founder', 'content' => ['body' => 'Credentials: BSN, RN, MBA. A warm, clinical voice guiding your care.']],
                    ['section_type' => 'founder_teaser', 'heading' => 'Led with heart and expertise', 'content' => ['credentials' => ['BSN', 'RN', 'MBA']]],
                ],
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact',
                'sort_order' => 7,
                'sections' => [
                    ['section_type' => 'hero', 'heading' => 'Let\'s connect', 'content' => ['body' => 'Join the waitlist, request a consultation, or inquire about group experiences.']],
                    ['section_type' => 'forms', 'heading' => 'Get in touch', 'content' => ['forms' => ['waitlist', 'consultation', 'group_inquiry']]],
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            $sections = $pageData['sections'];
            unset($pageData['sections']);

            $page = Page::query()->updateOrCreate(
                ['slug' => $pageData['slug']],
                array_merge($pageData, [
                    'meta_title' => $pageData['title'].' | HeartWell',
                    'meta_description' => 'HeartWell Aesthetics & Wellness — '.$pageData['title'],
                    'is_published' => true,
                ]),
            );

            foreach ($sections as $index => $section) {
                PageSection::query()->updateOrCreate(
                    [
                        'page_id' => $page->id,
                        'section_type' => $section['section_type'],
                    ],
                    [
                        'heading' => $section['heading'],
                        'sort_order' => $index + 1,
                        'content' => $section['content'],
                        'is_published' => true,
                    ],
                );
            }
        }
    }

    private function seedSupportPathways(): void
    {
        $pathways = [
            [
                'slug' => 'recovery-hydration',
                'title' => 'Recovery & Hydration Support',
                'intro' => 'Support when your body needs restoration and replenishment.',
                'avatar_type' => AvatarType::Depleted,
                'cta_label' => 'Book a Visit',
                'cta_url' => '/contact#book',
                'accordion_content' => [
                    ['title' => 'When you need recovery support', 'body' => 'Empathetic guidance for depletion, fatigue, and recovery — not a treatment menu.'],
                ],
            ],
            [
                'slug' => 'energy-wellness',
                'title' => 'Energy & Wellness Support',
                'intro' => 'For when you are functioning but exhausted.',
                'avatar_type' => AvatarType::Depleted,
                'cta_label' => 'Request Consultation',
                'cta_url' => '/contact#consultation',
                'accordion_content' => [
                    ['title' => 'Energy and vitality', 'body' => 'Nurse-led support for burnout, brain fog, and low energy.'],
                ],
            ],
            [
                'slug' => 'metabolic-weight',
                'title' => 'Metabolic / Weight Support',
                'intro' => 'When you are trying but feel stuck.',
                'avatar_type' => AvatarType::Frustrated,
                'cta_label' => 'Request Consultation',
                'cta_url' => '/contact#consultation',
                'accordion_content' => [
                    ['title' => 'Metabolic shifts', 'body' => 'Support for weight and metabolism changes with compassion, not judgment.'],
                ],
            ],
            [
                'slug' => 'advanced-cellular',
                'title' => 'Advanced Cellular Support',
                'intro' => 'Deeper wellness support guided by clinical expertise.',
                'avatar_type' => AvatarType::Frustrated,
                'cta_label' => 'Book a Visit',
                'cta_url' => '/contact#book',
                'accordion_content' => [
                    ['title' => 'Cellular wellness', 'body' => 'Educational, supportive guidance — never a clinical catalog.'],
                ],
            ],
            [
                'slug' => 'confidence-aesthetic',
                'title' => 'Confidence & Aesthetic Support',
                'intro' => 'When how you see yourself is changing.',
                'avatar_type' => AvatarType::Confidence,
                'cta_label' => 'Book a Visit',
                'cta_url' => '/contact#book',
                'accordion_content' => [
                    ['title' => 'Confidence through transitions', 'body' => 'Support for self-image and appearance changes with warmth and trust.'],
                ],
            ],
        ];

        foreach ($pathways as $index => $pathway) {
            SupportPathway::query()->updateOrCreate(
                ['slug' => $pathway['slug']],
                array_merge($pathway, [
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ]),
            );
        }
    }

    private function seedAutomationRules(): void
    {
        $rules = [
            [
                'name' => 'Waitlist Welcome Email',
                'trigger_type' => 'waitlist.joined',
                'channel' => 'email',
                'template_ref' => config('integrations.sendgrid.templates.waitlist_welcome'),
            ],
            [
                'name' => 'Waitlist Mailchimp Subscribe',
                'trigger_type' => 'waitlist.joined',
                'channel' => 'mailchimp',
                'template_ref' => null,
            ],
            [
                'name' => 'Consultation Acknowledgement',
                'trigger_type' => 'consultation.requested',
                'channel' => 'email',
                'template_ref' => config('integrations.sendgrid.templates.consultation_ack'),
            ],
            [
                'name' => 'Lead Booked Confirmation',
                'trigger_type' => 'lead.status_changed',
                'channel' => 'email',
                'template_ref' => config('integrations.sendgrid.templates.booking_confirmation'),
                'conditions' => ['status' => 'booked'],
            ],
        ];

        foreach ($rules as $rule) {
            AutomationRule::query()->updateOrCreate(
                ['name' => $rule['name']],
                array_merge($rule, ['is_active' => true, 'delay_minutes' => 0]),
            );
        }
    }
}
