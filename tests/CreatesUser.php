<?php

namespace Tests;

use App\Models\User;

trait CreatesUser
{

    /**
     * @param string $token
     * @return User|object
     */
    protected function makeSingleUserWithToken(string $token): object
    {
        return User::factory()->create(['token' => $token]);
    }

    /**
     * @return User[]
     */
    protected function makeMultipleUsers(): array
    {
        return User::factory()->count(3)->create();
    }

    /**
     * @return User|object
     */
    protected function makeSingleUser(): object
    {
        return $this->makeSingleUserWithToken(CreatesJwt::makeRandom());
    }

}
