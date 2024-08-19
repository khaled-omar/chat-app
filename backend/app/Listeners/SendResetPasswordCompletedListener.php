<?php

namespace App\Listeners;

use App\Mail\ResetPasswordCompletedEmail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordCompletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        dispatch(fn () => Mail::to($event->user->email)->send(new ResetPasswordCompletedEmail($event->user->name)))->afterResponse();
    }
}
