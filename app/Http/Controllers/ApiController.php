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
}
