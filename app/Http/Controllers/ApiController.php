<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiClient;

class ApiController extends Controller
{
    public function index(Request $request) {
        $client = new ApiClient();

        $episodes = $client->getEpisodes();

        if (empty($episodes)) {
            return [];
        }

        return collect($episodes)->map(function($episode) {
            return [
                "id" => $episode->id,
                "name" => $episode->name,
                "comments" => Comment::forEpisode($episode->id)
            ];
        });

        dd($episodes);

        return response($episodes);
    }
}
