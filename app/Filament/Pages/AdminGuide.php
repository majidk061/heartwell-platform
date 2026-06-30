<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Resources\Content\PageResource;
use App\Filament\Resources\Content\SectionTemplateResource;
use App\Filament\Resources\CRM\LeadResource;
use App\Filament\Resources\System\UserResource;
use Filament\Pages\Page;

class AdminGuide extends Page
{
    use AuthorizesWithPermissions;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Help & Guide';

    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.admin-guide';

    protected static ?string $title = 'Help & Guide';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    protected static function permissionPrefix(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return 'Visual workflow for Pages, Section Library, draft/publish, and revisions.';
    }

    /**
     * @return array<int, array{id: string, icon: string, title: string, summary: string, steps: list<string>, url?: string, diagram?: string}>
     */
    public function getSections(): array
    {
        return [
            [
                'id' => 'getting-started',
                'icon' => 'heroicon-o-sparkles',
                'title' => 'Getting started',
                'summary' => 'Log in, permissions, and where to find website content.',
                'steps' => [
                    'Log in at /admin with your email and password.',
                    'Website Content holds pages, Section Library, FAQs, testimonials, and pathways.',
                    'Super admins manage System Settings; editors manage content and leads.',
                ],
                'url' => Dashboard::getUrl(),
            ],
            [
                'id' => 'section-library',
                'icon' => 'heroicon-o-rectangle-stack',
                'title' => 'Section Library (edit content here)',
                'summary' => 'Single source of truth — headline, body, layout, and CTAs live only in the library.',
                'diagram' => 'library',
                'steps' => [
                    'Open Website Content → Section Library.',
                    'Create a template: Step 1 choose section type (Hero, FAQ, Forms, …), Step 2 name it and fill content.',
                    'Use Save draft while working; Publish when ready for the live site.',
                    'Revision history tab stores past versions — restore if needed.',
                    'Need different hero text on Contact vs Home? Create two library templates — never duplicate text on the page row.',
                ],
                'url' => SectionTemplateResource::getUrl('index'),
            ],
            [
                'id' => 'page-sections',
                'icon' => 'heroicon-o-squares-2x2',
                'title' => 'Pages → Page sections (placement only)',
                'summary' => 'Pages only store which library template appears, in what order, and whether it is live on that page.',
                'diagram' => 'placement',
                'steps' => [
                    'Open Website Content → Pages → Edit a page → Page sections tab.',
                    'Insert from library to add a template to this page.',
                    'Drag rows to reorder. Toggle Live on each placement row.',
                    'Click Edit content — opens Section Library for that template (use Back to page sections when done).',
                    'Change template swaps which library entry is linked without copying content.',
                ],
                'url' => PageResource::getUrl('index'),
            ],
            [
                'id' => 'draft-publish',
                'icon' => 'heroicon-o-eye',
                'title' => 'Draft vs Publish',
                'summary' => 'Draft content stays hidden from visitors; published content appears on the public site.',
                'steps' => [
                    'Save draft — keeps working copy invisible on the website.',
                    'Publish — makes the page or template live (syncs Show on website).',
                    'Draft pages: use Preview draft from the pages list or page edit header.',
                    'Published pages: Preview live opens the public URL.',
                ],
            ],
            [
                'id' => 'revisions',
                'icon' => 'heroicon-o-clock',
                'title' => 'Revision history',
                'summary' => 'WordPress-style snapshots on each save, with one-click restore.',
                'steps' => [
                    'On Page edit or Section Library edit, open the Revision history tab.',
                    'Each row shows date, author, and optional note.',
                    'Restore saves the current version first, then applies the selected snapshot.',
                    'Up to 25 revisions are kept per record.',
                ],
            ],
            [
                'id' => 'site-settings',
                'icon' => 'heroicon-o-cog-6-tooth',
                'title' => 'Site Settings',
                'summary' => 'Logo, navigation, theme colors, SEO defaults, and compliance copy.',
                'steps' => [
                    'Set logo mode, navigation order, CTAs, and footer compliance text.',
                    'Theme & Layout controls site width, header style, and brand colors.',
                    'SEO tab controls robots.txt and sitemap defaults.',
                ],
                'url' => ManageSiteSettings::getUrl(),
            ],
            [
                'id' => 'crm',
                'icon' => 'heroicon-o-user-group',
                'title' => 'Leads & CRM',
                'summary' => 'Pipeline for waitlist, consultation, and booking leads.',
                'steps' => [
                    'Open Leads & CRM → Leads for pipeline tabs.',
                    'Change status, add notes, and assign leads from the list or detail view.',
                ],
                'url' => LeadResource::getUrl('index'),
            ],
            [
                'id' => 'integrations',
                'icon' => 'heroicon-o-puzzle-piece',
                'title' => 'Integrations & Acuity',
                'summary' => 'Booking embed, webhooks, Hydreight handoff, and HeartWell-branded client comms.',
                'steps' => [
                    'Acuity: set Embed URL so Contact shows Book a Visit. Register webhook POST /webhooks/acuity?secret=… in Acuity.',
                    'Hydreight: enable portal link only — clients use /clinical-intake (HeartWell branding).',
                    'Test buttons verify required fields; they do not send email. Use Email / SMTP for mail tests.',
                    'Configure Acuity account emails to HeartWell branding, or rely on HeartWell booking_confirmation template.',
                ],
                'url' => ManageIntegrations::getUrl(),
            ],
            [
                'id' => 'production-launch',
                'icon' => 'heroicon-o-rocket-launch',
                'title' => 'Production launch checklist',
                'summary' => 'Mail, APP_URL, and preflight before inviting team or going live.',
                'steps' => [
                    'Set APP_URL in .env to your public domain (e.g. https://heartwellwellness.com).',
                    'Configure Email / SMTP (or SendGrid — not both). Send a test email.',
                    'Run: php artisan heartwell:preflight --strict on the server.',
                    'Import final client copy — see docs/CLIENT_CONTENT_IMPORT.md in the repo.',
                    'Invite team members only after mail is working (password-reset links depend on APP_URL).',
                ],
                'url' => ManageMailSettings::getUrl(),
            ],
            [
                'id' => 'team',
                'icon' => 'heroicon-o-users',
                'title' => 'Team members',
                'summary' => 'Admin users, roles, and invites.',
                'steps' => [
                    'System Settings → Team Members to invite editors.',
                    'Audit trail on content shows who last updated each page or template.',
                ],
                'url' => UserResource::getUrl('index'),
            ],
            [
                'id' => 'email',
                'icon' => 'heroicon-o-envelope',
                'title' => 'Email & notifications',
                'summary' => 'SMTP, templates, and form notification recipients.',
                'steps' => [
                    'Configure SMTP and send a test email.',
                    'Edit templates under Email Templates.',
                    'Set per-form admin recipients under Email Notifications.',
                ],
                'url' => ManageMailSettings::getUrl(),
            ],
        ];
    }
}
