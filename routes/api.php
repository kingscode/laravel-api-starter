<?php

use Illuminate\Routing\Router;

/** @var $router Illuminate\Routing\Router */

// @formatter:off

$router->namespace('Api')->prefix('api')->group(function (Router $router) {
    $router->namespace('Password')->prefix('password')->name('password.')->group(function (Router $router) {
        $router->post('reset', 'Reset')->name('reset');
        $router->post('forgotten', 'Forgotten')->name('reset');
    });

    $router->namespace('Invitation')->prefix('invitation')->name('invitation.')->group(function (Router $router) {
        $router->post('accept', 'Accept')->name('accept');
    });

    $router->middleware('auth:api')->group(function (Router $router) {
        $router->namespace('Profile')->prefix('profile')->name('profile.')->group(function (Router $router) {
            $router->namespace('Password')->prefix('password')->name('password.')->group(function (Router $router) {
                $router->put('', 'Update')->name('update');
            });

            $router->put('', 'Update')->name('update');
            $router->get('', 'Show')->name('show');
        });
    });

    $router->post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->name('passport.token');
});

// @formatter:on
