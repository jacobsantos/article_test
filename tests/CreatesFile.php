<?php

namespace Tests;

use App\Models\File;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

trait CreatesFile
{

    /**
     * @return File[]
     */
    protected function makeMultipleFiles(): array
    {
        return File::factory()->count(3)->create();
    }

    /**
     * @return File|object
     */
    protected function makeSingleFile(): object
    {
        return File::factory()->create();
    }

    /**
     * @param Model $user
     * @return File|object
     */
    protected function makeSingleFileWithUser(Model $user): object
    {
        return File::factory()
            ->for(Post::factory()->for($user)->create())
            ->create();
    }

    /**
     * @param Post|Model $post
     * @return File|object
     */
    protected function makeSingleFileWithPost(Model $post): object
    {
        return File::factory()->for($post)->create();
    }
}
