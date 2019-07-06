<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', "HomeController@index");

/**
 * Route for retrieving list of episodes
 */
$router->get('/episodes', "ApiController@index");

/**
 * Route for adding a comment to an episode
 */
$router->put('/episodes/{episode_id}/add-comment', "ApiController@addComment");
