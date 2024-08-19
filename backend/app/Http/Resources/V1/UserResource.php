<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\AbstractJsonResource;

class UserResource extends AbstractJsonResource
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
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'picture' => getPath($this->picture),
        ];
    }
}
