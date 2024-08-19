<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Http\Request;
use Laravel\Passport\Client;

class AuthorizeClient
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $clientName
     * @return mixed
     *
     * @throws \App\Exceptions\ApiException
     */
    public function handle(Request $request, Closure $next)
    {
        $clientId = $request->header('client-id');
        $clientSecret = $request->header('client-secret');

        if (! $this->clientExist($clientId, $clientSecret)) {
            $this->unauthorizedClient();
        }

        return $next($request);
    }

    /**
     * Validate if request client exist
     */
    private function clientExist(?string $clientId = null, ?string $clientKey = null): bool
    {
        if (is_null($clientId) || is_null($clientKey)) {
            return false;
        }

        $clientCount = Client::query()->where('id', $clientId)
            ->where('secret', $clientKey)
            ->where('revoked', 0)
            ->count();

        return (int) $clientCount > 0;
    }

    /**
     * Throw unauthorized client exception
     *
     * @throws \App\Exceptions\ApiException
     */
    private function unauthorizedClient(): void
    {
        throw new ApiException(__('Unauthorized Client.'), 403);
    }
}
