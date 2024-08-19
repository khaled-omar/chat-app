<?php

namespace App\Notifications;

use App\Mail\ForgotPasswordEmail;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;

class ResetPasswordNotification extends ResetPassword
{
    use Queueable;

    protected User $user;

    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        // @TODO: This should be replaced with the the frontend client URL
        return url(route('api.auth.reset-password', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }

    public function toMail($notifiable)
    {
        return (new ForgotPasswordEmail(
            $notifiable->name,
            $this->resetUrl($notifiable),
            config('auth.passwords.'.config('auth.defaults.passwords').'.expire')
        ))->to($notifiable->email);
    }
}
