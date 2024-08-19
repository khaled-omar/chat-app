<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Propaganistas\LaravelPhone\Rules\Phone;

class ProfileRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function PostUpdateRules(): array
    {
        return [
            'picture' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5000'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore($this->user()->id)],
            'name' => ['string', 'required', 'max:100'],
            'mobile' => ['required', (new Phone)->international()->country('EG')],
        ];
    }

    public function PutChangePasswordRules(): array
    {
        return [
            'old_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->letters()->symbols(),
            ],
        ];
    }
}
