<?php

namespace App\Services;

use App\Repositories\CountryRepo;

class CountryService extends AbstractService
{
    /**
     * @var CountryRepo
     */
    protected $repo;

    public function __construct(CountryRepo $repo)
    {
        $this->repo = $repo;
    }
}
