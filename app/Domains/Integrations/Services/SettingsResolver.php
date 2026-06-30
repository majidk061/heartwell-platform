<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Automation\Models\AutomationLog;
use App\Domains\Integrations\Models\EncryptedSetting;
use Illuminate\Support\Facades\Crypt;

class SettingsResolver
{
    /** @var array<string, string|null>|null */
    private static ?array $cache = null;

    public function get(string $key, ?string $envFallback = null): ?string
    {
        $settings = $this->all();

        if (isset($settings[$key]) && $settings[$key] !== '') {
            return $settings[$key];
        }

        return $envFallback !== null ? (env($envFallback) ?: null) : null;
    }

    public function set(string $key, ?string $value, string $group = 'general', ?int $userId = null): void
    {
        EncryptedSetting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => $value !== null && $value !== '' ? Crypt::encryptString($value) : null,
                'group' => $group,
                'updated_by' => $userId,
            ],
        );

        self::$cache = null;

        if (str_contains($key, 'password') || str_contains($key, 'api_key') || str_contains($key, 'secret')) {
            AutomationLog::query()->create([
                'status' => 'updated',
                'channel' => 'settings',
                'payload' => ['key' => $key, 'group' => $group, 'updated_by' => $userId],
                'executed_at' => now(),
            ]);
        }
    }

    public function has(string $key): bool
    {
        return filled($this->get($key));
    }

    /**
     * @return array<string, string|null>
     */
    public function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        self::$cache = [];

        foreach (EncryptedSetting::query()->get() as $setting) {
            if ($setting->value === null) {
                self::$cache[$setting->key] = null;

                continue;
            }

            try {
                self::$cache[$setting->key] = Crypt::decryptString($setting->value);
            } catch (\Throwable) {
                self::$cache[$setting->key] = null;
            }
        }

        return self::$cache;
    }

    public function mergeIntoConfig(): void
    {
        $r = $this;

        config([
            'mail.default' => $r->get('mail_mailer', 'MAIL_MAILER') ?? config('mail.default'),
            'mail.mailers.smtp.host' => $r->get('mail_host', 'MAIL_HOST') ?? config('mail.mailers.smtp.host'),
            'mail.mailers.smtp.port' => $r->get('mail_port', 'MAIL_PORT') ?? config('mail.mailers.smtp.port'),
            'mail.mailers.smtp.encryption' => $r->get('mail_encryption', 'MAIL_ENCRYPTION') ?? config('mail.mailers.smtp.encryption'),
            'mail.mailers.smtp.username' => $r->get('mail_username', 'MAIL_USERNAME') ?? config('mail.mailers.smtp.username'),
            'mail.mailers.smtp.password' => $r->get('mail_password', 'MAIL_PASSWORD') ?? config('mail.mailers.smtp.password'),
            'mail.from.address' => $r->get('mail_from_address', 'MAIL_FROM_ADDRESS') ?? config('mail.from.address'),
            'mail.from.name' => $r->get('mail_from_name', 'MAIL_FROM_NAME') ?? config('mail.from.name'),
            'integrations.acuity.enabled' => (bool) ($r->get('acuity_enabled') ?? config('integrations.acuity.enabled')),
            'integrations.acuity.user_id' => $r->get('acuity_user_id', 'ACUITY_USER_ID') ?? config('integrations.acuity.user_id'),
            'integrations.acuity.api_key' => $r->get('acuity_api_key', 'ACUITY_API_KEY') ?? config('integrations.acuity.api_key'),
            'integrations.acuity.webhook_secret' => $r->get('acuity_webhook_secret', 'ACUITY_WEBHOOK_SECRET') ?? config('integrations.acuity.webhook_secret'),
            'integrations.acuity.embed_url' => $r->get('acuity_embed_url', 'ACUITY_EMBED_URL') ?? config('integrations.acuity.embed_url'),
            'integrations.mailchimp.enabled' => (bool) ($r->get('mailchimp_enabled') ?? config('integrations.mailchimp.enabled')),
            'integrations.mailchimp.api_key' => $r->get('mailchimp_api_key', 'MAILCHIMP_API_KEY') ?? config('integrations.mailchimp.api_key'),
            'integrations.mailchimp.server_prefix' => $r->get('mailchimp_server_prefix', 'MAILCHIMP_SERVER_PREFIX') ?? config('integrations.mailchimp.server_prefix'),
            'integrations.mailchimp.audience_id' => $r->get('mailchimp_audience_id', 'MAILCHIMP_AUDIENCE_ID') ?? config('integrations.mailchimp.audience_id'),
            'integrations.sendgrid.enabled' => (bool) ($r->get('sendgrid_enabled') ?? config('integrations.sendgrid.enabled')),
            'integrations.sendgrid.api_key' => $r->get('sendgrid_api_key', 'SENDGRID_API_KEY') ?? config('integrations.sendgrid.api_key'),
            'integrations.sendgrid.from_email' => $r->get('sendgrid_from_email', 'SENDGRID_FROM_EMAIL') ?? config('integrations.sendgrid.from_email'),
            'integrations.sendgrid.from_name' => $r->get('sendgrid_from_name', 'SENDGRID_FROM_NAME') ?? config('integrations.sendgrid.from_name'),
            'integrations.sendgrid.admin_alert_email' => $r->get('sendgrid_admin_alert_email', 'SENDGRID_ADMIN_ALERT_EMAIL') ?? config('integrations.sendgrid.admin_alert_email'),
            'integrations.hydreight.enabled' => (bool) ($r->get('hydreight_enabled') ?? config('integrations.hydreight.enabled')),
            'integrations.hydreight.portal_url' => $r->get('hydreight_portal_url', 'HYDREIGHT_PORTAL_URL') ?? config('integrations.hydreight.portal_url'),
            'integrations.twilio.enabled' => (bool) ($r->get('twilio_enabled') ?? config('integrations.twilio.enabled')),
            'integrations.twilio.account_sid' => $r->get('twilio_account_sid', 'TWILIO_ACCOUNT_SID') ?? config('integrations.twilio.account_sid'),
            'integrations.twilio.auth_token' => $r->get('twilio_auth_token', 'TWILIO_AUTH_TOKEN') ?? config('integrations.twilio.auth_token'),
            'integrations.twilio.from_number' => $r->get('twilio_from_number', 'TWILIO_FROM_NUMBER') ?? config('integrations.twilio.from_number'),
            'heartwell.ga4_measurement_id' => $r->get('ga4_measurement_id', 'HEARTWELL_GA4_MEASUREMENT_ID') ?? config('heartwell.ga4_measurement_id'),
        ]);
    }
}
