<?php

namespace App;

class ApiClient {
    const RATE_LIMIT = 10000;

    /**
     * Url for accessing the Rick and Morty API
     *
     * @var string
     */
    protected $api_url;

    public function __construct() {
        /**
         * The API URL is bound to change, so let's load it from .env and fall to the one provided
         * if not configured
         */
        $this->api_url = env('RICK_AND_MORTY_API_URL', 'https://rickandmortyapi.com');
    }
}
