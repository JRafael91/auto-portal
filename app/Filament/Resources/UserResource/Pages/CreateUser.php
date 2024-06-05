<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Services\EmailService;
use App\Models\User;
use App\Notifications\ConfirmationEmailNotification;
use Illuminate\Support\Facades\Password;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\CanResetPassword;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $user = User::query()->latest()->first();

        Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            ['email' => $user->email],
            function (CanResetPassword $user, string $token): void {
                if (! method_exists($user, 'notify')) {
                    $userClass = $user::class;
                    throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
                }
 
                $notification = new ConfirmationEmailNotification($token);
                $user->notify($notification);
            },
        );
    }

}
