<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Support\Str;

/**
 * Class CountryControllerTest
 */
class CountryControllerTest extends ApiBaseTestCase
{
    public function testIndex()
    {
        Country::factory()->create();

        $filter = [
            'is_featured' => 1,
            'keyword' => Str::random(1),
        ];
        $endPoint = $this->getRoute('countries.index', $filter);

        $response = $this->initRequestHeadersWithoutUser()->getJson($endPoint);
        $response->assertOk();
    }

    public function testShow()
    {
        $country = Country::factory()->create();
        $endPoint = $this->getRoute('countries.show', ['country' => $country->id]);

        $response = $this->initRequestHeadersWithoutUser()->getJson($endPoint);
        $response->assertOk();
    }
}
