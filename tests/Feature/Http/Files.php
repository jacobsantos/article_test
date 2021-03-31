<?php

namespace Tests\Feature\Http;

use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\CreatesFile;
use Tests\CreatesPost;
use Tests\CreatesUser;
use Tests\IncludeAuthorizationHeader;
use Tests\TestCase;

class Files extends TestCase
{
    use WithFaker, RefreshDatabase, CreatesUser, CreatesFile, CreatesPost, IncludeAuthorizationHeader;

    public function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    /**
     * GET /api/files (get_all)
     *
     * @return void
     */
    public function test_get_all()
    {
        $files = $this->makeMultipleFiles();
        $response = $this->json("GET",'/api/files');
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($files)
            {
                $json->where('data', $files);
            }
        );
    }

    /**
     * GET /api/files/{id} (get_by_id)
     *
     * @return void
     */
    public function test_get_by_id()
    {
        $file = $this->makeSingleFile();
        $response = $this->json("GET",'/api/files/' . $file->id);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($file)
            {
                $json->where('data', $file);
            }
        );
    }

    /**
     * POST /api/files (create) - success condition
     *
     * @return void
     */
    public function test_create()
    {
        $expected = File::factory()->definition();
        $response = $this->withAuthorizationHeader()->json("POST", '/api/files', $expected);
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) use ($expected)
            {
                $json->has('data.id');
                $json->where('data.id', 1);
                $json->where('data.url', $expected->url);
            }
        );
    }

    /**
     * POST /api/files (create) - failure condition (no authorization header)
     *
     * @return void
     */
    public function test_create_not_authorized_with_no_authorization_header()
    {
        $response = $this->json("POST", '/api/files', File::factory()->definition());
        $response->assertForbidden();
    }

    /**
     * POST /api/files (create) - failure condition (invalid jwt token)
     *
     * @return void
     */
    public function test_create_not_authorized_with_invalid_jwt()
    {
        $response = $this->withInvalidAuthorizationHeader()->json("POST", '/api/files', File::factory()->definition());
        $response->assertForbidden();
    }

    /**
     * DELETE /api/files (delete) - success condition
     *
     * @return void
     */
    public function test_delete()
    {
        $user = $this->makeSingleUser();
        $file = $this->makeSingleFileWithRelationships($user);
        $response = $this->withAuthorizationHeaderByUser($user)->json("DELETE", '/api/files/' . $file->id);
        $response->assertStatus(200);
    }

    /**
     * DELETE /api/files (delete) - failure condition (no authorization header)
     *
     * @return void
     */
    public function test_delete_gives_not_authorized()
    {
        $response = $this->json("DELETE", '/api/files/' . $this->makeSingleFile()->id);
        $response->assertForbidden();
    }

    /**
     * DELETE /api/files (delete) - failure condition (invalid jwt token)
     *
     * @return void
     */
    public function test_delete_not_authorized_with_invalid_jwt()
    {
        $file = $this->makeSingleFile();
        $response = $this->withInvalidAuthorizationHeader()->json("DELETE", '/api/files/' . $file->id);
        $response->assertForbidden();
    }

    /**
     * DELETE /api/files (delete) - failure condition (invalid owner)
     *
     * @return void
     */
    public function test_delete_not_authorized_with_invalid_owner_jwt()
    {
        $file = $this->makeSingleFile();
        $response = $this->withAuthorizationHeader()->json("DELETE", '/api/files/' . $file->id);
        $response->assertForbidden();
    }
}
