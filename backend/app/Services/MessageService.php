<?php

namespace App\Services;

use App\Repositories\CityRepo;
use App\Repositories\MessageRepo;

class MessageService extends AbstractService
{
    /**
     * @var CityRepo
     */
    protected $repo;

    public function __construct(MessageRepo $repo)
    {
        $this->repo = $repo;
    }

    protected function orderBy()
    {
        return [
            'field' => 'id',
            'type' => 'asc',
        ];
    }
}
