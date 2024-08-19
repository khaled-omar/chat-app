<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Auth\Traits\Authenticatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest as RequestValidation;
use App\Services\RegistrationService;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RegistrationController
 */
class RegistrationController extends Controller
{
    use Authenticatable;

    /**
     * RegistrationController constructor.
     */
    public function __construct(protected RegistrationService $registrationService) {}

    /**
     * Register new user.
     *
     *
     * @throws \Throwable
     */
    public function register(ServerRequestInterface $requestInterface, RequestValidation $request): mixed
    {
        $data = $request->validated();
        $user = $this->registrationService->handle($data);
        $response = $this->doUserLogin($requestInterface, Arr::only($data, ['email', 'password']));

        return response()->api($response);
    }
}
