<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ScheduleController;

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
Route::group(['prefix' => 'auth'], function (\Illuminate\Routing\Router $router) {
    /** Register */
    $router->post('register', 'Auth\RegisterController');

    /** Login */
    $router->post('login', 'Auth\LoginController');
});

/** Authenticated routes group */
Route::group([
    'prefix' => 'schedule',
    'middleware' => ['auth:api', 'admin']
], function (\Illuminate\Routing\Router $router) {

    $router->post('/', [ScheduleController::class, 'create']);

    $router->put('/{id}', [ScheduleController::class, 'update']);

    $router->get('/{id}', [ScheduleController::class, 'getById']);

    $router->post('/', [ScheduleController::class, 'getAll']);

});
