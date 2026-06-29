<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Pages\ManageIntegrations;
use App\Filament\Pages\ManageMailSettings;
use App\Filament\Pages\ManageSiteSettings;
use App\Filament\Resources\Content\PageResource;
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
        return 'Everything you need to manage the HeartWell website — step by step.';
    }

    /**
     * @return array<int, array{id: string, icon: string, title: string, steps: list<string>, url?: string}>
     */
    public function getSections(): array
    {
        return [
            [
                'id' => 'getting-started',
                'icon' => 'heroicon-o-sparkles',
                'title' => 'Getting started',
                'steps' => [
                    'Log in at /admin with your email and password.',
                    'Use the dashboard quick links to jump to common tasks.',
                    'Super admins see System Settings; editors manage content and leads only.',
                    'Use Forgot password on the login page if you are locked out (SMTP must be configured).',
                ],
                'url' => Dashboard::getUrl(),
            ],
            [
                'id' => 'sections',
                'icon' => 'heroicon-o-squares-2x2',
                'title' => 'Editing pages & sections',
                'steps' => [
                    'Go to Website Content → Pages and click Edit on the page you want.',
                    'Open the Page sections tab.',
                    'Click Add section to create a new block (Hero, Intro, FAQ, etc.).',
                    'Click Edit content on any row — a slide-over panel opens for headline, body, and images.',
                    'Drag the handle on the left of each row to change display order.',
                    'Use Preview on the pages list to open the live public page.',
                    'When uploading images, use the crop tool — hero images should be 4:3.',
                ],
                'url' => PageResource::getUrl('index'),
            ],
            [
                'id' => 'site-settings',
                'icon' => 'heroicon-o-cog-6-tooth',
                'title' => 'Site Settings',
                'steps' => [
                    'Set Logo style to Text, Image, or Both.',
                    'Upload a logo image (crop to wide 10:3 ratio).',
                    'Drag menu items in the Navigation repeater to reorder the header menu.',
                    'Edit primary and secondary button labels and anchors.',
                    'Set footer contact info, compliance text, and Google Analytics ID.',
                ],
                'url' => ManageSiteSettings::getUrl(),
            ],
            [
                'id' => 'crm',
                'icon' => 'heroicon-o-user-group',
                'title' => 'Leads & CRM',
                'steps' => [
                    'Open Leads & CRM → Leads to see the pipeline tabs (New, Contacted, etc.).',
                    'Click a lead name to open the full view.',
                    'Use Mark contacted when you have reached out to a new lead.',
                    'Use Change status to move leads through the pipeline with optional notes.',
                    'Use Add note to append timestamped internal notes.',
                    'Assign to me or bulk-assign from the list page.',
                ],
                'url' => LeadResource::getUrl('index'),
            ],
            [
                'id' => 'integrations',
                'icon' => 'heroicon-o-puzzle-piece',
                'title' => 'Integrations & Acuity booking',
                'steps' => [
                    'Acuity Scheduling is the online appointment booking service (like Calendly).',
                    'When the client provides Acuity credentials, enter the Embed URL under Integrations → Acuity.',
                    'Until Acuity is connected, visitors use Waitlist and Consultation forms on /contact.',
                    'Bookings sync to Admin → Bookings when webhooks are configured.',
                ],
                'url' => ManageIntegrations::getUrl(),
            ],
            [
                'id' => 'team',
                'icon' => 'heroicon-o-users',
                'title' => 'Team members',
                'steps' => [
                    'Super admins: System Settings → Team Members.',
                    'Create a user with name, email, and roles.',
                    'The invitee receives an email with a link to set their password.',
                    'Use Resend invite if they did not receive the email.',
                    'Super admin accounts cannot be deleted and always have full access.',
                ],
                'url' => UserResource::getUrl('index'),
            ],
            [
                'id' => 'email',
                'icon' => 'heroicon-o-envelope',
                'title' => 'Email & notifications',
                'steps' => [
                    'Configure SMTP under Email / SMTP (host, port, SSL, username, password).',
                    'Use Send test email to verify delivery.',
                    'Edit templates under Email Templates (subject, heading, body, logo).',
                    'Set per-form admin recipients under Email Notifications.',
                    'Run php artisan heartwell:test-emails you@example.com to test all templates.',
                ],
                'url' => ManageMailSettings::getUrl(),
            ],
        ];
    }
}
