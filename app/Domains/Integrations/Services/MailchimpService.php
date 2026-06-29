<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Integrations\Contracts\MailchimpServiceInterface;
use Illuminate\Support\Facades\Log;
use MailchimpMarketing\ApiClient;

class MailchimpService implements MailchimpServiceInterface
{
    public function subscribe(string $email, string $firstName, ?string $lastName = null, array $tags = []): ?string
    {
        if (! $this->isConfigured()) {
            Log::info('[Mailchimp stub] subscribe', compact('email', 'firstName', 'lastName', 'tags'));

            return null;
        }

        try {
            $client = $this->client();
            $audienceId = config('integrations.mailchimp.audience_id');
            $hash = md5(strtolower($email));

            $client->lists->setListMember($audienceId, $hash, [
                'email_address' => $email,
                'status_if_new' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $firstName,
                    'LNAME' => $lastName ?? '',
                ],
            ]);

            if ($tags !== []) {
                $client->lists->updateListMemberTags($audienceId, $hash, [
                    'tags' => collect($tags)->map(fn (string $tag) => ['name' => $tag, 'status' => 'active'])->all(),
                ]);
            }

            return $hash;
        } catch (\Throwable $e) {
            Log::error('[Mailchimp] subscribe failed', ['email' => $email, 'error' => $e->getMessage()]);

            return null;
        }
    }

    public function unsubscribe(string $email): bool
    {
        if (! $this->isConfigured()) {
            Log::info('[Mailchimp stub] unsubscribe', compact('email'));

            return true;
        }

        try {
            $audienceId = config('integrations.mailchimp.audience_id');
            $hash = md5(strtolower($email));
            $this->client()->lists->updateListMember($audienceId, $hash, [
                'status' => 'unsubscribed',
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('[Mailchimp] unsubscribe failed', ['email' => $email, 'error' => $e->getMessage()]);

            return false;
        }
    }

    public function isConfigured(): bool
    {
        $config = config('integrations.mailchimp');

        return ($config['enabled'] ?? false)
            && filled($config['api_key'])
            && filled($config['audience_id']);
    }

    private function client(): ApiClient
    {
        $client = new ApiClient;
        $client->setConfig([
            'apiKey' => config('integrations.mailchimp.api_key'),
            'server' => config('integrations.mailchimp.server_prefix'),
        ]);

        return $client;
    }
}
