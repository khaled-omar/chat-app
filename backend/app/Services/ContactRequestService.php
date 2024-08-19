<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Repositories\ContactRequestRepo;

class ContactRequestService extends AbstractService
{
    /**
     * @var ContactRequestRepo
     */
    protected $repo;

    public function __construct(ContactRequestRepo $repo)
    {
        $this->repo = $repo;
    }

    public function retrieveStatistics(array $filters): array
    {
        return [
            'unread' => $this->repo->getTotalUnreadContacts($filters),
        ];
    }

    public function makeResource($requestData = [])
    {
        if ($requestData['to_company_id'] == $requestData['from_company_id']) {
            throw new ApiException(__('Company representatives cannot send contact requests to their own company.'), 422);
        }

        return parent::makeResource($requestData);
    }

    public function markAsRead(ContactRequest $contactRequest, Company $company): void
    {
        if ($contactRequest?->latestMessage?->from_company_id === $company->id) {
            throw new ApiException(__('Company representatives cannot mark their own messages as read.'), 422);
        }
        $this->repo->markAsRead($contactRequest);
    }

    protected function orderBy()
    {
        return [
            'field' => 'read_at',
            'type' => 'asc',
            'relation' => 'latestMessage',
        ];
    }
}
