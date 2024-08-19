<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\NotificationChannels;
use App\Enums\OTPModels;
use App\Enums\OTPModules;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\OTPRequest as RequestValidation;
use App\Http\Resources\V1\OTPResource;
use App\Services\Contracts\OTP;

class OTPController extends Controller
{
    public function __construct(protected OTP $service) {}

    public function send(RequestValidation $request)
    {
        $channel = NotificationChannels::from($request->channel);
        $model = OTPModels::from($request->model);

        $otpModel = $this->service->send($channel, $request->address, OTPModules::REGISTRATION, $model);

        return response()->api(new OTPResource($otpModel));
    }
}
