<?php

namespace App\Http\Controllers\Api\V1\Auth\Traits;

use App\Exceptions\ApiException;
use Laravel\Passport\Client;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

trait Authenticatable
{
    /**
     * @throws \App\Exceptions\ApiException
     */
    protected function doUserLogin(ServerRequestInterface $request, array $credentials): mixed
    {
        $request = $request->withParsedBody($this->client('password') + [
            'username' => $credentials['email'], 'password' => $credentials['password'],
        ]);

        return $this->issueNewToken($request);
    }

    protected function issueNewToken(ServerRequestInterface $request)
    {
        try {
            $response = app(AccessTokenController::class)->issueToken($request);
        } catch (\Throwable $e) {
            throw new ApiException(__('Invalid Auth Credentials.'), 401);
        }

        return json_decode($response->getContent());
    }

    /**
     * Get client credentials
     */
    protected function client(string $grantType): array
    {
        $request = resolve('request');

        return [
            'client_id' => Client::query()->where('id', $request->header('client-id'))->first()->id,
            'grant_type' => $grantType,
            'client_secret' => $request->header('client-secret'),
            'scope' => '',
        ];
    }
}
