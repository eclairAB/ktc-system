<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\ExcelController;

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
    Route::get('/container-actions/{action}', [ViewsController::class, "containerActions"]);
    Route::get('/container-receivings-and-releasing/{action}', [ViewsController::class, "containerReceivingsAndReleasings"]);
    Route::get('/get/yards',[QueriesController::class,"getYardLocation"]);
    Route::get('/get/clients',[QueriesController::class,"getClient"]);
    Route::get('/get/receiving/details',[QueriesController::class,"getReceivingDetails"]);
    Route::get('/get/details/forUpdate',[QueriesController::class,"geDetailsForUpdate"]);
    Route::get('/get/container/classes',[QueriesController::class,"getContainterClass"]);
    Route::get('/get/container/size_type',[QueriesController::class,"getContainterSizeType"]);
    // Route::get('/get/container/heights',[QueriesController::class,"getContainterHeight"]);
    Route::get('/get/container/damage',[QueriesController::class,"getContainerDamage"]);
    Route::get('/get/container/component',[QueriesController::class,"getContainerComponent"]);
    Route::get('/get/container/repair',[QueriesController::class,"getContainerRepair"]);

    Route::post('/create/client', [PostsController::class, "createClient"]);
    Route::post('/create/Staff', [PostsController::class, "createStaff"]);
    Route::post('/create/checker', [PostsController::class, "createChecker"]);
    Route::post('/create/releasing',[PostsController::class,"createReleasing"]);
    Route::post('/create/receiving',[PostsController::class,"createReceiving"]);
    Route::post('/create/sizeType',[PostsController::class,"createSizeType"]);
    Route::post('/create/damage',[PostsController::class,"ReceivingDamage"]);
    Route::post('/check/damage',[PostsController::class,"ReceivingDamageChecker"]);

    Route::post('/create/type',[PostsController::class,"createType"]);

    Route::post('/update/client', [UpdateController::class, "updateClient"]);
    Route::post('/update/Staff', [UpdateController::class, "updateStaff"]);
    Route::post('/update/checker', [UpdateController::class, "updateChecker"]);
    Route::post('/update/releasing',[UpdateController::class,"updateReleasing"]);
    Route::post('/update/receiving',[UpdateController::class,"updateReceiving"]);
    Route::post('/update/sizeType',[UpdateController::class,"updateSizeType"]);
    Route::post('/update/damage',[UpdateController::class,"ReceivingDamageUpdate"]);

    Route::get('/get/releasing/byId/{id}',[QueriesController::class,"getReleasingById"]);
    Route::get('/get/receiving/byId/{id}',[QueriesController::class,"getReceivingById"]);
    Route::get('/get/sizeType/byId/{id}',[QueriesController::class,"getSizeTypeById"]);
    Route::get('/get/client/byId/{id}',[QueriesController::class,"getClientById"]);
    Route::get('/get/Staff/byId/{id}',[QueriesController::class,"getStaffById"]);
    Route::get('/get/checker/byId/{id}',[QueriesController::class,"getCheckerById"]);
    Route::get('/get/damage/{receiving_id}',[QueriesController::class,"getReceivingDamage"]);
    Route::get('/get/print/releasing/{id}',[QueriesController::class,"prntReleasing"]);
    Route::get('/get/print/receiving/{id}',[QueriesController::class,"prntReceiving"]);
    Route::get('/get/sizeType/all',[QueriesController::class,"getSizeTypeByAll"]);
    Route::get('/get/container_no/all',[QueriesController::class,"getContainerNos"]);
    Route::get('/get/booking_no/all',[QueriesController::class,"getBookingNos"]);
    Route::get('/get/container_no/byBookingNo',[QueriesController::class,"getContainerNosByBookingNo"]);

    Route::get('/get/type/all',[QueriesController::class,"getTypeByAll"]);
    Route::get('/get/type/byId/{id}',[QueriesController::class,"getTypeById"]);
    Route::get('/get/type',[QueriesController::class,"getType"]);
    Route::post('/update/type',[UpdateController::class,"updateType"]);

    // fetch daily in-out
    Route::post('/get/daily_in',[QueriesController::class,"getDailyIn"]);
    Route::post('/get/daily_out',[QueriesController::class,"getDailyOut"]);

    // fetch container aging
    Route::get('/container-aging/all', [ViewsController::class, "containerAging"]);
    Route::post('/get/container/aging',[QueriesController::class,"getContainerAging"]);

    // fetch container inquiry
    Route::get('/container-inquiry/{container_id}',[QueriesController::class,"containerInquiry"]);

    Route::delete('/delete/damage/{id}',[UpdateController::class,"ReceivingDamageDelete"]);

});

  // EXCEL
    Route::get('excel/daily_container_in/{type}/{sizeType}/{client}/{container_no}/{loc}/{from}/{to}',[ExcelController::class,"dailyContainerIn"])->name('excel.daily_container_in');
    Route::get('excel/daily_container_out/{type}/{sizeType}/{client}/{container_no}/{booking_no}/{from}/{to}',[ExcelController::class,"dailyContainerOut"])->name('excel.daily_container_out');
    Route::get('excel/container_aging/{type}/{sizeType}/{client}/{class}/{container_no}/{date_as_of}',[ExcelController::class,"containerAging"])->name('excel.container_aging');
    /*Route::group(['prefix' => 'admin'], function () {
        Voyager::routes();
    });*/
