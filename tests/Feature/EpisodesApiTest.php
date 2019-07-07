<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Comment;

class EpisodesApiTest extends TestCase
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

        $this->assertTrue(isset($data->data));
        $this->hasPaginationStructure($data);
    }



    public function test_episodes_are_ordered_by_release_date()
    {
        $this->get('/episodes');

        $content = $this->response->getContent();
        $data = json_decode($content);
        $episodes = $data->data;

        $dates = collect($episodes)->pluck("release_date");
        $merge1 = $dates->implode("-");

        // Order dates in ascending order
        $dates1 = $dates->toArray();
        sort($dates1);
        $merge2 = implode("-", $dates1);

        $this->assertEquals($merge1, $merge2);
    }
}
