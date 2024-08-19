<?php

namespace Tests\Fixtures;

use App\Enums\OTPModules;
use App\Models\OTPNotification;
use App\Models\User;

class OtpFixture
{
    /**
     * Create user
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public static function create(): mixed
    {
        $data = [
            'notifiable_type' => 'user',
            'notifiable_id' => null,
            'code' => random_int(100000, 999999),
            'channel' => 'email',
            'module' => OTPModules::REGISTRATION->value,
            'address' => 'mmohammed@arkdev.net',
        ];

        return OTPNotification::create($data);
    }
}
