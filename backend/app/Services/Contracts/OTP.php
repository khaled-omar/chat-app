<?php

namespace App\Services\Contracts;

use App\Enums\NotificationChannels;
use App\Enums\OTPModels;
use App\Enums\OTPModules;
use App\Models\OTPNotification;
use Illuminate\Database\Eloquent\Model;

interface OTP
{
    public function send(NotificationChannels $channel, string $address, OTPModules $module, Model|OTPModels $notifiable): OTPNotification;

    public function verify($refId, string $code, OTPModules $module, NotificationChannels $channel, Model|OTPModels $notifiable, string $address): bool;

    public function flush($refId, string $code, OTPModules $module, Model|OTPModels $notifiable): bool;

    public function resend(OTPNotification $notification);
}
