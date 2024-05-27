<?php

namespace App\Filament\Pages\Auth;

use Exception;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPasswordNotification; 
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset; 

class RequestPasswordReset extends BaseRequestPasswordReset
{
    public function request(): void
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();
            return;
        }
        $data = $this->form->getState();
        dd($data);
        $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            $data,
            function (CanResetPassword $user, string $token): void {
                if (! method_exists($user, 'notify')) {
                    $userClass = $user::class;
                    throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
                }
 
                $notification = new ResetPasswordNotification($token); 
                $user->notify($notification);
            },
        );
        if ($status !== Password::RESET_LINK_SENT) {
            Notification::make()
                ->title('Ocurrió un error al intentar enviar correo, vuelta a intentarlo.')
                ->danger()
                ->send();
 
            return;
        }
 
        Notification::make()
            ->title('Se envió un correo electrónico con el enlace para restablecer la contraseña.')
            ->success()
            ->send();
 
        $this->form->fill();
    }
}
