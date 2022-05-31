<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\Admin\AdminScheduleController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;

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

/** Just for testing */
Route::get('test', function () {
    dd('Test V1');
});

/** Auth routes */
Route::group(['prefix' => 'auth'], function (Router $router) {
    /** Register */
    $router->post('register', 'Auth\RegisterController');

    /** Login */
    $router->post('login', 'Auth\LoginController');
});

/** Authenticated routes group */
Route::group([
    'middleware' => ['auth:api']
], function (Router $router) {

    $router->group(['middleware' => ['admin']], function (Router $router) {
        $router->post('schedule/', [AdminScheduleController::class, 'create']);
        $router->put('schedule/', [AdminScheduleController::class, 'update']);
        $router->delete('schedule/', [AdminScheduleController::class, 'delete']);
        $router->get('schedule/accumulated', [AdminScheduleController::class, 'accumulated']);

        $router->get('users/', [AdminUserController::class, 'getAll']);
        $router->put('users/{id}', [AdminUserController::class, 'update']);
        $router->delete('users/{id}', [AdminUserController::class, 'delete']);
    });

    $router->get('schedule/view', [ScheduleController::class, 'getByEmail']);
    $router->get('schedule/me', [ScheduleController::class, 'getSelf']);

});
