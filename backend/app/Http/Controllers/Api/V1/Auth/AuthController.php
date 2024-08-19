<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Auth\Traits\Authenticatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\AuthRequest as RequestValidation;
use App\Http\Resources\V1\UserResource;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AuthController
 */
class AuthController extends Controller
{
    use Authenticatable;

    /**
     * @throws \App\Exceptions\ApiException
     */
    public function login(ServerRequestInterface $request, RequestValidation $requestValidation)
    {
        $response = $this->doUserLogin($request, $requestValidation->only('email', 'password'));

        return response()->api($response);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->api(null);
    }

    public function me()
    {
        $user = auth()->user();

        return response()->api(new UserResource($user));
    }

    public function refreshToken(ServerRequestInterface $request, RequestValidation $requestValidation)
    {
        $requestData = array_merge($this->client('refresh_token'), [
            'refresh_token' => $requestValidation->input('refresh_token'),
        ]);
        $request = $request->withParsedBody($requestData);
        $response = $this->issueNewToken($request);

        return response()->api($response);
    }
}
