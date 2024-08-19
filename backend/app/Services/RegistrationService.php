<?php

namespace App\Services;

use App\Enums\NotificationChannels;
use App\Enums\OTPModels;
use App\Enums\OTPModules;
use App\Exceptions\ApiException;
use App\Models\User;
use App\Repositories\UserRepo;
use App\Services\Contracts\OTP;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    /**
     * UserRepo constructor.
     */
    public function __construct(protected UserRepo $userRepository, protected OTP $verificationService) {}

    /**
     * @throws \App\Exceptions\ApiException
     */
    public function handle(array $data)
    {
        try {
            DB::beginTransaction();
            // verify OTP
            $this->verifyOtp(Arr::get($data, 'otp.ref'), Arr::get($data, 'otp.code'), Arr::get($data, 'email'));
            // Create new user
            $user = $this->userRepository->create(Arr::only($data,
                (new User())->getFillable()));

            DB::commit();

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    protected function verifyOtp(string $otpRef, string $otpCode, string $address)
    {
        $verified = $this->verificationService->verify($otpRef, $otpCode, OTPModules::REGISTRATION, NotificationChannels::EMAIL, OTPModels::USER, $address);

        if (! $verified) {
            throw new Exception(__('OTP Verification Failed.'), 422);
        }

        $this->verificationService->flush($otpRef, $otpCode, OTPModules::REGISTRATION, OTPModels::USER);
    }
}
