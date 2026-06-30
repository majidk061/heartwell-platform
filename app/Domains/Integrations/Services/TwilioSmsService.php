<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Integrations\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioSmsService implements SmsServiceInterface
{
    public function send(string $toPhone, string $message): bool
    {
        if (! $this->isConfigured()) {
            Log::info('[Twilio stub] SMS', compact('toPhone', 'message'));

            return true;
        }

        try {
            $sid = config('integrations.twilio.account_sid');
            $token = config('integrations.twilio.auth_token');
            $from = config('integrations.twilio.from_number');

            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post('https://api.twilio.com/2010-04-01/Accounts/'.$sid.'/Messages.json', [
                    'From' => $from,
                    'To' => $toPhone,
                    'Body' => $message,
                ]);

            if (! $response->successful()) {
                Log::error('[Twilio] SMS failed', ['phone' => $toPhone, 'body' => $response->body()]);

                return false;
            }

            return true;
        } catch (\Throwable $exception) {
            Log::error('[Twilio] SMS exception', ['phone' => $toPhone, 'error' => $exception->getMessage()]);

            return false;
        }
    }

    public function isConfigured(): bool
    {
        $config = config('integrations.twilio');

        return ($config['enabled'] ?? false)
            && filled($config['account_sid'])
            && filled($config['auth_token'])
            && filled($config['from_number']);
    }
}
