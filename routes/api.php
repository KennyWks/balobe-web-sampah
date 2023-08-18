<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TransactionController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function(){
    return response()->json([
        "message" => "Welcome!"
    ]);
});
Route::resource('news', NewsController::class);
Route::post('/signin', [AuthApiController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('/user/signout', [AuthApiController::class, 'logout']);
    Route::post('/user/createorupdate',  [AuthApiController::class, 'updateOrCreateUser']);
    Route::post('/user/upload-photo', [AuthApiController::class, 'uploadPhoto']);
    Route::resource('/user/transaction', TransactionController::class);
    // Route::delete('delete/{product}',  [ProductController::class, 'destroy']);
});
