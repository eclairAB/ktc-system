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
    Route::get('/get/emptyloaded',[QueriesController::class,"getEmptyLoaded"]);
    Route::get('/get/client/dateIn',[QueriesController::class,"getClientByDateIn"]);
    Route::get('/get/client/dateOut',[QueriesController::class,"getClientByDateOut"]);

    Route::post('/create/container/classes', [PostsController::class, "createClass"]);
    Route::post('/create/container/damages', [PostsController::class, "createDamages"]);
    Route::post('/create/container/repairs', [PostsController::class, "createRepairs"]);
    Route::post('/create/container/components', [PostsController::class, "createComponents"]);
    Route::post('/create/yards', [PostsController::class, "createYard"]);
    Route::post('/create/client', [PostsController::class, "createClient"]);
    Route::post('/create/Staff', [PostsController::class, "createStaff"]);
    Route::post('/create/checker', [PostsController::class, "createChecker"]);
    Route::post('/create/releasing',[PostsController::class,"createReleasing"]);
    Route::post('/create/receiving',[PostsController::class,"createReceiving"]);
    Route::post('/create/sizeType',[PostsController::class,"createSizeType"]);
    Route::post('/create/damage',[PostsController::class,"ReceivingDamage"]);
    Route::post('/check/damage',[PostsController::class,"ReceivingDamageChecker"]);
    Route::post('/create/type',[PostsController::class,"createType"]);

    Route::post('/update/container/classes', [UpdateController::class, "updateClass"]);
    Route::post('/update/container/damages', [UpdateController::class, "updateDamages"]);
    Route::post('/update/container/repairs', [UpdateController::class, "updateRepairs"]);
    Route::post('/update/container/components', [UpdateController::class, "updateComponents"]);
    Route::post('/update/yards', [UpdateController::class, "updateYard"]);
    Route::post('/update/client', [UpdateController::class, "updateClient"]);
    Route::post('/update/Staff', [UpdateController::class, "updateStaff"]);
    Route::post('/update/checker', [UpdateController::class, "updateChecker"]);
    Route::post('/update/releasing',[UpdateController::class,"updateReleasing"]);
    Route::post('/update/receiving',[UpdateController::class,"updateReceiving"]);
    Route::post('/update/sizeType',[UpdateController::class,"updateSizeType"]);
    Route::post('/update/damage',[UpdateController::class,"ReceivingDamageUpdate"]);

    Route::get('/get/releasing/byId/{id}',[QueriesController::class,"getReleasingById"]);
    Route::get('/get/receiving/byId/{id}',[QueriesController::class,"getReceivingById"]);

    Route::get('/get/container/components/byId/{id}',[QueriesController::class,"getComponentsById"]);
    Route::get('/get/container/repairs/byId/{id}',[QueriesController::class,"getRepairsById"]);
    Route::get('/get/container/damages/byId/{id}',[QueriesController::class,"getDamagesById"]);
    Route::get('/get/container/classes/byId/{id}',[QueriesController::class,"getClassById"]);
    Route::get('/get/yards/byId/{id}',[QueriesController::class,"getYardById"]);
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
    Route::get('/get/print/aging/{type}/{sizeType}/{client}/{class}/{date_in_from}/{date_in_to}/{date_out_from}/{date_out_to}/{option}/{status}',[QueriesController::class,"prntAging"]);
    Route::get('/get/print/daily_in/{type}/{sizeType}/{client}/{class}/{status}/{from}/{to}',[QueriesController::class,"prntDailyIn"]);
    Route::get('/get/print/daily_out/{type}/{sizeType}/{client}/{class}/{status}/{from}/{to}',[QueriesController::class,"prntDailyOut"]);
    // fetch container inquiry
    Route::get('/container-inquiry/{container_no}',[QueriesController::class,"containerInquiry"]);
    Route::get('/container-inquiry/download/{record_type}/{container_no}',['as' => 'admin.container-inquiry.download', 'uses' => 'App\Http\Controllers\QueriesController@saveImages']);
    Route::get('/container-images/download/{record_type}/{container_no}',[QueriesController::class,"saveImages"]);

    Route::delete('/delete/damage/{id}',[UpdateController::class,"ReceivingDamageDelete"]);    
});

  // EXCEL
    Route::get('excel/daily_container_in/{type}/{sizeType}/{client}/{class}/{status}/{from}/{to}',[ExcelController::class,"dailyContainerIn"])->name('excel.daily_container_in');
    Route::get('excel/daily_container_out/{type}/{sizeType}/{client}/{class}/{status}/{from}/{to}',[ExcelController::class,"dailyContainerOut"])->name('excel.daily_container_out');
    Route::get('excel/container_aging/{type}/{sizeType}/{client}/{class}/{date_in_from}/{date_in_to}/{date_out_from}/{date_out_to}/{option}/{status}',[ExcelController::class,"containerAging"])->name('excel.container_aging');
    /*Route::group(['prefix' => 'admin'], function () {
        Voyager::routes();
    });*/
