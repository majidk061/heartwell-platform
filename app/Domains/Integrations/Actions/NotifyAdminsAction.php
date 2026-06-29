<?php

namespace App\Domains\Integrations\Actions;

class NotifyAdminsAction
{
    public function __construct(
        private readonly ResolveNotificationRecipientsAction $resolveRecipients,
        private readonly SendTemplatedEmailAction $sendTemplatedEmail,
    ) {}

    /**
     * @param  array<string, mixed>  $mergeData
     */
    public function execute(string $eventKey, string $templateKey, array $mergeData): void
    {
        foreach ($this->resolveRecipients->execute($eventKey) as $email) {
            $this->sendTemplatedEmail->execute($templateKey, $email, $mergeData);
        }
    }
}
