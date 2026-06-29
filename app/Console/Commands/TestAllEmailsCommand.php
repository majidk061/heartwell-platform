<?php

namespace App\Console\Commands;

use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use App\Domains\Integrations\Models\EmailTemplate;
use App\Domains\Integrations\Services\SettingsResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestAllEmailsCommand extends Command
{
    protected $signature = 'heartwell:test-emails {email : Recipient address for all test emails}';

    protected $description = 'Send all enabled email templates to the given address using configured SMTP';

    public function handle(SettingsResolver $settingsResolver, SendTemplatedEmailAction $sendTemplatedEmail): int
    {
        $email = $this->argument('email');
        $settingsResolver->mergeIntoConfig();

        $sampleData = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $email,
            'phone' => '555-0100',
            'message' => 'This is a test message from HeartWell.',
            'event_name' => 'Wellness Gathering',
            'guest_count' => '8',
            'booking_date' => now()->addWeek()->toDateString(),
            'host_name' => 'Test Host',
            'name' => 'Test Admin',
            'source' => 'website',
            'reset_url' => url('/admin'),
        ];

        $this->info('Sending SMTP connectivity test…');

        try {
            Mail::raw('HeartWell SMTP connectivity test.', fn ($message) => $message->to($email)->subject('HeartWell SMTP Test'));
            $this->line('  ✓ SMTP raw test');
        } catch (\Throwable $e) {
            $this->error('  ✗ SMTP failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $templates = EmailTemplate::query()->where('is_enabled', true)->orderBy('key')->get();

        if ($templates->isEmpty()) {
            $this->warn('No enabled email templates found. Run EmailTemplateSeeder.');

            return self::SUCCESS;
        }

        foreach ($templates as $template) {
            $sent = $sendTemplatedEmail->execute($template->key, $email, $sampleData);
            $this->line($sent ? "  ✓ {$template->key}" : "  ✗ {$template->key} (disabled or missing)");
        }

        $this->info("Done. Check inbox: {$email}");

        return self::SUCCESS;
    }
}
