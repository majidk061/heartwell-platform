<?php

namespace App\Filament\Concerns;

use Illuminate\Support\Facades\Auth;

trait AuthorizesWithPermissions
{
    protected static function permissionPrefix(): string
    {
        return '';
    }

    public static function canViewAny(): bool
    {
        return static::userHasAnyPermission(static::viewPermissions());
    }

    public static function canCreate(): bool
    {
        return static::userHasAnyPermission(static::createPermissions());
    }

    public static function canEdit($record): bool
    {
        return static::userHasAnyPermission(static::editPermissions());
    }

    public static function canDelete($record): bool
    {
        return static::userHasAnyPermission(static::deletePermissions());
    }

    public static function canView($record): bool
    {
        return static::userHasAnyPermission(static::viewPermissions());
    }

    /**
     * @return list<string>
     */
    protected static function viewPermissions(): array
    {
        $prefix = static::permissionPrefix();

        if (str_starts_with($prefix, 'system.')) {
            return ["{$prefix}.manage"];
        }

        return $prefix ? ["{$prefix}.view"] : [];
    }

    /**
     * @return list<string>
     */
    protected static function createPermissions(): array
    {
        $prefix = static::permissionPrefix();

        if (str_starts_with($prefix, 'system.')) {
            return ["{$prefix}.manage"];
        }

        return $prefix ? ["{$prefix}.edit", "{$prefix}.create"] : [];
    }

    /**
     * @return list<string>
     */
    protected static function editPermissions(): array
    {
        $prefix = static::permissionPrefix();

        if (str_starts_with($prefix, 'system.')) {
            return ["{$prefix}.manage"];
        }

        return $prefix ? ["{$prefix}.edit"] : [];
    }

    /**
     * @return list<string>
     */
    protected static function deletePermissions(): array
    {
        $prefix = static::permissionPrefix();

        if (str_starts_with($prefix, 'system.')) {
            return ["{$prefix}.manage"];
        }

        return $prefix ? ["{$prefix}.delete"] : [];
    }

    /**
     * @param  list<string>  $permissions
     */
    protected static function userHasAnyPermission(array $permissions): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if (method_exists($user, 'is_active') && $user->is_active === false) {
            return false;
        }

        if (! \Illuminate\Support\Facades\Schema::hasTable('permissions')) {
            return true;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        if ($permissions === []) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }
}
