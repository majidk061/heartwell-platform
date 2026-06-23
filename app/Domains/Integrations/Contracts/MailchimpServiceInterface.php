<?php

namespace App\Domains\Integrations\Contracts;

interface MailchimpServiceInterface
{
    /**
     * @param  array<int, string>  $tags
     */
    public function subscribe(string $email, string $firstName, ?string $lastName = null, array $tags = []): ?string;

    public function unsubscribe(string $email): bool;

    public function isConfigured(): bool;
}
