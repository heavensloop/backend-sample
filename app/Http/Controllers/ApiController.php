<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiClient;
use App\Comment;

class ApiController extends Controller
{
    public function index(Request $request) {
        $client = new ApiClient();

        $episodes = $client->getEpisodes();

        if (!isset($episodes) || empty($episodes->results)) {
            return response([]);
        }

        $episodes->results = collect($episodes->results)->map(function($episode) {
            return [
                "name" => $episode->name,
                "episode" => $episode->episode,
                "comments" => Comment::forEpisode($episode->id)->count()
            ];
        })->toArray();

        return response((array) $episodes);
    }

    function getComments($episode_id) {

    }

    function addComment(Request $request, $episode_id) {
        $message = $request->get("message", "");
        $ip_address = $request->ip();

        // Add the comment..
        try {
            $comment = Comment::addNew($ip_address, $episode_id, $message);
        } catch(\ErrorException $ex) {
            return response([
                "status" => "error",
                "message" => $ex->getMessage()
            ], $ex->getCode());
        }

        return response([
            "status" => "success",
            "message" => "The comment was added successfully"
        ], 201);
    }

    function getCharacters($episode_id) {
        $client = new ApiClient();
        $episodes = $client->getCharactersInEpisode($episode_id);

        return $episodes;
    }
}
