<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SSLController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DatabaseController;

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

//autentication PassPort


//administer DB
Route::put('/admin/database/{database}/quota', [AdminController::class, 'setDatabaseQuota']);
Route::post('/database/{database}/provision-ssl', [SSLController::class, 'provisionSSL']);
Route::get('/database/{database}/check-usage', [DatabaseController::class, 'checkUsage']);

//Send e-mail
Route::post('/password/email', [ResetPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);

//to create Client
Route::post('/admin/client', [ClientController::class, 'store']);

// Routes authenticator passport
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});






;


