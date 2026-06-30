<?php

namespace App\Console\Commands;

use App\Domains\Integrations\Actions\GetTestRecipientEmailAction;
use App\Domains\Integrations\Actions\SendTestEmailsAction;
use Illuminate\Console\Command;

class TestAllEmailsCommand extends Command
{
    protected $signature = 'heartwell:test-emails {email? : Recipient address; defaults to Test email recipient in admin}';

    protected $description = 'Send SMTP test and all enabled email templates to the test recipient';

    public function handle(
        GetTestRecipientEmailAction $getTestRecipientEmail,
        SendTestEmailsAction $sendTestEmails,
    ): int {
        $email = $getTestRecipientEmail->execute($this->argument('email'));

        if (! filled($email)) {
            $this->error('No test recipient configured. Set Test email recipient on Email / SMTP settings or pass an email argument.');

            return self::FAILURE;
        }

        $this->info("Sending tests to {$email}…");

        try {
            $result = $sendTestEmails->sendAllTemplateTests($email);
        } catch (\Throwable $e) {
            $this->error('Failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->line('  ✓ SMTP connectivity test');

        foreach ($result['sent'] as $key) {
            $this->line("  ✓ {$key}");
        }

        foreach ($result['skipped'] as $key) {
            $this->line("  ✗ {$key} (disabled or missing)");
        }

        $this->info("Done. Check inbox: {$result['email']}");

        return self::SUCCESS;
    }
}
