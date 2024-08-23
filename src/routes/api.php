<?php

use App\Http\Controllers\Api\UserAuthenticationController;
use App\Http\Controllers\User\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[UserAuthenticationController::class, 'login']);

Route::post('registration',[UserAuthenticationController::class, 'registration']);

Route::group(['middleware'=>['auth:sanctum','common']],function (){

});

Route::group(['middleware'=>['auth:sanctum','admin']],function (){
    Route::apiResource('users', UserController::class);
});








