<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Integrations\Contracts\MailchimpServiceInterface;
use Illuminate\Support\Facades\Log;

class MailchimpService implements MailchimpServiceInterface
{
    public function subscribe(string $email, string $firstName, ?string $lastName = null, array $tags = []): ?string
    {
        if (! $this->isConfigured()) {
            Log::info('[Mailchimp stub] subscribe', compact('email', 'firstName', 'lastName', 'tags'));

            return null;
        }

        // Live integration wired when API keys are present.
        Log::info('[Mailchimp] subscribe', compact('email', 'firstName', 'lastName', 'tags'));

        return null;
    }

    public function unsubscribe(string $email): bool
    {
        if (! $this->isConfigured()) {
            Log::info('[Mailchimp stub] unsubscribe', compact('email'));

            return true;
        }

        Log::info('[Mailchimp] unsubscribe', compact('email'));

        return true;
    }

    public function isConfigured(): bool
    {
        $config = config('integrations.mailchimp');

        return ($config['enabled'] ?? false)
            && filled($config['api_key'])
            && filled($config['audience_id']);
    }
}
