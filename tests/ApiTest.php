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
}
