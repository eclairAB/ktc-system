<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UpdateController;

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
    return view('voyager::login');
});

Route::group(['prefix' => 'admin'], function () {
    require __DIR__.'/voyager.php';

    Route::get('/{url_role}', [ViewsController::class, "roleView"]);
    Route::get('/get/yards',[QueriesController::class,"getYardLocation"]);
    Route::get('/get/clients',[QueriesController::class,"getClient"]);
    Route::get('/get/receiving/details',[QueriesController::class,"getReceivingDetails"]);
    Route::get('/get/details/forUpdate',[QueriesController::class,"geDetailsForUpdate"]);
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

    Route::post('/update/client', [UpdateController::class, "updateClient"]);
    Route::post('/update/Staff', [UpdateController::class, "updateStaff"]);
    Route::post('/update/releasing',[UpdateController::class,"updateReleasing"]);
    Route::post('/update/receiving',[UpdateController::class,"updateReceiving"]);
    Route::post('/update/sizeType',[UpdateController::class,"updateSizeType"]);

    Route::get('/get/releasing/byId/{id}',[QueriesController::class,"getReleasingById"]);
    Route::get('/get/receiving/byId/{id}',[QueriesController::class,"getReceivingById"]);
    Route::get('/get/sizeType/byId/{id}',[QueriesController::class,"getSizeTypeById"]);
    Route::get('/get/client/byId/{id}',[QueriesController::class,"getClientById"]);
    Route::get('/get/Staff/byId/{id}',[QueriesController::class,"getStaffById"]);
});
