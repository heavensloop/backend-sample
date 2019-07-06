<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
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
        $data = json_decode($content, 1);

        $this->assertTrue(array_key_exists("info", $data));
        $this->assertTrue(array_key_exists("results", $data));
    }
}