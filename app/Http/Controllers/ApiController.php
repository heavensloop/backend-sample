<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiClient;

class ApiController extends Controller
{
    public function index(Request $request) {
        $client = new ApiClient();

        return response($client->getEpisodes());
    }
}
