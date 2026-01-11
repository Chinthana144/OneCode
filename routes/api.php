<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WifiLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    //customer routes
    Route::post('/register_customer', [CustomerController::class, 'customerRegister'])->name('api.register.customer');
    Route::put('/update_customer', [CustomerController::class, 'updateCustomer'])->name('api.update.customer');
    Route::get('/search_customer', [CustomerController::class, 'searchCustomers'])->name('api.search.customer');
    Route::get('/getCustomersByCamp', [CustomerController::class, 'getCustomersByCamp'])->name('api.getCustomersByCamp');
    Route::get('/customersWithPackages', [CustomerController::class, 'customersWithPackages'])->name('api.search.customer');

    //packages
    Route::get('/getCustomerPackages', [PackageController::class, 'getCustomerPackages'])->name('api.customerPackages');

    //subscriptions
    Route::post('/addSubscriptionFromAPI', [SubscriptionController::class, 'addSubscriptionFromAPI'])->name('api.addSubscriptionFromAPI');
    Route::get('/getSubscriptionByUserDate', [SubscriptionController::class, 'getSubscriptionByUserDate'])->name('api.getSubscriptionByUserDate');
    Route::get('/searchSubscriptionsByUser', [SubscriptionController::class, 'searchSubscriptionsByUser'])->name('api.searchSubscriptionsByUser');
    Route::get('/getDonutChartData', [SubscriptionController::class, 'getDonutChartData'])->name('api.getDonutChartData');
    Route::get('/getBarChartData', [SubscriptionController::class, 'getBarChartData'])->name('api.getBarChartData');
    Route::get('/getOneSubscriptionAPI', [SubscriptionController::class, 'getOneSubscriptionAPI'])->name('api.getOneSubscriptionAPI');

    // Add your authenticated routes here
    Route::post('/logout', [UserController::class, 'logout'])->name('api.logout');
    Route::get('/getOneUser', [UserController::class, 'getOneUser'])->name('api.getOneUser');
});

// API route for user login
Route::post('/login', [UserController::class, 'login'])->name('api.login');

// API route for WiFi login
Route::match(['get', 'post'],'/wifi_login', [WifiLoginController::class, 'login'])->name('api.wifi_login');
