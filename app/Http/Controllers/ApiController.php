<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiClient;
use App\Comment;
use App\ResponseTransformer;
use Carbon\Carbon;

class ApiController extends Controller
{
    use ResponseTransformer;

    public function index(Request $request) {
        $page = $request->get("page", 1);
        $client = new ApiClient();
        $result = $client->getEpisodes($page);
        $episodes = collect($result->results)->map(function($episode) {
            $no_comments = Comment::forEpisode($episode->id)->count();
            return $this->mapEpisodeWithComments($episode, $no_comments);
        })->sortBy(function($episode) {
            return strtotime($episode["release_date"]);
        })->toArray();

        $url_template = route('episodes');
        $transformed = $this->transformPagination($result, $episodes, $url_template, $page);

        return response($transformed);
    }

    function getComments($episode_id) {
        $comments = Comment::forEpisode($episode_id)
            ->select(["ip_address", "message", "created_at"])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $comments->setCollection(
            $comments->getCollection()->map(function($entry) {
                return [
                    "comment" => $entry->message,
                    "ip_address" => $entry->ip_address,
                    "time_created" => $entry->created_at
                ];
            })
        );

        return response($comments);
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

    function getCharacters(Request $request, $episode_id) {
        $sort_by = $request->get("sortBy", null);
        $order = $request->get("order", "asc");
        $gender = $request->get("gender", null);
        $status = $request->get("status", null);

        if ($gender && $status) {
            return response([
                "error" => "Only one filter can be set at once"
            ]);
        }

        $filter_type = $gender ? "gender": "status";
        $filter_value = $request->get($filter_type);

        $filter = [
            "type" => $filter_type,
            "value" => $filter_value
        ];

        $client = new ApiClient();
        $result = collect($client->getCharactersInEpisode($episode_id))
            ->map(function($character){
                return [
                    "name" => $character->name,
                    "status" => $character->status,
                    "species" => $character->species,
                    "type" => $character->type,
                    "gender" => $character->gender,
                    "origin" => $character->origin,
                    "location" => $character->location,
                    "image" => $character->image,
                    "url" => $character->url,
                    "created" => $character->created
                ];
            });
            
        $sorted = $this->sortCharacters($result, $sort_by, $order);

        if ($filter_value) {
            $sorted = $sorted->filter(function($character) use ($filter_type, $filter_value) {
                return strtolower($character[$filter_type]) == strtolower($filter_value);
            });
        }
        
        return response($sorted);
    }

    function sortCharacters($characters, $by=null, $order="asc") {
        if (!$by) {
            return $characters;
        }

        if ($order === "asc") {
            $sorted = $characters->sortBy(function($character) use ($by) {
                return $character[$by];
            });
        } else {
            $sorted = $characters->sortByDesc(function($character) use ($by) {
                return $character[$by];
            });
        }

        return $sorted;
    }
}
