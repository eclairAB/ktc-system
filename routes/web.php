<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\PostsController;
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

Route::get('/', function () {
    return redirect()->route('voyager.login');
});

Route::group(['prefix' => 'admin'], function () {
    require __DIR__.'/voyager.php';

    Route::get('/{url_role}', [ViewsController::class, "roleView"]);
    Route::get('/get/yards',[QueriesController::class,"getYardLocation"]);
    Route::get('/get/clients',[QueriesController::class,"getClient"]);
    Route::get('/get/receiving/details',[QueriesController::class,"getReceivingDetails"]);
    Route::get('/get/container/classes',[QueriesController::class,"getContainterClass"]);
    Route::get('/get/container/heights',[QueriesController::class,"getContainterHeight"]);
    Route::get('/get/container/size_type',[QueriesController::class,"getContainterSizeType"]);
    Route::get('/get/print/releasing/{id}',[QueriesController::class,"prntReleasing"]);
    Route::get('/get/print/receiving/{id}',[QueriesController::class,"prntReceiving"]);

    Route::post('/create/client', [PostsController::class, "createClient"]);
    Route::post('/create/Staff', [PostsController::class, "createStaff"]);
    Route::post('/create/releasing',[PostsController::class,"createReleasing"]);
    Route::post('/create/receiving',[PostsController::class,"createReceiving"]);
    Route::post('/create/sizeType',[PostsController::class,"createSizeType"]);
});