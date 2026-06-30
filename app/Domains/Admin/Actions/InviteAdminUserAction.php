<?php

namespace App\Domains\Admin\Actions;

use App\Models\User;
use Illuminate\Support\Str;

class InviteAdminUserAction
{
    public function __construct(
        private readonly SendAdminPasswordResetInviteAction $sendAdminPasswordResetInvite,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, ?User $existingUser = null): User
    {
        $user = $existingUser ?? User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Str::random(32),
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

        $this->sendAdminPasswordResetInvite->execute($user, $existingUser !== null);

        return $user;
    }
}
