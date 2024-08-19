<?php

namespace App\Repositories\Traits;

trait CountryRepoFilters
{
    /**
     * Filter country by name
     *
     * @return void
     */
    protected function nameLikeFilter($queryBuilder, string $value)
    {
        $queryBuilder->where('name', 'like', "%{$value}%");
    }
}
