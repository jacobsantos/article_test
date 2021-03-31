<?php

namespace Tests\Feature\Http;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\CreatesPost;
use Tests\CreatesTag;
use Tests\CreatesUser;
use Tests\IncludeAuthorizationHeader;
use Tests\TestCase;

class Tags extends TestCase
{
    use WithFaker, RefreshDatabase, CreatesUser, CreatesPost, CreatesTag, IncludeAuthorizationHeader;

    public function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    /**
     * GET /api/tags (get_all)
     *
     * @return void
     */
    public function test_get_all()
    {
        $expected = $this->makeMultipleTags();
        $response = $this->json("GET",'/api/tags');
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected)
            {
                $json->where('data', $expected);
            }
        );
    }

    /**
     * GET /api/tags/{id} (get_by_id)
     *
     * @return void
     */
    public function test_get_by_id()
    {
        $user = $this->makeSingleUser();
        $expected = $this->makeSingleTagWithUser($user);
        $response = $this->withAuthorizationHeaderByUser($user)->json("GET",'/api/tags/' . $expected->id);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected)
            {
                $json->where('data.id', $expected->id)
                    ->where('data.name', $expected->name)
                    ->missing('data.owner');
            }
        );
    }

    /**
     * POST /api/tags/{id} (update)
     *
     * @return void
     */
    public function test_update()
    {
        $user = $this->makeSingleUser();
        $tag = $this->makeSingleTagWithUser($user);
        $expected = Tag::factory()->definition();
        $response = $this->withAuthorizationHeaderByUser($user)->json("POST",'/api/tags/' . $tag->id, $expected);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected, $tag)
            {
                $json->where('data.id', $tag->id)
                    ->where('data.url', $expected->url)
                    ->missing('data.owner');
            }
        );
    }

    /**
     * POST /api/tags (create)
     *
     * @return void
     */
    public function test_create()
    {
        $expected = Tag::factory()->definition();
        $response = $this->withInvalidAuthorizationHeader()->json("POST",'/api/tags', $expected);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected)
            {
                $json->where('data.id', 1)
                    ->where('data.name', $expected->name)
                    ->missing('data.owner');
            }
        );
    }

    /**
     * DELETE /api/tags/{id} (delete) - success condition
     *
     * @return void
     */
    public function test_delete()
    {
        $user = $this->makeSingleUser();
        $tag = $this->makeSingleTagWithUser($user);
        $response = $this->withAuthorizationHeaderByUser($user)->json("DELETE", '/api/tags/' . $tag->id);
        $response->assertStatus(200);
    }

    /**
     * DELETE /api/tags/{id} (delete) - failure condition (no authorization header)
     *
     * @return void
     */
    public function test_delete_gives_not_authorized()
    {
        $response = $this->json("DELETE", '/api/tags/' . $this->makeSingleTag()->id);
        $response->assertForbidden();
    }

    /**
     * DELETE /api/tags/{id} (delete) - failure condition (invalid jwt token)
     *
     * @return void
     */
    public function test_delete_not_authorized_with_invalid_jwt()
    {
        $tag = $this->makeSingleTag();
        $response = $this->withInvalidAuthorizationHeader()->json("DELETE", '/api/tags/' . $tag->id);
        $response->assertForbidden();
    }

    /**
     * DELETE /api/tags/{id} (delete) - failure condition (invalid owner)
     *
     * @return void
     */
    public function test_delete_not_authorized_with_invalid_owner_jwt()
    {
        $tag = $this->makeSingleTag();
        $response = $this->withAuthorizationHeader()->json("DELETE", '/api/tags/' . $tag->id);
        $response->assertForbidden();
    }
}
