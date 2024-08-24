<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\AbstractJsonResource;
use Illuminate\Http\Request;

class SettingResource extends AbstractJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->key,
            'value' => $this->lang()->value,
        ];
    }
}
