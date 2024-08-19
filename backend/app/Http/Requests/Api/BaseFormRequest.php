<?php

namespace App\Http\Requests\Api;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BaseFormRequest extends FormRequest
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
     */
    public function rules(): array
    {
        // Handel generic export resource
        if ($this->route()->getName() == 'exportExcel') {
            return [];
        }
        // Special handling for specific action name
        $method = Str::title($this->method()).Str::studly($this->route()->getActionMethod()).'Rules';

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        $_callback_name = Str::title($this->method()).'Rules';

        if (method_exists($this, $_callback_name)) {
            return $this->{$_callback_name}();
        } else {
            throw new Exception("Method $_callback_name not exist");
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (filled($this->defaultValues)) {
            $data = $this->all();
            foreach ($this->defaultValues as $key => $value) {
                data_set($data, $key, data_get($data, $key, $value));
            }

            $this->merge($data);
        }
    }

    /**
     * Get all filters keys and values of request
     *
     * @return array
     */
    public function getRequestFilters() {}

    /**
     * Get validation rules of post requests
     *
     * @return array
     */
    protected function PostRules()
    {
        return [];
    }

    /**
     * Get validation rules of put(update entity) requests
     *
     * @return array
     */
    protected function PutRules()
    {
        return [];
    }

    /**
     * Get validation rules of patch requests
     *
     * @return array
     */
    protected function PatchRules()
    {
        return [];
    }

    /**
     * Get validation rules of GET requests
     *
     * @return array
     */
    protected function GetRules()
    {
        return [];
    }

    /**
     * Get validation rules of Delete requests
     *
     * @return array
     */
    protected function DeleteRules()
    {
        return [];
    }

    /**
     * Attachment Rules validation
     *
     * @return array
     */
    public function AttachmentRules()
    {
        return [];
    }

    /**
     * Custom Mapping Attributes
     *
     * @return array
     */
    public function customAttributes()
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return collect($this->customAttributes())->map(function ($item, $key) {
            return __("validation.attributes.{$item}");
        })->toArray();
    }
}
