<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Integrations\Exceptions\MailNotConfiguredException;

class MailChannelResolver
{
    public function __construct(
        private readonly SettingsResolver $settings,
        private readonly SendGridService $sendGrid,
    ) {}

    public function isSmtpConfigured(): bool
    {
        $mailer = $this->settings->get('mail_mailer', 'MAIL_MAILER') ?? config('mail.default');

        if ($mailer !== 'smtp') {
            return false;
        }

        return filled($this->settings->get('mail_host', 'MAIL_HOST'))
            && filled($this->settings->get('mail_username', 'MAIL_USERNAME'))
            && filled($this->settings->get('mail_password', 'MAIL_PASSWORD'));
    }

    public function isSendGridConfigured(): bool
    {
        return $this->sendGrid->isConfigured();
    }

    /**
     * @return 'smtp'|'sendgrid'
     */
    public function resolve(): string
    {
        $smtp = $this->isSmtpConfigured();
        $sendGrid = $this->isSendGridConfigured();

        if ($smtp && $sendGrid) {
            throw MailNotConfiguredException::ambiguous();
        }

        if (! $smtp && ! $sendGrid) {
            throw MailNotConfiguredException::none();
        }

        return $smtp ? 'smtp' : 'sendgrid';
    }

    public function resolveOrNull(): ?string
    {
        try {
            return $this->resolve();
        } catch (MailNotConfiguredException) {
            return null;
        }
    }
}
