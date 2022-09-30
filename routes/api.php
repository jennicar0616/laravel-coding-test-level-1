<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::get('events', 'Api\EventsController@getEvents');
    Route::get('events/active-events', 'EventsController@activeEvents');
    Route::get('events/{id}', 'EventsController@show');
    Route::put('events/{id}', 'EventsController@store');
    Route::patch('events/{id}', 'EventsController@update');
    Route::delete('events/{id}', 'EventsController@delete');
});
