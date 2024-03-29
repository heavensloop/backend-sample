<?php

namespace App;

use GuzzleHttp\Client;

class ApiClient {
    const RATE_LIMIT = 10000;

    /**
     * Url for accessing the Rick and Morty API
     *
     * @var string
     */
    protected $api_url;

    /**
     * HTTP Client
     *
     * @var Client
     */
    protected $client;

    public function __construct() {
        /**
         * The API URL is bound to change, so let's load it from .env and fall to the one provided
         * if not configured
         */
        $this->api_url = env('RICK_AND_MORTY_API_URL', 'https://rickandmortyapi.com');

        /**
         * Instance of the client
         */
        $this->client = new Client();
    }

    function getUrl($endpoint="") {
        return rtrim($this->api_url, "/") . "/api/{$endpoint}";
    }

    public function getEpisodes($page) {
        $endpoint = $this->getUrl("episode?page={$page}");
        $response = $this->client->get($endpoint);

        $response_code = $response->getStatusCode();
        $content_type = $response->getHeaderLine('content-type');
        $response_body = $response->getBody();

        if (preg_match("/application\/json/", "application/json")) {
            return json_decode($response_body);
        }

        return $response_body;
    }

    public function getSingleEpisode($episode_id) {
        $endpoint = $this->getUrl("episode/{$episode_id}");
        $response = $this->client->get($endpoint);

        $response_code = $response->getStatusCode();
        $content_type = $response->getHeaderLine('content-type');
        $response_body = $response->getBody();

        if (preg_match("/application\/json/", "application/json")) {
            return json_decode($response_body);
        }

        return $response_body;
    }

    function getCharactersInEpisode($episode_id) {
        $episode = $this->getSingleEpisode($episode_id);
        $character_ids = collect($episode->characters)->map(function($episode_url) {
            $matches = [];
            preg_match_all("/character\/(\d+)/", $episode_url, $matches);
            return $matches[1][0];
        });

        $endpoint = $this->getUrl("character/" . $character_ids->implode(","));
        $response = $this->client->get($endpoint);

        $response_code = $response->getStatusCode();
        $content_type = $response->getHeaderLine('content-type');
        $response_body = $response->getBody();

        if (preg_match("/application\/json/", "application/json")) {
            return json_decode($response_body);
        }

        return $response_body;
    }
}
