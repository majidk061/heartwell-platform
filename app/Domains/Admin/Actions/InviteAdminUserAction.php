<?php

namespace App\Domains\Admin\Actions;

use App\Models\User;
use App\Notifications\AdminInviteNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class InviteAdminUserAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, ?User $existingUser = null): User
    {
        $user = $existingUser ?? User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make(Str::random(32)),
            'is_active' => $data['is_active'] ?? true,
            'invited_at' => now(),
        ]);

        if ($existingUser) {
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'is_active' => $data['is_active'] ?? $user->is_active,
                'invited_at' => now(),
            ]);
        }

        if (! empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        if (! empty($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        $token = Password::broker('users')->createToken($user);
        $resetUrl = route('filament.admin.auth.password-reset.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        $sent = app(\App\Domains\Integrations\Actions\SendTemplatedEmailAction::class)->execute('admin_invite', $user->email, [
            'name' => $user->name,
            'email' => $user->email,
            'reset_url' => $resetUrl,
        ]);

        if (! $sent) {
            \Illuminate\Support\Facades\Mail::raw(
                "You have been invited to manage HeartWell.\n\nSet your password: {$resetUrl}",
                fn ($message) => $message->to($user->email)->subject('Set your HeartWell admin password'),
            );
        }

        return $user;
    }
}
