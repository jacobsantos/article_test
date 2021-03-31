<?php

namespace Tests;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

trait CreatesPost
{

    /**
     * @return Post[]
     */
    protected function makeMultiplePosts(): array
    {
        return Post::factory()->count(3)->create();
    }

    /**
     * @return Post|object
     */
    protected function makeSinglePost(): object
    {
        return Post::factory()->create();
    }

    /**
     * @return Post|object
     */
    protected function makeSinglePostWithUser(Model $user): object
    {
        return Post::factory()->for($user)->create();
    }

    /**
     * @return Post|object
     */
    protected function makeSinglePostWithMainImage(Model $user, Model $file): object
    {
        return Post::factory()->for($user)->for($file)->create();
    }
}
