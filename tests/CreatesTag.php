<?php

namespace Tests;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait CreatesTag
{

    /**
     * @return Tag[]
     */
    protected function makeMultipleTags(): array
    {
        return Tag::factory()->count(3)->create();
    }

    /**
     * @return Tag|object
     */
    protected function makeSingleTag(): object
    {
        return Tag::factory()->create();
    }

    /**
     * @param User|Model $user
     * @return Tag|object
     */
    protected function makeSingleTagWithUser(Model $user): object
    {
        return Tag::factory()->for($user)->create();
    }

    /**
     * @param User|Model $user
     * @param Post|Model $post
     * @return Tag|object
     */
    protected function makeSingleTagWithRelationships(Model $user, Model $post): object
    {
        return Tag::factory()->for($user)->hasAttached($post)->create();
    }
}
