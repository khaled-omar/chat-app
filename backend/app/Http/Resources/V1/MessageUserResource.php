<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\AbstractJsonResource;

class MessageUserResource extends AbstractJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'picture' => getPath($this->picture),
        ];
    }
}
