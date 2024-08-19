<?php

namespace App\Services\Mocked;

use App\Enums\NotificationChannels;
use App\Enums\OTPModels;
use App\Enums\OTPModules;
use App\Models\OTPNotification;
use App\Repositories\OTPNotificationRepo;
use App\Services\Contracts\OTP;
use Illuminate\Database\Eloquent\Model;
use Random\RandomException;

class OTPMockedService implements OTP
{
    protected int $expireInMin;

    /**
     * OTPService constructor.
     */
    public function __construct(protected OTPNotificationRepo $repo)
    {
        $this->expireInMin = config('otp.otp_expire_in_min');
    }

    /**
     * Send OTP
     *
     * @return OTPNotification $otpModel
     *
     * @throws RandomException
     */
    public function send(NotificationChannels $channel, string $address, OTPModules $module, Model|OTPModels $notifiable): OTPNotification
    {
        $otpCode = random_int(100000, 999999);
        $data = [
            'notifiable_type' => ($notifiable instanceof Model) ? get_class($notifiable) : $notifiable->value,
            'notifiable_id' => ($notifiable instanceof Model) ? $notifiable->id : null,
            'code' => $otpCode,
            'channel' => $channel->value,
            'module' => $module->value,
            'address' => $address,
        ];

        // remove old otp match the new one
        $this->repo->purgeOldRequests($notifiable, $channel->value, $address, $module->value);

        /** @var OTPNotification $otpModel */
        $otpModel = $this->repo->create($data);

        if ($channel === NotificationChannels::SMS) {
            $this->smsOtp($address, $otpCode);

        } elseif ($channel === NotificationChannels::EMAIL) {
            $this->emailOtp($address, $otpCode);
        }

        return $otpModel;
    }

    /**
     * Send OTP SMS code
     */
    protected function smsOtp(string $address, string $code)
    {
        //        $smsService = resolve(SendSmsService::class);
        //        $message = __('Please use the following OTP :code', ['code' => $code]);
        //        $smsService->send($address, $message);
    }

    /**
     * Send OTP email
     */
    protected function emailOtp(string $address, string $code)
    {
        // Mail::to($address)->send(new OtpVerificationCodeEmail($code));
    }

    /**
     * send OTP
     */
    public function verify($refId, string $code, OTPModules $module, NotificationChannels $channel, Model|OTPModels $notifiable, string $address): bool
    {
        return true;
    }

    /**
     * delete OTP
     *
     * @throws \Exception
     */
    public function flush($refId, string $code, OTPModules $module, Model|OTPModels $notifiable): bool
    {
        return $this->repo->findAll(returnResults: false)
            ->where('id', $refId)
            ->where('code', $code)
            ->where('module', $module->value)
            ->where('notifiable_id', ($notifiable instanceof Model) ? $notifiable->id : null)
            ->where('notifiable_type', ($notifiable instanceof Model) ? get_class($notifiable) : $notifiable->value)
            ->delete();
    }

    public function resend(OTPNotification $notification)
    {
        // TODO: Implement resend() method.
    }
}
