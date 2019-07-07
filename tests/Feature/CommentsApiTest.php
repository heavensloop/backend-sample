<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Comment;

class CommentsApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_add_new_comment()
    {
        $comment = factory(Comment::class)->make();

        $this->post(route("add-comment", ["episode" => $comment->episode_id]), [
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

        $this->post(route("add-comment", ["episode" => $comment->episode_id]), [
            "message" => $comment->message
        ]);

        $this->assertEquals(413, $this->response->getStatusCode());

        $content = $this->response->getContent();
        $data = json_decode($content);

        // Check the json structure for status and message..
        $this->assertTrue(isset($data->status));
        $this->assertTrue(isset($data->message));
    }

    function test_episode_comment_list() {
        // Add some comments to an episode..
        $episode_id = 22;
        $number_of_comments = 30;
        factory(Comment::class, $number_of_comments)->create([
            "episode_id" => $episode_id
        ]);

        // Hit the comments api..
        $this->get(route("list-comments", ["episode" => $episode_id]));
        $content = $this->response->getContent();
        $data = json_decode($content);

        $this->assertEquals($number_of_comments, $data->total);

        $this->assertTrue($this->hasPaginationStructure($data));
    }
}
