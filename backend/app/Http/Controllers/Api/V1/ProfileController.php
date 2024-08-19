<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ProfileRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\UserService;
use Illuminate\Support\Arr;

class ProfileController extends Controller
{
    public function __construct(protected UserService $service) {}

    /**
     * @return mixed
     *
     * @throws \Throwable
     */
    public function update(ProfileRequest $request)
    {
        $data = $request->validated();
        $user = $this->service->updateResource($data, auth()->user());

        return response()->api(new UserResource($user));
    }

    /**
     * @return false|\Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function changePassword(ProfileRequest $request)
    {
        $data = Arr::only($request->validated(), ['old_password', 'password']);
        $user = $this->service->changePassword($data, auth()->user());

        return response()->api(new UserResource($user));
    }
}
