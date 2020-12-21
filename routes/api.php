<?php declare(strict_types=1);

/**
 * This file is part of The Resonate Wealth Game, a Bettergist Collective Project.
 *
 * Copyright © 2020 Theodore R. Smith <hopeseekr@gmail.com>
 *   GPG Fingerprint: D8EA 6E4D 5952 159D 7759  2BB4 EEB6 CE72 F441 EC41
 *   https://github.com/BettergistCo/ResonateWealthGame
 *   https://www.resonance.how/wealth-game/
 *
 * This file is licensed under the Creative Commons Attribution v4.0 License.
 */

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\v1\AccountController;
use App\Http\Controllers\v1\ContactController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** @var Router $router */
$router->post('oauth/token', [AuthenticateController::class, 'oauth']);
$router->group(['prefix' => 'auth'], function (Router $api) {
    // All these routes starts with '/auth/'

    $api->post('register', [AuthenticateController::class, 'register']);
    $api->post('login', [AuthenticateController::class, 'login']);

    $api->get('token', [AuthenticateController::class, 'verifyResetToken']);
    $api->post('token/{email}', [AuthenticateController::class, 'requestResetToken']);

    $api->patch('user/{user}', [AuthenticateController::class, 'resetPassword']);

    $api->group(['middleware' => ['jwt.auth']], function (Router $api) {
        $api->get('jwt', [AuthenticateController::class, 'refreshToken']);
        $api->get('logout', [AuthenticateController::class, 'logout']);
    });
});

//$router->group(['prefix' => 'auth', 'middleware' => 'auth:api'], function (Router $api) {
//    $api->post('login', 'AuthenticateController@login')->name('login');
//});

$router->middleware('jwt.auth')->group(function (Router $api) {
    $api->get('/user', function (Request $request) {
        return $request->user();
    });

    $api->get('/expenses',  [ExpensesController::class, 'index']);
    $api->post('/expenses', [ExpensesController::class, 'store']);

    $api->post('/game',       [GameController::class, 'store']);
    $api->get('/games/{id}',  [GameController::class, 'show']);
});
