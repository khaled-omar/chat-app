<?php

namespace App\Models\Helpers;

use App\Models\CompanyUser;
use App\Notifications\ResetPasswordNotification;

trait UserHelper
{
    public function hasCompany(?int $companyId = null): bool
    {
        if (is_null($companyId)) {
            return $this->companies()->exists();
        }

        return $this->companies()->where('company_users.company_id', $companyId)->exists();
    }

    public function getCompanyUser(int $companyId): ?CompanyUser
    {
        return CompanyUser::where('company_id', $companyId)->where('user_id', $this->id)->first();
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * get roles of user by company id
     */
    public function defaultCompanyRoles(): mixed
    {
        /** @var CompanyUser $companyUser */
        $companyUser = $this->getCompanyUser($this->defaultCompany()->id)?->load('roles.languages');

        return $companyUser->roles;
    }
}
