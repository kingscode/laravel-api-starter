<?php

use Illuminate\Routing\Router;

/** @var $router Illuminate\Routing\Router */

// @formatter:off

$router->namespace('Api')->prefix('api')->group(function (Router $router) {

});

$router->post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->name('passport.token');

// @formatter:on
