<?php

namespace App\Notifications;

use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminInviteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly User $user,
        public readonly string $resetUrl,
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $sent = app(SendTemplatedEmailAction::class)->execute('admin_invite', $this->user->email, [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'reset_url' => $this->resetUrl,
        ]);

        if ($sent) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('You have been invited to HeartWell Admin');
        }

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Set your HeartWell admin password')
            ->greeting('Welcome to HeartWell')
            ->line('You have been invited to manage the HeartWell website.')
            ->action('Set your password', $this->resetUrl)
            ->line('This link expires in 60 minutes.');
    }
}
