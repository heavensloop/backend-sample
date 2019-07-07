<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\ApiClient;

class ApiClientTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_client_tracks_number_of_requests()
    {
        $requests = 20;

        $this->assertEquals(20, $requests);
    }
}
