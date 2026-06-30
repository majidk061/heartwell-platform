<?php

namespace App\Domains\Integrations\Actions;

use App\Domains\Integrations\Services\SettingsResolver;

class GetTestRecipientEmailAction
{
    public function execute(?string $override = null): ?string
    {
        if (filled($override)) {
            return $override;
        }

        $resolver = app(SettingsResolver::class);

        $saved = $resolver->get('test_recipient_email');

        if (filled($saved)) {
            return $saved;
        }

        $adminAlert = $resolver->get('admin_alert_email', 'SENDGRID_ADMIN_ALERT_EMAIL');

        if (filled($adminAlert)) {
            return $adminAlert;
        }

        return auth()->user()?->email;
    }
}
