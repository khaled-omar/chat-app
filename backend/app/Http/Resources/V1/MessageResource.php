<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\AbstractJsonResource;

class MessageResource extends AbstractJsonResource
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
            'content' => $this->content,
            'from_user' => new MessageUserResource($this->whenLoaded('fromUser')),
            'from_company_id' => $this->from_company_id,
            'from_company' => new CompanyResource($this->whenLoaded('fromCompany')),
            'to_company_id' => $this->to_company_id,
            'to_company' => new CompanyResource($this->whenLoaded('toCompany')),
            'read_at' => $this->read_at?->date_api(),
            'created_at' => $this->created_at?->date_api(),
            'updated_at' => $this->created_at?->date_api(),

        ];
    }
}
