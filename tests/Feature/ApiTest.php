<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_home_page_returns_version()
    {
        $this->get('/');

        $this->assertEquals(
            env('APP_NAME') . " V" . env('APP_VERSION'), $this->response->getContent()
        );
    }

    public function test_episodes_route_returns_episodes()
    {
        $this->get('/episodes');

        $content = $this->response->getContent();
        $data = json_decode($content);

        $this->assertTrue(isset($data->info));
        $this->assertTrue(isset($data->results));
    }



    public function test_add_new_comment()
    {
        $episode_id = 22;
        $this->post("/episodes/{$episode_id}/add-comment", [
            "message" => "Hello World"
        ]);

        $this->assertEquals(201, $this->response->getStatusCode());

        $content = $this->response->getContent();
        $data = json_decode($content);

        $this->assertTrue(isset($data->status));
        $this->assertTrue(isset($data->message));
    }
}
