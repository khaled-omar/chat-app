<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ContactRequestValidation as RequestValidation;
use App\Http\Resources\V1\ContactRequestResource as ApiResource;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Services\ContactRequestService;
use Illuminate\Support\Arr;

class ContactRequestController extends Controller
{
    public function __construct(protected ContactRequestService $service) {}

    public function index(RequestValidation $request, Company $company)
    {
        $data = $request->validated();
        $filters = [
            'contact-request' => Arr::except($data, 'role') + ['company_id' => $company->id],
            'roles' => ['name' => Arr::get($data, 'role')],
        ];
        $items = $this->service->retrieveResource($filters, ['roles.languages', 'latestMessage']);

        return response()->api([
            'statistics' => $this->service->retrieveStatistics($filters),
            'items' => ApiResource::collection($items),
        ]);
    }

    public function store(RequestValidation $request, Company $company)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $data = $request->validated();
        $data['to_company_id'] = $company->id;
        $data['from_company_id'] = $user->defaultCompany()->id;
        $data['from_user_id'] = $user->id;
        $item = $this->service->makeResource($data);

        return response()->api(new ApiResource($item?->load(['roles.languages'])));
    }

    public function show(RequestValidation $request, Company $company, ContactRequest $contactRequest)
    {
        $contactRequest = $contactRequest->load(['messages.fromUser']);

        return response()->api(new ApiResource($contactRequest));
    }

    public function update(RequestValidation $request, Company $company, ContactRequest $contactRequest)
    {
        $data = $company->id == $contactRequest->to_company_id ? ['to_status' => $request->status] : ['from_status' => $request->status];
        $item = $this->service->updateResource($data, $contactRequest);

        return response()->api(new ApiResource($item));
    }

    public function markAsRead(RequestValidation $request, Company $company, ContactRequest $contactRequest)
    {
        $this->service->markAsRead($contactRequest, $company);

        return response()->api();
    }

    public function destroy(RequestValidation $request, Company $company, ContactRequest $contactRequest)
    {
        $this->service->deleteOneById($contactRequest->id);

        return response()->api();
    }
}
