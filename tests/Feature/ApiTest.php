<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Comment;

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
        $comment = factory(Comment::class)->make();

        $this->post("/episodes/{$comment->episode_id}/add-comment", [
            "message" => $comment->message
        ]);

        $this->assertEquals(201, $this->response->getStatusCode());

        $content = $this->response->getContent();
        $data = json_decode($content);

        // Check the json structure for status and message..
        $this->assertTrue(isset($data->status));
        $this->assertTrue(isset($data->message));

        // Check that comment is saved in database..
        $this->assertTrue(Comment::whereEpisodeId($comment->episode_id)
            ->whereMessage($comment->message)->exists());
    }

    public function test_get_error_message_for_long_comment()
    {
        $comment = factory(Comment::class)->states('long-comment')->make();

        $this->post("/episodes/{$comment->episode_id}/add-comment", [
            "message" => $comment->message
        ]);

        $this->assertEquals(413, $this->response->getStatusCode());

        $content = $this->response->getContent();
        $data = json_decode($content);

        // Check the json structure for status and message..
        $this->assertTrue(isset($data->status));
        $this->assertTrue(isset($data->message));
    }
}
