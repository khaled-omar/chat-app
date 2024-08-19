<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CountryRequest;
use App\Http\Resources\V1\CountryResource;
use App\Models\Country;
use App\Services\CountryService;
use Illuminate\Support\Arr;

class CountryController extends Controller
{
    public function __construct(protected CountryService $service) {}

    /**
     * Get Countries list
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function index(CountryRequest $request)
    {
        $data = $request->validated();
        $filter = ['country' => ['enabled' => true]];
        if ($isFeatured = Arr::get($data, 'is_featured')) {
            $filter['country']['featured'] = $isFeatured;
        }
        if ($keyword = Arr::get($data, 'keyword')) {
            $filter['languages']['nameLike'] = $keyword;
        }

        $countries = $this->service->retrieveResource($filter, 'languages');

        return response()->api(CountryResource::collection($countries));
    }

    /**
     * get country by Id with attachments ordered by weight
     *
     * @return mixed
     */
    public function show(Country $country)
    {
        $country->enabled ?: abort(404);
        $country->load('languages', 'attachments.languages');

        return response()->api(new CountryResource($country));
    }
}
