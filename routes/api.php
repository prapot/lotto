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

Route::post('/guide/soidow', function () {
    try {
        sleep(5);
        Artisan::call("command:SendHuay");
        $message = [
            'status' => 200,
            'success' => 'true',
        ];
        return $message;
    } catch (\Throwable $e) {
        $message = [
            'status' => 500,
            'success' => 'false',
        ];
        return $message;
    }
});