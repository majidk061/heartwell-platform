<?php

namespace App\Domains\Integrations\Actions;

use App\Domains\Content\Actions\GetSiteSettingsAction;
use App\Domains\Integrations\Services\SettingsResolver;

class ResolveNotificationRecipientsAction
{
    public function __construct(
        private readonly GetSiteSettingsAction $getSiteSettings,
        private readonly SettingsResolver $settingsResolver,
    ) {}

    /**
     * @return list<string>
     */
    public function execute(string $eventKey): array
    {
        $settings = $this->getSiteSettings->execute();
        $notifications = $settings['email_notifications'] ?? [];

        $fieldMap = [
            'waitlist' => 'waitlist_admin_emails',
            'consultation' => 'consultation_admin_emails',
            'group_inquiry' => 'group_inquiry_admin_emails',
            'booking' => 'booking_admin_emails',
            'new_lead' => 'new_lead_admin_emails',
        ];

        $field = $fieldMap[$eventKey] ?? 'default_admin_emails';
        $emails = $notifications[$field] ?? $notifications['default_admin_emails'] ?? [];

        if (empty($emails)) {
            $fallback = $this->settingsResolver->get('admin_alert_email', 'ADMIN_ALERT_EMAIL')
                ?? config('integrations.sendgrid.admin_alert_email');

            if ($fallback) {
                $emails = [$fallback];
            }
        }

        return collect($emails)
            ->flatten()
            ->filter(fn ($email) => filled($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->values()
            ->all();
    }
}
