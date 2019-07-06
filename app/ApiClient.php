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

    public function getEpisodes() {
        $endpoint = $this->getUrl("episode");
        $response = $this->client->get($endpoint);

        $response_code = $response->getStatusCode();
        $content_type = $response->getHeaderLine('content-type');
        $response_body = $response->getBody();

        if (preg_match("/application\/json/", "application/json")) {
            return json_decode($response_body, 1);
        }

        return $response_body;
    }
}
