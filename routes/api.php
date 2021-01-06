<?php

declare(strict_types=1);

use Illuminate\Routing\Router;

/** @var $router Illuminate\Routing\Router */

// @formatter:off

$router->namespace('Api')->group(function (Router $router) {
    $router->namespace('Auth')->prefix('auth')->middleware('throttle:spa_login_lock')->group(function (Router $router) {
        $router->post('login', 'Login');
        $router->post('dispense', 'Dispense');
    });

    $router->get('docs/{doc?}', 'Docs\Show');

    $router->namespace('Invitation')->prefix('invitation')->middleware('throttle:spa_invitation_lock')->group(function (Router $router) {
        $router->post('resend', 'Resend');
        $router->post('accept', 'Accept');
    });

    $router->namespace('Password')->prefix('password')->middleware('throttle:spa_password_reset_lock')->group(function (Router $router) {
        $router->post('reset', 'Reset');
        $router->post('forgotten', 'Forgotten');
    });

    $router->namespace('Registration')->prefix('registration')->group(function (Router $router) {
        $router->post('', 'Store');
        $router->post('verify', 'Verify');
    });

    $router->middleware('auth:api')->group(function (Router $router) {
        $router->namespace('Auth')->prefix('auth')->group(function (Router $router) {
            $router->post('logout', 'Logout');
        });

        $router->namespace('Profile')->prefix('profile')->group(function (Router $router) {
            $router->namespace('Email')->prefix('email')->group(function (Router $router) {
                $router->post('verify', 'Verify');
                $router->put('', 'Update');
            });

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
