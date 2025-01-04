<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Admin\SSLController;

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

Route::middleware('auth:api')->group(function () {
    // Client
    Route::get('/client/create', [ClientController::class, 'createClient']);
    Route::get('/client/toggleClientStatus', [ClientController::class, 'toggleClientStatus']);

    //Manage DB
    Route::get('/database/{database}/check-usage', [DatabaseController::class, 'checkUsage']);
    Route::get('/database/{database}/create', [DatabaseController::class, 'create']);

    //SSL
    Route::post('/database/{database}/provision-ssl', [SSLController::class, 'provisionSSL']);

    //Domain
    Route::get('/domain/create', [DomainController::class, 'create']);
    Route::post('/domain/{database}/createDatabase', [DomainController::class, 'createDatabase']);
    Route::post('/domain/{database}/ChackUsedDatabase', [DomainController::class, 'ChackUsedDatabase']);

    //Send e-mail
    Route::post('/password/email', [ResetPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/password/reset', [ResetPasswordController::class, 'reset']);
    
});


