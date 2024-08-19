<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\AbstractJsonResource;

class ContactRequestResource extends AbstractJsonResource
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
            'subject' => $this->subject,
            'status' => $request->company->id == $this->to_company_id ? $this->to_status : $this->from_status,
            'from_user' => new MessageUserResource($this->whenLoaded('fromUser')),
            'from_company' => new CompanyResource($this->whenLoaded('fromCompany')),
            'to_company' => new CompanyResource($this->whenLoaded('toCompany')),
            'roles' => CompanyRoleResource::collection($this->whenLoaded('roles')),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'latest_message' => new MessageResource($this->whenLoaded('latestMessage')),
        ];
    }
}
