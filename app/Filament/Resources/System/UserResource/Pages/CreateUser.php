<?php

namespace App\Filament\Resources\System\UserResource\Pages;

use App\Domains\Admin\Actions\InviteAdminUserAction;
use App\Filament\Resources\Pages\HeartWellCreateRecord;
use App\Filament\Resources\System\UserResource;
use Filament\Notifications\Notification;

class CreateUser extends HeartWellCreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $user = app(InviteAdminUserAction::class)->execute($data);

        Notification::make()
            ->title('Invite sent to '.$user->email)
            ->success()
            ->send();

        return $user;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return null;
    }
}
