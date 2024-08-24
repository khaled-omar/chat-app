<?php

namespace App\Services;

use App\Repositories\SettingRepo;

class SettingService extends AbstractService
{
    /**
     * @var SettingRepo
     */
    protected $repo;

    public function __construct(SettingRepo $repo)
    {
        $this->repo = $repo;
    }
}
