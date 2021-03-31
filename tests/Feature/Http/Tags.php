<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Tags extends TestCase
{
    /**
     * GET /api/tags (get_all)
     *
     * @return void
     */
    public function test_get_all()
    {
        $response = $this->get('/api/tags');

        $response->assertStatus(200);
    }

    /**
     * GET /api/tags/{id} (get_by_id)
     *
     * @return void
     */
    public function test_get_by_id()
    {
        $response = $this->get('/api/tags/1');

        $response->assertStatus(200);
    }

    /**
     * POST /api/tags/{id} (update)
     *
     * @return void
     */
    public function test_update()
    {
        $response = $this->get('/api/tags/1');

        $response->assertStatus(200);
    }

    /**
     * POST /api/tags (create)
     *
     * @return void
     */
    public function test_create()
    {
        $response = $this->post('/api/tags');

        $response->assertStatus(200);
    }

    /**
     * DELETE /api/tags (delete)
     *
     * @return void
     */
    public function test_delete()
    {
        $response = $this->delete('/api/tags/1');

        $response->assertStatus(200);
    }
}
