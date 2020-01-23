<?php

use Illuminate\Routing\Router;

/** @var $router Illuminate\Routing\Router */

// @formatter:off

$router->namespace('Api')->prefix('api')->group(function (Router $router) {
    $router->namespace('Auth')->prefix('auth')->middleware('throttle:5,15,spa_login_lock')->group(function (Router $router) {
        $router->post('login', 'Login');
        $router->post('dispense', 'Dispense');
    });

    $router->namespace('Password')->prefix('password')->group(function (Router $router) {
        $router->post('reset', 'Reset');
        $router->post('forgotten', 'Forgotten');
    });

    $router->namespace('Invitation')->prefix('invitation')->group(function (Router $router) {
        $router->post('accept', 'Accept');
    });

    $router->middleware('auth:api')->group(function (Router $router) {
        $router->namespace('Profile')->prefix('profile')->group(function (Router $router) {
            $router->namespace('Password')->prefix('password')->group(function (Router $router) {
                $router->put('', 'Update');
            });

            $router->put('', 'Update');
            $router->get('', 'Show');
        });

        $router->namespace('User')->prefix('user')->group(function (Router $router) {
            $router->delete('{user}', 'Destroy');
            $router->put('{user}', 'Update');
            $router->get('{user}', 'Show');
            $router->post('', 'Store');
            $router->get('', 'Index');
        });
    });
});

// @formatter:on
