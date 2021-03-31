<?php

namespace Tests;

use App\Models\User;

trait CreatesUser
{

    /**
     * @return User[]
     */
    protected function makeMultipleUsers(): array
    {
        return User::factory()->count(3)->create();
    }

    /**
     * @param string $token
     * @return User|object
     */
    protected function makeSingleUserWithToken(string $token): object
    {
        return User::factory()->create(['token' => $token]);
    }

    /**
     * @return User|object
     */
    protected function makeSingleUser(): object
    {
        return $this->makeSingleUserWithToken(CreatesJwt::makeRandom());
    }

}
