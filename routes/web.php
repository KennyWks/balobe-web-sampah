<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('login')->middleware('guest');
Route::post('/signin', [AuthController::class, 'login']);
Route::post('/signout', [AuthController::class, 'logout']);

Route::group(['prefix' => 'admin'], function () {
    Route::get('/beranda', [AdminController::class, 'index'])->middleware('auth');
});
