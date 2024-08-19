<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\NotificationChannels;
use App\Enums\OTPModels;
use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rules\Enum;

class OTPRequest extends ApiFormRequest
{
    protected function PostRules()
    {
        return [
            'channel' => ['bail', 'required', new Enum(NotificationChannels::class)],
            'model' => ['bail', 'required', new Enum(OTPModels::class)],
            'address' => ['bail', 'required', 'email'],
        ];
    }
}
