<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\SettingKey;
use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends ApiFormRequest
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
    public function GetRules(): array
    {
        return [
            'keys' => ['required', 'array'],
            'keys.*' => ['required', 'string', Rule::enum(SettingKey::class)],
        ];
    }
}
