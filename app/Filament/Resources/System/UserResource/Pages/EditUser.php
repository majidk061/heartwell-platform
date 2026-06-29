<?php

namespace App\Filament\Resources\System\UserResource\Pages;

use App\Filament\Resources\Pages\HeartWellEditRecord;
use App\Filament\Resources\System\UserResource;

class EditUser extends HeartWellEditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();
        $data['roles'] = $record->roles->pluck('name')->all();
        $data['permissions'] = $record->permissions->pluck('name')->all();
        $data['effective_permissions'] = $record->getAllPermissions()->pluck('name')->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();

        if ($record->hasRole('super_admin')) {
            unset($data['roles'], $data['permissions'], $data['effective_permissions']);

            return $data;
        }

        $roles = $data['roles'] ?? [];
        $permissions = $data['permissions'] ?? [];
        unset($data['roles'], $data['permissions'], $data['effective_permissions']);

        $record->syncRoles($roles);
        $record->syncPermissions($permissions);

        return $data;
    }
}
