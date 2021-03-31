<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\CreatesPost;
use Tests\CreatesTag;
use Tests\CreatesUser;
use Tests\IncludeAuthorizationHeader;
use Tests\TestCase;

class Users extends TestCase
{
    use WithFaker, RefreshDatabase, CreatesUser, CreatesPost, CreatesTag, IncludeAuthorizationHeader;

    public function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    /**
     * GET /api/users (get_all)
     *
     * @return void
     */
    public function test_get_all()
    {
        $expected = $this->makeMultipleUsers();
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
     * GET /api/users/{id} (get_by_id)
     *
     * @return void
     */
    public function test_get_by_id()
    {
        $expected = $this->makeSingleUser();
        $response = $this->json("GET",'/api/users/' . $expected->id);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected)
            {
                $json->where('data.id', $expected->id);
                $json->where('data.email', $expected->email);
                $json->where('data.name', $expected->name);
                $json->missing('data.token');
            }
        );
    }

    /**
     * POST /api/users/{id} (update)
     *
     * @return void
     */
    public function test_update()
    {
        $user = $this->makeSingleUser();
        $expected = User::factory()->definition();
        $response = $this->withAuthorizationHeaderByUser($user)->json("POST",'/api/users/1', $expected);
        $response->assertStatus(200);
    }

    /**
     * POST /api/users (create)
     *
     * @return void
     */
    public function test_create()
    {
        $expected = User::factory()->definition();
        $response = $this->json("POST",'/api/users/1', $expected);
        $response->assertStatus(200);
    }
}
