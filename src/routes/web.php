<?php

use App\Http\Controllers\BidderController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PropertyownerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Common\OrderController;
use App\Http\Controllers\Logistic\Logisticszone;
use App\Http\Controllers\Common\InvoiceController;
use App\Http\Controllers\Common\LocationController;

use App\Http\Controllers\Common\TrackingController;
use App\Http\Controllers\Settings\CouponController;
use App\Http\Controllers\Settings\StatusController;
use App\Http\Controllers\Settings\BoxsizeController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Settings\LogisticsController;
use App\Http\Controllers\Settings\SubStatusController;
use App\Http\Controllers\Logistic\LogisticRateController;
use App\Http\Controllers\Settings\StatushistoryController;
use App\Http\Controllers\Settings\Pickup_Location_Controller;
use App\Http\Controllers\Opsmaneger\OperationManagerController;

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

//Route::get('/', function () {
//    return view('welcome');
//});

//Auth::routes();

Route::get('/', function () {
    return view('Landing_page.landingpage');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware([App\Http\Middleware\Authenticate::class])->group(function () {
    //admin auth
    Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'auth'],], function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    });




    Route::group(['prefix' => 'bidder', 'middleware' => ['bidder', 'auth'], 'namespace' => 'Merchant'], function () {
        Route::get('dashboard', [BidderController::class, 'index'])->name('bidder.dashboard');

    });




    Route::group(['prefix' => 'propertyowner', 'middleware' => ['property-owner', 'auth'], 'namespace' => 'Opsmaneger'], function () {
        Route::get('dashboard', [PropertyownerController::class, 'index'])->name('propertyowner.dashboard');

    });





});

