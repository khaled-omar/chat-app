<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * ApiException constructor.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array  $errors
     */
    public function __construct($message, $code = 0, $errors = [])
    {
        $this->errors = $errors;

        parent::__construct($message, $code, null);
    }

    /**
     * Get first error in errors or return empty string
     *
     * @return array|string
     */
    public function getErrors()
    {
        if (empty($this->errors)) {
            return '';
        }

        return $this->errors[0];
    }
}
