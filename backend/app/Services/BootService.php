<?php

namespace App\Services;

use App\Enums\AttachmentStatus;
use App\Enums\CategoryType;
use App\Enums\CompanyRoles;
use App\Enums\CompanyStatus;
use App\Enums\CompanyUserStatus;
use App\Enums\ContactRequestStatus;
use App\Enums\FacilityStatus;
use App\Enums\FacilityType;
use App\Enums\InvitationStatus;
use App\Enums\LookupType;
use App\Enums\ProductStatus;
use App\Enums\UserTitle;

class BootService extends AbstractService
{
    protected array $enumClasses = [
        'company_statuses' => CompanyStatus::class,
        'company_roles' => CompanyRoles::class,
        'company_user_statuses' => CompanyUserStatus::class,
        'invitation_statuses' => InvitationStatus::class,
        'user_titles' => UserTitle::class,
        'attachment_statuses' => AttachmentStatus::class,
        'category_types' => CategoryType::class,
        'product_statuses' => ProductStatus::class,
        'lookup_types' => LookupType::class,
        'facility_statuses' => FacilityStatus::class,
        'facility_types' => FacilityType::class,
        'contact_request_statuses' => ContactRequestStatus::class,
    ];

    public function getAllEnums(): array
    {
        $data = [];
        foreach ($this->enumClasses as $enumName => $enumClass) {
            $constants = $enumClass::cases();
            foreach ($constants as $constant) {
                $data[$enumName][] = [
                    'key' => $constant->name,
                    'value' => $constant->value,
                ];
            }
        }

        return $data;
    }
}
