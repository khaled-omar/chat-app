<?php

namespace App\Repositories\Traits;

trait UserRepoFilters
{
    protected function usernameFilter($value)
    {
        $this->getQuery()->where('username', 'like', "%{$value}%");
    }

    protected function emailFilter($value)
    {
        $this->getQuery()->where('email', 'like', "%{$value}%");
    }
}
