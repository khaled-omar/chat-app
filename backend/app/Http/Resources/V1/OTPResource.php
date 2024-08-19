<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\AbstractJsonResource;

class OTPResource extends AbstractJsonResource
{
    public function toArray($request)
    {
        return [
            'ref' => $this->id,
        ];
    }
}
