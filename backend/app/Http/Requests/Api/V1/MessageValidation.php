<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\ApiFormRequest;

class MessageValidation extends ApiFormRequest
{
    protected function PostRules()
    {
        return [
            'content' => ['required', 'string'],
        ];
    }
}
