<?php

namespace Tests\Feature;

use App\Enums\CompanyStatus;
use Illuminate\Http\UploadedFile;
use Tests\Fixtures\BusinessNatureFixture;
use Tests\Fixtures\CompanyFixture;
use Tests\Fixtures\OtpFixture;
use Tests\Fixtures\UserFixture;

/**
 * Class RegistrationControllerTest
 */
class RegistrationControllerTest extends ApiBaseTestCase
{
    public function testUserCanRegister()
    {
        $endPoint = $this->getRoute('auth.company.register');
        /** @var \App\Models\OTPNotification $otpModel */
        $otpModel = OtpFixture::create();
        $credentials = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'company_name' => $this->faker->company(),
            'country_code' => $this->faker()->countryCode,
            'business_nature' => [1],
            'position' => $this->faker->title,
            'g-recaptcha-response' => 'dxdx',
            'otp' => [
                'code' => (string) $otpModel->code, 'ref' => $otpModel->id,
            ],
        ];
        $credentials['password'] = $credentials['password_confirmation'] = '12345678Aa$';

        $response = $this->initRequestHeadersWithoutUser()->postJson($endPoint, $credentials);
        $response->assertOk();
    }

    public function testUserCanCompleteRegister()
    {
        $user = UserFixture::createUser()->first();
        $company = CompanyFixture::createAndAssignUser($user, CompanyStatus::PENDING_PROFILE_COMPLETION->value);

        $endPoint = $this->getRoute('companies.complete', ['company' => $company->id]);

        $data = [
            'name' => $this->faker->name(),
            'secondary_name' => $this->faker->name(),
            'website_url' => $this->faker->url(),
            'description' => $this->faker()->text(),
            'secondary_description' => $this->faker()->text(),
            'year_founded' => $this->faker()->year(),
            'address' => $this->faker()->address(),
            'country_code' => 'eg',
            'city' => '3223',
            'logo' => UploadedFile::fake()->create($this->faker->name().'.png'),
            'business_nature' => BusinessNatureFixture::create(2)->pluck('id')->toArray(),
            'sell_product_categories' => [1, 2],
            'sell_regions' => [1, 2],
            'buy_product_categories' => [3, 4],
            'buy_regions' => [3, 4],
            'crn' => '05025-15151515a',
            'commercial_registration_document' => UploadedFile::fake()->create('document.pdf', 500, 'application/pdf'),
            'certificates' => [
                [
                    'body' => $this->faker->text(),
                    'expiry' => $this->faker->date('Y-m-d'),
                    'certificate-file' => UploadedFile::fake()->create($this->faker->name().'.png'),
                ],
                [
                    'body' => $this->faker->text(),
                    'expiry' => $this->faker->date('Y-m-d'),
                    'certificate-file' => UploadedFile::fake()->create($this->faker->name().'.png'),
                ],
            ],
        ];

        $response = $this->initRequestHeaders($user)->postJson($endPoint, $data);
        $response->assertOk();
    }
}
