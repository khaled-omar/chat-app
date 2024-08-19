<?php

namespace Tests\Fixtures;

use App\Models\User;

class UserFixture
{
    /**
     * Create user
     *
     * @param  int  $count
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public static function createUser($count = 1)
    {
        return User::factory()->count($count)->create();
    }
}
