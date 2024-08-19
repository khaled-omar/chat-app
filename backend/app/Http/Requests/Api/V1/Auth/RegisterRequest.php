<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Exceptions\ApiException;
use App\Http\Requests\Api\ApiFormRequest;
use App\Models\Country;
use App\Models\OTPNotification;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function PostRegisterRules(): array
    {
        return [
            'username' => 'required|string|max:255|unique:users,name',
            //'country_code' => ['required', Rule::exists(Country::class, 'iso2')],
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->letters()->symbols(),
            ],
            'otp.ref' => ['bail', 'string', 'uuid', Rule::exists(OTPNotification::class, 'id')],
            'otp.code' => ['bail', 'required', 'string', 'size:6'],
            'g-recaptcha-response' => ['required', 'custom_captcha'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Handle OTP validation errors on separate status code.
        if ($validator->errors()->hasAny(['otp.ref', 'otp.code'])) {
            throw new ApiException(__('messages.invalid_request_data'), 424, $validator->errors()->all());
        }
        parent::failedValidation($validator);
    }
}
