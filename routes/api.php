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

Route::get('/weather', function () {
    $apikey = config('services.darksky.key');
    $lat = request('lat'); //37.8267
    $lng = request('lng'); //-122.4233

    //optional parameter : [ https://darksky.net/dev/docs ]
    //exclude = currently, minutely, hourly, daily, alerts, flags
    //extend=hourly
    //lang=en (english),hi (hindi)
    //units

   $response = Zttp\Zttp::get("https://api.darksky.net/forecast/$apikey/$lat,$lng?lang=en&units=ca");
   return $response->json();
});
