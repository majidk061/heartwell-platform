<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->can('system.users.manage');
    }

    public function view(User $user, User $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, User $model): bool
    {
        if ($model->hasRole('super_admin') && ! $user->hasRole('super_admin')) {
            return false;
        }

        return $this->viewAny($user);
    }

    public function delete(User $user, User $model): bool
    {
        if ($model->hasRole('super_admin')) {
            return false;
        }

        if ($model->id === $user->id) {
            return false;
        }

        return $user->hasRole('super_admin');
    }
}
