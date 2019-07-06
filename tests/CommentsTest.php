<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Comment;

class CommentsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_add_comment() {
        $comment = Comment::addNew("100.100.13.1", "Hello world");
        $this->assertInstanceOf(Comment::class, $comment);
    }

    public function test_comments_cant_be_more_than_500_characters() {
        $this->expectException(\ErrorException::class);
        $really_long_message = "Hello world Endpoint should accept sort parameters to sort by one of name, gender or species in  or descending order Endpoint should also accept a filter parameter to filter by gender or status The response should also return metadata that contains the total number of characters that match the criteria Endpoint should also accept a filter parameter to filter by gender or status The response should also return metadata that contains the total number of characters that match the criteria Endpoint should also accept a filter parameter to filter by gender or status The response should also return metadata that contains the total number of characters that match the criteria";

        $comment = Comment::addNew("100.100.13.3", $really_long_message);
    }
}
