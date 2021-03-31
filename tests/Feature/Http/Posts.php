<?php

namespace Tests\Feature\Http;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\CreatesFile;
use Tests\CreatesPost;
use Tests\CreatesTag;
use Tests\CreatesUser;
use Tests\IncludeAuthorizationHeader;
use Tests\TestCase;

class Posts extends TestCase
{
    use WithFaker, RefreshDatabase, CreatesUser, CreatesPost, CreatesTag, CreatesFile, IncludeAuthorizationHeader;

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
        $expected = $this->makeMultiplePosts();
        $response = $this->json("GET",'/api/posts');
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected)
            {
                $json->where('data', $expected);
            }
        );
    }

    /**
     * GET /api/posts/{id} (get_by_id) - success condition without tags and main image
     *
     * @return void
     */
    public function test_get_by_id()
    {
        $user = $this->makeSingleUser();
        $expected = $this->makeSinglePostWithUser($user);
        $response = $this->withAuthorizationHeaderByUser($user)->json("GET",'/api/posts/' . $expected->id);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected, $user)
            {
                $json->where('data.id', $expected->id)
                    ->where('data.title', $expected->title)
                    ->where('data.body', $expected->body)
                    ->has('data.main_image', null)
                    ->where('data.owner.id', $expected->owner()->id)
                    ->where('data.owner.email', $expected->owner()->email)
                    ->where('data.owner.name', $expected->owner()->name)
                    ->where('data.tags', []);
            }
        );
    }

    /**
     * GET /api/posts/{id} (get_by_id) - success condition with tags
     *
     * @return void
     */
    public function test_get_by_id_with_tags()
    {
        $user = $this->makeSingleUser();
        $expected = $this->makeSinglePostWithUser($user);
        $tags = [
            $this->makeSingleTagWithRelationships($user, $expected),
            $this->makeSingleTagWithRelationships($user, $expected),
        ];
        $response = $this->withAuthorizationHeaderByUser($user)->json("GET",'/api/posts/' . $expected->id);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected, $user, $tags)
            {
                $json->where('data.id', $expected->id)
                    ->where('data.title', $expected->title)
                    ->where('data.body', $expected->body)
                    ->has('data.main_image', null)
                    ->where('data.owner.id', $expected->owner()->id)
                    ->where('data.owner.email', $expected->owner()->email)
                    ->where('data.owner.name', $expected->owner()->name)
                    ->where('data.tags', [['id' => $tags[0]->id, 'name' => $tags[0]->name], ['id' => $tags[1]->id, 'name' => $tags[1]->name]]);
            }
        );
    }

    /**
     * GET /api/posts/{id} (get_by_id) - success condition with main image
     *
     * @return void
     */
    public function test_get_by_id_with_main_image()
    {
        $user = $this->makeSingleUser();
        $expected = $this->makeSinglePostWithUser($user);
        $file = $this->makeSingleFileWithPost($expected);
        $response = $this->withAuthorizationHeaderByUser($user)->json("GET",'/api/posts/' . $expected->id);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected, $user, $file)
            {
                $json->where('data.id', $expected->id)
                    ->where('data.title', $expected->title)
                    ->where('data.body', $expected->body)
                    ->has('data.main_image', ['id' => $file->id, 'url' => $file->url])
                    ->where('data.owner.id', $expected->owner()->id)
                    ->where('data.owner.email', $expected->owner()->email)
                    ->where('data.owner.name', $expected->owner()->name)
                    ->where('data.tags', []);
            }
        );
    }

    /**
     * POST /api/posts/{id} (update) - success condition
     *
     * @return void
     */
    public function test_update()
    {
        $user = $this->makeSingleUser();
        $post = $this->makeSinglePostWithUser($user);
        $expected = Post::factory()->definition();
        $response = $this->withAuthorizationHeaderByUser($user)->json("POST", '/api/posts/' . $post->id, $expected);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected, $post)
            {
                $json->where('data.title', $expected->title)
                    ->where('data.body', $expected->body)
                    ->etc();
            }
        );
    }

    /**
     * POST /api/posts/{id} (update) - failure condition (no authorization header)
     *
     * @return void
     */
    public function test_update_not_authorized_with_no_authorization_header()
    {
        $user = $this->makeSingleUser();
        $post = $this->makeSinglePostWithUser($user);
        $response = $this->json("POST", '/api/posts/' . $post->id, Post::factory()->definition());
        $response->assertForbidden();
    }

    /**
     * POST /api/posts/{id} (update) - failure condition (invalid jwt token)
     *
     * @return void
     */
    public function test_update_not_authorized_with_invalid_jwt_token()
    {
        $user = $this->makeSingleUser();
        $post = $this->makeSinglePostWithUser($user);
        $response = $this->withInvalidAuthorizationHeader()
            ->json("POST", '/api/posts/' . $post->id, Post::factory()->definition());
        $response->assertForbidden();
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
