<?php
namespace App\Filament\Pages\Auth;

use Exception;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Http\Responses\Auth\Contracts\PasswordResetResponse;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Filament\Pages\Auth\PasswordReset\ResetPassword as BaseResetPassword;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Password;


class ResetPassword extends BaseResetPassword
{

    public function resetPassword(): ?PasswordResetResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
            ->title(__('filament-panels::pages/auth/password-reset/reset-password.notifications.throttled.title', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => ceil($exception->secondsUntilAvailable / 60),
            ]))
            ->body(array_key_exists('body', __('filament-panels::pages/auth/password-reset/reset-password.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/password-reset/reset-password.notifications.throttled.body', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => ceil($exception->secondsUntilAvailable / 60),
            ]) : null)
            ->danger()
            ->send();

            return null;
        }

        $data = $this->form->getState();
        $data['email'] = $this->email;
        $data['token'] = $this->token;

        $status = Password::broker(Filament::getAuthPasswordBroker())->reset(
            $data,
            function (CanResetPassword | Model | Authenticatable $user) use ($data) {
                $user->forceFill([
                    'password' => Hash::make($data['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        if ($status === Password::PASSWORD_RESET) {
            Notification::make()
                ->title('La contraseña restablecida correctamente.')
                ->success()
                ->send();

            return app(PasswordResetResponse::class);
        }

        Notification::make()
            ->title('Ocurrió un error al intentar restablecer la contraseña, vuelta a intentarlo.')
            ->danger()
            ->send();

        return null;
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/password-reset/reset-password.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(PasswordRule::default()->symbols()->letters()->mixedCase()->numbers()->uncompromised())
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::pages/auth/password-reset/reset-password.form.password.validation_attribute'))
            ->validationMessages([
                'required' => 'Contraseña es requerida',
                'mixed' => 'Contraseña requiere al menos una letra mayúscula y/o una minúscula',
                'numbers' => 'Contraseña debe contener al menos un número',
                'uncompromised' => 'Contraseña se ha visto comprometida y no se puede usar',
                'symbols' => 'Contraseña debe contener al menos un caracter especial',
                'letters' => 'Contraseña debe contener al menos una letra',
                'same' => 'Las contraseñas no coinciden'
            ]);
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::pages/auth/password-reset/reset-password.form.password_confirmation.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

}