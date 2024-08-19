<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\Fixtures\CompanyFixture;
use Tests\Fixtures\UserFixture;

/**
 * Class AuthControllerTest
 */
class AuthControllerTest extends ApiBaseTestCase
{
    public function testUserCanLogin()
    {
        $user = UserFixture::createUser()->first();

        $response = $this->doUserLogin($user);
        $response->assertOk();
    }

    public function testUserCanUseMe()
    {
        $user = UserFixture::createUser()->first();
        CompanyFixture::createAndAssignUser($user);

        $endPoint = $this->getRoute('auth.me');

        $response = $this->initRequestHeaders($user)->getJson($endPoint);
        $response->assertOk();
    }

    public function testUserCanRefresh()
    {
        $user = UserFixture::createUser()->first();

        $loginResponse = $this->doUserLogin($user);
        $token = $loginResponse->json('data.refresh_token');

        $endPoint = $this->getRoute('auth.refresh_token');

        $response = $this->initRequestHeaders($user)->postJson($endPoint, ['refresh_token' => $token]);
        $response->assertOk();
    }

    protected function doUserLogin(User $user): TestResponse
    {
        $endPoint = $this->getRoute('auth.login');

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
            'g-recaptcha-response' => 'dxdx',
        ];

        return $this->initRequestHeadersWithoutUser()->postJson($endPoint, $credentials);
    }
}
