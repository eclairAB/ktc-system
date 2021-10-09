<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsController;
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
    Voyager::routes();

    Route::get('admin', [ViewsController::class, "roleRedirect"]);
});