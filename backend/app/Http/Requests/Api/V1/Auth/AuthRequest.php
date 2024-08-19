<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class AuthRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function postLoginRules()
    {
        return [
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'g-recaptcha-response' => ['required', 'custom_captcha'],
        ];
    }

    public function postRefreshTokenRules()
    {
        return [
            'refresh_token' => 'required|string|min:200',
        ];
    }
}
