<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Traits\UserRepoFilters;

/**
 * Class UserRepo
 */
class UserRepo extends AbstractEntityRepo
{
    use UserRepoFilters;

    /**
     * UserRepo constructor.
     */
    public function __construct()
    {
        $this->model = new User();
        parent::__construct();
    }

    /**
     * Create entity relations
     *
     * @param  User  $user
     * @return mixed
     */
    protected function createEntity($user, $data)
    {
        return $user;
    }

    /**
     * Update entity
     *
     * @param  User  $entity
     * @return mixed
     */
    protected function updateEntity($entity, $data)
    {
        return $entity;
    }
}
