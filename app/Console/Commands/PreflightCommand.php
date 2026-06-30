<?php

namespace App\Console\Commands;

use App\Domains\Content\Models\SiteSetting;
use App\Domains\Content\Support\SectionLayout;
use App\Domains\Integrations\Services\MailChannelResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class PreflightCommand extends Command
{
    protected $signature = 'heartwell:preflight {--strict : Fail on production mail/URL warnings}';

    protected $description = 'Run production readiness checks (storage, sitemap, mail, APP_URL)';

    public function handle(MailChannelResolver $mailChannel): int
    {
        $strict = (bool) $this->option('strict');
        $isProduction = app()->environment('production');

        $checks = [
            'Storage linked' => is_link(public_path('storage')),
            'Sitemap exists' => File::exists(public_path('sitemap.xml')),
            'Robots.txt route available' => filled(SectionLayout::defaultRobotsTxt()),
            'Site settings seeded' => SiteSetting::query()->exists(),
            'APP_KEY set' => filled(config('app.key')),
            'APP_URL configured' => filled(config('app.url')),
        ];

        if ($isProduction || $strict) {
            $checks['APP_URL not localhost'] = ! str_contains((string) config('app.url'), 'localhost')
                && ! str_contains((string) config('app.url'), '127.0.0.1');
            $checks['Mail not log driver'] = config('mail.default') !== 'log';
            $checks['Mail channel (SMTP or SendGrid)'] = $mailChannel->resolveOrNull() !== null;
        } else {
            $checks['Mail channel (SMTP or SendGrid)'] = $mailChannel->resolveOrNull() !== null
                || config('mail.default') === 'log';
        }

        $failed = false;

        foreach ($checks as $label => $ok) {
            $this->line($ok ? "✓ {$label}" : "✗ {$label}");
            $failed = $failed || ! $ok;
        }

        if ($mailChannel->resolveOrNull() !== null) {
            $this->newLine();
            $this->comment('Mail: configure test recipient in Admin → Email / SMTP, then send a test email.');
        }

        if (filled(config('app.url'))) {
            $sampleUrl = URL::route('filament.admin.auth.login', [], absolute: true);
            $this->newLine();
            $this->line("Sample admin URL: {$sampleUrl}");
            $this->comment('Invite and password-reset links use APP_URL — verify this matches your public domain.');
        }

        if (filled(config('integrations.hydreight.portal_url'))) {
            $this->line('✓ Hydreight portal URL configured');
        } else {
            $this->warn('Hydreight portal URL not set — /clinical-intake will show fallback message.');
        }

        if (filled(config('integrations.acuity.embed_url'))) {
            $this->line('✓ Acuity embed URL configured');
            $webhook = url('/webhooks/acuity');
            $secret = config('integrations.acuity.webhook_secret');
            $this->comment('Acuity webhook: '.($secret ? $webhook.'?secret=YOUR_SECRET' : $webhook));
        } else {
            $this->warn('Acuity embed URL not set — Contact page shows waitlist/consultation only.');
        }

        if (filled(config('heartwell.ga4_measurement_id'))) {
            $this->line('✓ GA4 measurement ID configured');
        } elseif ($isProduction || $strict) {
            $this->warn('GA4 measurement ID not set — analytics will not load.');
        }

        $hydreightWebhook = url('/webhooks/hydreight');
        if (filled(config('integrations.hydreight.webhook_secret'))) {
            $this->comment('Hydreight clearance webhook: '.$hydreightWebhook.'?secret=YOUR_SECRET');
        } else {
            $this->comment('Hydreight clearance webhook (optional): '.$hydreightWebhook);
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
