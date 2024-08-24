<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SettingRequest;
use App\Http\Resources\V1\SettingResource;
use App\Services\SettingService;
use Illuminate\Support\Arr;

class SettingController extends Controller
{
    public function __construct(protected SettingService $service) {}

    /**
     * Get Settings list
     *
     * @return void
     */
    public function index(SettingRequest $request)
    {
        $keys = Arr::get($request->validated(), 'keys');
        $filters = [
            'setting' => ['key' => $keys],
        ];
        $load = ['languages'];

        $setting = $this->service->retrieveResource($filters, $load, false);

        return response()->api(SettingResource::collection($setting));
    }
}
