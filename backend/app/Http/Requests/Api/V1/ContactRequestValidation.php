<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\CompanyRoles;
use App\Enums\ContactRequestStatus;
use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class ContactRequestValidation extends ApiFormRequest
{
    protected function GetRules()
    {
        return [
            'role' => ['nullable', 'string', Rule::enum(CompanyRoles::class)->except(CompanyRoles::ADMIN)],
            'status' => ['nullable', 'integer', Rule::enum(ContactRequestStatus::class)],
            'subject' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function PostRules()
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'string', Rule::enum(CompanyRoles::class)->except(CompanyRoles::ADMIN)],
        ];
    }

    protected function PatchUpdateRules(): array
    {
        return [
            'status' => ['required', 'integer', Rule::enum(ContactRequestStatus::class)],
        ];
    }
}
