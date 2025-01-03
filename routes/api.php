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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('/admin/database/{database}/quota', [AdminController::class, 'setDatabaseQuota']);

Route::post('/database/{database}/provision-ssl', [SSLController::class, 'provisionSSL']);
Route::post('/admin/client', [ClientController::class, 'store']);

Route::put('/admin/database/{database}/quota', [AdminController::class, 'setDatabaseQuota']);

Route::get('/database/{database}/check-usage', [DatabaseController::class, 'checkUsage']);


Route::post('/password/email', [ResetPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);

Route::put('/admin/database/{database}/quota', [AdminController::class, 'setDatabaseQuota']);


