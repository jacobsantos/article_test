<?php

namespace Tests\Feature\Http;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesJwt;
use Tests\CreatesPost;
use Tests\CreatesUser;
use Tests\IncludeAuthorizationHeader;
use Tests\TestCase;

class Posts extends TestCase
{
    use WithFaker, RefreshDatabase, CreatesUser, CreatesPost, IncludeAuthorizationHeader;

    public function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    /**
     * GET /api/posts (get_all)
     *
     * @return void
     */
    public function test_get_all()
    {
        $response = $this->get('/api/posts');

        $response->assertStatus(200);
    }

    /**
     * GET /api/posts/{id} (get_by_id)
     *
     * @return void
     */
    public function test_get_by_id()
    {
        $response = $this->get('/api/posts/1');

        $response->assertStatus(200);
    }

    /**
     * POST /api/posts/{id} (update)
     *
     * @return void
     */
    public function test_update()
    {
        $post = $this->makeSinglePost();
        $expected = Post::factory()->definition();
        $response = $this->withAuthorizationHeader()->json("POST", '/api/posts/' . $post->id, $expected);
        $response->assertStatus(200);
    }

    /**
     * POST /api/posts (create)
     *
     * @return void
     */
    public function test_create()
    {
        $response = $this->post('/api/posts');

        $response->assertStatus(200);
    }

    /**
     * DELETE /api/posts (delete)
     *
     * @return void
     */
    public function test_delete()
    {
        $response = $this->delete('/api/posts/1');

        $response->assertStatus(200);
    }
}
