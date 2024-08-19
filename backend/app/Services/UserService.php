<?php

namespace App\Services;

use App\Enums\StoragePaths;
use App\Exceptions\ApiException;
use App\Models\User;
use App\Repositories\UserRepo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserService extends AbstractService
{
    /**
     * User repo
     *
     * @var UserRepo
     */
    protected $repo;

    /**
     * UserService constructor.
     */
    public function __construct(UserRepo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function updateResource($requestData, $item)
    {
        if (Arr::has($requestData, 'picture')) {
            blank($item?->picture) ?: deleteFile($item?->picture);
            $requestData['picture'] = storeFile($requestData['picture'], StoragePaths::USERS->value);
        }

        return parent::updateResource($requestData, $item);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     *
     * @throws \Throwable
     */
    public function changePassword($data, User $user)
    {
        if (! Hash::check($data['old_password'], $user->password)) {
            throw new ApiException(__('Invalid Old Password.'), 422);
        }

        return $this->updateResource(Arr::only($data, 'password'), $user);
    }
}
