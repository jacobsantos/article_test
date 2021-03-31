<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Users extends TestCase
{
    /**
     * GET /api/users (get_all)
     *
     * @return void
     */
    public function test_get_all()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    /**
     * GET /api/users/{id} (get_by_id)
     *
     * @return void
     */
    public function test_get_by_id()
    {
        $response = $this->get('/api/users/1');

        $response->assertStatus(200);
    }

    /**
     * POST /api/users/{id} (update)
     *
     * @return void
     */
    public function test_update()
    {
        $response = $this->get('/api/users/1');

        $response->assertStatus(200);
    }

    /**
     * POST /api/users (create)
     *
     * @return void
     */
    public function test_create()
    {
        $response = $this->post('/api/users');

        $response->assertStatus(200);
    }
}
