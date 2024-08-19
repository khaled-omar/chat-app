<?php

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\Traits\CountryRepoFilters;
use Illuminate\Database\Eloquent\Model;

class CountryRepo extends AbstractEntityRepo
{
    use CountryRepoFilters;

    public function __construct()
    {
        $this->model = new Country();
        parent::__construct();
    }

    /**
     * @return Model|mixed
     */
    protected function createEntity($model, $data)
    {
        return $model;
    }

    protected function updateEntity($entity, $data)
    {
        return $entity;
    }
}
