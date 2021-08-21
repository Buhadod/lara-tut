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

Route::post("/login-api","App\Http\Controllers\API\PassportController@login");
Route::post("/register-api","App\Http\Controllers\API\PassportController@register");


Route::post('/updateProfile','App\Http\Controllers\API\ProfileController@updateProfile');

Route::get('items/search/{query}',"App\Http\Controllers\API\ItemController@search");
Route::get('items/filter/{filter}/{query}',"App\Http\Controllers\API\ItemController@filter");
Route::get('items/paginate',"App\Http\Controllers\API\ItemController@paginate");

Route::resource('items',"App\Http\Controllers\API\ItemController");

Route::middleware(['auth:api'])->group(function () {
    Route::resource('item-auth',"App\Http\Controllers\API\ItemController");
});

Route::resource('products',"App\Http\Controllers\API\ProductController");

Route::post("/register-console-api","App\Http\Controllers\ConsoleController@register");
Route::post("/login-console-api","App\Http\Controllers\ConsoleController@login");


Route::post('show-console-api/{id}',"App\Http\Controllers\ConsoleController@show");

Route::group(['middleware' => ['auth:api','role:admin']], function () {
    
    
    Route::get("/test", function(){
        return "text";
    });

    Route::get('getUser-console-api',"App\Http\Controllers\ConsoleController@getUser");

   

});