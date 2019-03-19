<?php

use Illuminate\Http\Request;

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

Route::resource('/train', TrainStopController::class);
Route::resource('/bus', BusStopController::class);

Route::resource('/line', LineController::class);
Route::resource('/parish', ParishController::class);
Route::resource('/region', RegionController::class);
Route::resource('/roadSide', RoadSideController::class);
Route::resource('/village', VillageController::class);
Route::resource('/tower', TowerController::class);
