<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

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

Route::post('guide/soidow/{server?}', function ($server = null) {

    $cooldown = Cache::has('cooldown');
    $message = Cache::remember('cooldown',config('cache.redis_lifetime'), function ()  {
        $message = [
            'status' => 200,
            'success' => 'true',
            'message' => 'waiting time.',
        ];
        return $message;
    });
    // Cache::forget('cooldown');
    if($cooldown){
        return $message;
    }

    if($server){
        $message = [
            'status' => 200,
            'success' => 'true',
            'message' => 'server test',
        ];
        return $message;
    }
    try {
        $message = [
            'status' => 200,
            'success' => 'true',
        ];
        sleep(55);
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
            'message' => $e->getMessage()
        ];
        return $message;
    }
});