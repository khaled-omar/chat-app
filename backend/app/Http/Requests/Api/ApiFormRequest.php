<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use Illuminate\Contracts\Validation\Validator;

class ApiFormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation for api response.
     *
     * @throws \App\Exceptions\ApiException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ApiException(__('messages.invalid_request_data'), 422, $validator->errors()->all());
    }

    /**
     * Handle failed authorization api response
     *
     * @throws \App\Exceptions\ApiException
     */
    protected function failedAuthorization()
    {
        throw new ApiException(__('messages.unauthorized'), 403, [__('messages.unauthorized')]);
    }
}
