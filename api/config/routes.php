<?php

declare(strict_types=1);

use App\Http\Action;
use App\Router\StaticRouteGroup as Group;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/', Action\Home::class);

    $app->map(['GET', 'POST'], '/authorize', Action\Authorize::class);
    $app->get('/oauth/{provider}', Action\OAuth::class);
    $app->post('/token', Action\Token::class);

    $app->group('/v1', new Group(static function (RouteCollectorProxy $group): void {
        $group->group('/auth', new Group(static function (RouteCollectorProxy $group): void {
            $group->post('/join', Action\V1\Auth\Join\Request::class);
            $group->post('/join/confirm', Action\V1\Auth\Join\Confirm::class);

            $group->get('/user', Action\V1\Auth\User::class);
        }));

        // module finance:
        $group->group('/finance', new Group(static function (RouteCollectorProxy $group): void {
            $group->post('/wallet', Action\V1\Finance\Wallet\CreateAction::class);
            $group->get('/wallets', Action\V1\Finance\Wallet\ListAction::class);
            $group->get('/transactions', Action\V1\Finance\Transaction\ListAction::class);
            $group->post('/transactions', Action\V1\Finance\Transaction\CreateAction::class);
            $group->get('/categories', Action\V1\Finance\Category\ListAction::class);
            $group->post('/categories', Action\V1\Finance\Category\CreateAction::class);
        }));
    }));
};
