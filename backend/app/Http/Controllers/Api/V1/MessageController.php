<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\MessageValidation as RequestValidation;
use App\Http\Resources\V1\MessageResource as ApiResource;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Services\MessageService;

class MessageController extends Controller
{
    public function __construct(protected MessageService $service) {}

    public function index(RequestValidation $request, Company $company, ContactRequest $contactRequest)
    {
        $filters = [
            'contactRequest' => ['id' => $contactRequest->id],
        ];

        $items = $this->service->retrieveResource($filters, ['fromUser']);

        return response()->api(ApiResource::collection($items));
    }

    public function store(RequestValidation $request, Company $company, ContactRequest $contactRequest)
    {
        $data = $request->validated();
        $data['contact_request_id'] = $contactRequest->id;
        $data['to_company_id'] = $contactRequest->to_company_id == $company->id ? $contactRequest->from_company_id : $contactRequest->to_company_id;
        $data['from_company_id'] = $company->id;
        $data['from_user_id'] = auth()->id();
        $item = $this->service->makeResource($data);

        return response()->api(new ApiResource($item?->load(['fromUser'])));
    }
}
