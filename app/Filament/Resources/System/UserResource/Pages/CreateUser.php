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
        try {
            $user = app(InviteAdminUserAction::class)->execute($data);

            Notification::make()
                ->title('Invite sent to '.$user->email)
                ->success()
                ->send();

            return $user;
        } catch (\Throwable $exception) {
            report($exception);

            $user = \App\Models\User::query()->where('email', $data['email'] ?? '')->first();

            Notification::make()
                ->title('User saved but invite email failed')
                ->body($exception->getMessage())
                ->warning()
                ->send();

            if ($user) {
                return $user;
            }

            throw $exception;
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return null;
    }
}
