<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

/**
 * Class ApiBaseTestCase
 */
class ApiBaseTestCase extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $url;

    protected $prefixRouteName = 'api';

    /**
     * Supported app localizations
     *
     * @var array
     */
    protected $supportedLocale = ['en', 'ar'];

    protected function shouldSeed()
    {
        return true;
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->url = config('testing.base_url');
        $this->client = $this->initPassportClient();
    }

    /**
     * Get service url by route name.
     *
     * @param  array  $params
     * @return string
     */
    protected function getRoute(string $routeName, $params = [])
    {
        return route($this->prefixRouteName.'.'.$routeName, $params);
    }

    /**
     * Return api request headers.
     *
     * @return array
     */
    protected function getRequestHeaders()
    {
        return [
            'Accept-Language' => 'ar',
            'client-id' => Client::query()->firstOrFail()->id,
            'client-secret' => Client::query()->firstOrFail()->secret,
        ];
    }

    /**
     * Bind the locale header to request headers
     *
     * @return \Tests\Feature\ApiBaseTestCase
     */
    protected function withLocale($locale)
    {
        return $this->withHeader('Accept-Language', $locale);
    }

    /**
     * Initialize request with predefined headers.
     *
     * @param  \App\Models\User|null  $user
     * @return \Tests\Feature\ApiBaseTestCase
     */
    public function initRequestHeaders(User $user)
    {
        return $this->actingAs($user)->withHeaders($this->getRequestHeaders());
    }

    /**
     * Initialize request with predefined headers.
     *
     * @return \Tests\Feature\ApiBaseTestCase
     */
    public function initRequestHeadersWithUserRole($role)
    {
        $user = $this->getUser($role);

        return $this->actingAs($user)->withHeaders($this->getRequestHeaders());
    }

    /**
     * Initialize request with predefined headers without user.
     *
     * @return \Tests\Feature\ApiBaseTestCase
     */
    public function initRequestHeadersWithoutUser()
    {
        return $this->withHeaders($this->getRequestHeaders());
    }

    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|mixed
     */
    protected function initPassportClient(string $clientName = 'Halex')
    {
        return (new ClientRepository())->createPasswordGrantClient(null, $clientName, '', 'users');
    }
}
