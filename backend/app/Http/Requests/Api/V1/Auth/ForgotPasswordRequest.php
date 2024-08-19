<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Post reset Params
     *
     * @throws \Exception
     */
    public function PostSendResetLinkForgotPasswordRules(): array
    {
        return [
            'email' => ['required', 'string', 'max:255', Rule::exists(User::class, 'email')],
            'g-recaptcha-response' => ['required', 'custom_captcha'],
        ];
    }

    /**
     * Post reset Params
     *
     * @throws \Exception
     */
    public function PostResetPasswordRules(): array
    {
        return [
            'token' => 'bail|required',
            'email' => 'bail|required|email',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->symbols(),
            ],
            'g-recaptcha-response' => ['required', 'custom_captcha'],
        ];
    }
}
