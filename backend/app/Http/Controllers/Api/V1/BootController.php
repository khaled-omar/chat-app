<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\BootService;

class BootController extends Controller
{
    public function __construct(protected BootService $service) {}

    /**
     * Get Enums list
     *
     * @return void
     */
    public function index()
    {
        $enums = $this->service->getAllEnums();

        return response()->api(collect($enums));
    }
}
