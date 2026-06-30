<?php

namespace App\Domains\Admin\Actions;

use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class SendAdminPasswordResetInviteAction
{
    public function execute(User $user, bool $isResend = false): void
    {
        $token = Password::broker('users')->createToken($user);

        $resetUrl = Filament::getResetPasswordUrl($token, $user);

        $mergeData = [
            'name' => $user->name,
            'email' => $user->email,
            'reset_url' => $resetUrl,
        ];

        $sent = app(SendTemplatedEmailAction::class)->execute('admin_invite', $user->email, $mergeData);

        if ($sent) {
            return;
        }

        $subject = $isResend
            ? 'Set your HeartWell admin password (reminder)'
            : 'Set your HeartWell admin password';

        $body = "You have been invited to manage HeartWell.\n\nSet your password: {$resetUrl}\n\nThis link expires in 60 minutes.";

        Mail::raw($body, fn ($message) => $message->to($user->email)->subject($subject));
    }
}
