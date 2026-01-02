<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CampController;
use App\Http\Controllers\CampUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientReportController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MikrotikController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RolepageController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserAccessController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WifiLoginController;
use App\Models\CampUsers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| CloudTik project routes file.
| here all routes for web application are defined.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [CampController::class, 'campPortal'])->middleware('auth', 'verified')->name('camp.campPortal');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    //dashboard
    Route::get('/home', [DashboardController::class, 'index'])->name('dashboard.home');
    Route::get('/getBarchartData', [DashboardController::class, 'getBarchartData']);
    Route::get('/getDonutchartData', [DashboardController::class, 'getDonutchartData']);

    //camps
    Route::get('/camps', [CampController::class, 'index'])->name('camps.index');
    Route::get('/add-camp', [CampController::class, 'create'])->name('camps.create');
    Route::post('/store-camp', [CampController::class, 'store'])->name('camps.store');
    Route::post('/edit-camp', [CampController::class, 'edit'])->name('camps.edit');
    Route::put('/update-camp', [CampController::class, 'update'])->name('camps.update');
    Route::get('/gotoCamp/{camp_id}', [CampController::class, 'select'])->name('camp.select');

    //camp users
    Route::get('/campusers', [CampUserController::class, 'index'])->name('campusers.index');
    Route::post('/add-campusers', [CampUserController::class, 'store'])->name('campusers.store');
    Route::get('/getOneCampuser', [CampUserController::class, 'getOneCampuser'])->name('campusers.getOne');
    Route::put('/update-campusers', [CampUserController::class, 'update'])->name('campusers.update');
    Route::delete('/delete-campuser', [CampUserController::class, 'destroy'])->name('campuser.delete');

    //customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::post('/store-customers', [CustomerController::class, 'store'])->name('customer.store');
    Route::put('/update-customers', [CustomerController::class, 'update'])->name('customer.update');
    Route::put('/update-expire-date', [CustomerController::class, 'updateExpireDate'])->name('customer.updateExpireDate');
    Route::put('/deactivateCustomer', [CustomerController::class, 'deactivateCustomer'])->name('customer.deactivate');
    Route::get('/getCustomers', [CustomerController::class, 'getCustomers']);
    Route::get('/getOneCustomer', [CustomerController::class, 'getOneCustomer']);
    Route::get('/getCustomerTypes', [CustomerController::class, 'getCustomerTypes']);
    Route::post('/storeOneCustomer', [CustomerController::class, 'storeOneCustomer']);
    Route::get('/getCustomerByUsername', [CustomerController::class, 'getCustomerByUsername']);
    Route::get('/customer-search', [CustomerController::class, 'customerSearch'])->name('customer.search');

    //packages
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('/store-packages', [PackageController::class, 'store'])->name('packages.store');
    Route::put('/update-packages', [PackageController::class, 'update'])->name('packages.update');
    Route::get('/getCustomerPackages', [PackageController::class, 'getCustomerPackages']);
    Route::get('/getOnePackage', [PackageController::class, 'getOnePackage']);
    Route::get('/package-search', [PackageController::class, 'packageSearch'])->name('package.search');

    //invoice
    Route::get('/invoice', [SubscriptionController::class, 'index'])->name('invoice.index');
    Route::post('/store-subscription', [SubscriptionController::class, 'store'])->name('subscription.store');

    //receipt print
    Route::get('/receipt-print', [SubscriptionController::class, 'receiptPrint'])->name('invoice.receiptPrint');

    //subscriptions
    Route::get('/view-subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::get('/getOneSubscription', [SubscriptionController::class, 'getOneSubscription'])->name('subscription.getOneSubscription');
    Route::post('/updateSubsStatus', [SubscriptionController::class, 'updateStatus']);
    Route::post('/deleteSubscription', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');
    Route::post('/resetSubscription', [SubscriptionController::class, 'resetMacAddress'])->name('subscription.reset');
    Route::post('/changeStatusSubscription', [SubscriptionController::class, 'changeStatusSubscription'])->name('subscription.changeStatus');
    Route::get('/getCounterTotal', [SubscriptionController::class, 'getSubscriptionByCounter'])->name('subscription.total');
    Route::get('/getCustomerSubscriptions', [SubscriptionController::class, 'getSubscriptionByCustomer'])->name('subscription.customer');
    Route::get('/subscription-search', [SubscriptionController::class, 'subscriptionSearch'])->name('subscription.search');
    Route::get('/getRunningSubscriptionByCustomer', [SubscriptionController::class, 'getRunningSubscriptionByCustomer']);
    Route::post('/changeCamp', [SubscriptionController::class, 'changeCamp'])->name('subscription.changeCamp');
    Route::post('/cancelSubscription', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel');

    //users
    Route::get('/users-list', [UserController::class, 'index'])->name('users.index');
    Route::post('/store-user', [UserController::class, 'store'])->name('users.store');
    Route::put('/update-user', [UserController::class, 'update'])->name('users.update');
    Route::put('/update-pwd-user', [UserController::class, 'update_pwd'])->name('users.updatepwd');
    Route::get('/userProfile', [UserController::class, 'userProfile'])->name('users.profile');
    Route::get('/getOneUser', [UserController::class, 'getOneUser']);

    //reports
    Route::get('/sales_reports', [ReportsController::class, 'showSalesReports']);
    Route::get('/rpt_daily_sales', [ReportsController::class, 'showDailySalesReport']);
    Route::get('/rpt_daily_sales_search', [ReportsController::class, 'rptDailySalesSearch'])->name('rptDailySales.search');
    Route::get('/rpt_daily_summary', [ReportsController::class, 'showDailySummaryReport']);
    Route::get('/rpt_daily_summary_search', [ReportsController::class, 'rptDailySummarySearch'])->name('rptDailySummary.search');
    Route::get('/rpt_sales_summary', [ReportsController::class, 'showSalesSummaryReport']);
    Route::get('/rpt_sales_summary_search', [ReportsController::class, 'rptSalesSummarySearch'])->name('rptSalesSummary.search');
    Route::get('/rpt_user_sales', [ReportsController::class, 'showUserSalesReport']);
    Route::get('/rpt_user_sales_search', [ReportsController::class, 'rptUserSalesSearch'])->name('rptUserSales.search');
    Route::get('/rpt_user_package_summary', [ReportsController::class, 'showUserPackageSummaryReport']);
    Route::get('/rpt_user_package_summary_search', [ReportsController::class, 'rptUserPackageSummarySearch'])->name('rptUserPackageSummary.search');
    Route::get('/rpt_user_sales_summary', [ReportsController::class, 'showUserSalesSummaryReport']);
    Route::get('/rpt_user_sales_summary_search', [ReportsController::class, 'rptUserSalesSummarySearch'])->name('rptUserSalesSummary.search');

    //client side reports
    Route::get('/sale_reports', [ClientReportController::class, 'showSaleReports']);
    Route::get('/rpt_daily_sale', [ClientReportController::class, 'showDailySaleReport']);
    Route::get('/rpt_daily_sale_search', [ClientReportController::class, 'rptDailySaleSearch'])->name('rptDailySaleSearch.search');
    Route::get('/rpt_sale_summary', [ClientReportController::class, 'showSaleSummary']);
    Route::get('/rpt_sale_summary_search', [ClientReportController::class, 'rptSaleSummarySearch'])->name('rptSaleSummarySearch.search');

    //user page access
    Route::get('/useraccess', [UserAccessController::class, 'index'])->name('useraccess.index');
    Route::post('/store-useraccess', [UserAccessController::class, 'store'])->name('useraccess.store');
    Route::delete('/delete-useraccess', [UserAccessController::class, 'destroy'])->name('useraccess.delete');
    Route::get('/update-useraccess', [UserAccessController::class, 'update']);

    //client routes
    Route::get('/analysis', [ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/getAnalysisBarchartData', [ClientController::class, 'getAnalysisBarchartData'])->name('client.getAnalysisBarchartData');

    //mikrotik
    Route::get('/mikrotik', [MikrotikController::class, 'index'])->name('mikrotik.index');
    Route::get('/add_hotspot_users', [MikrotikController::class, 'showAddUsers'])->name('mikrotik.hotspotusers');
    Route::get('/manual_subscriptions', [MikrotikController::class, 'showSubscription']);
    Route::post('/mikrotik_store', [MikrotikController::class, 'store'])->name('mikrotik.store');
    Route::post('/bind_mac', [MikrotikController::class, 'bindMac'])->name('mikrotik.bindmac');
    Route::post('/unbind_mac', [MikrotikController::class, 'unbindMac'])->name('mikrotik.unbindmac');

    //for testing, delete this once testing is over
    Route::post('/checkConnection', [MikrotikController::class, 'checkConnection'])->name('mikrotik.checkConnection');

});

//Wifi log in controller
Route::get('/userlogin', [WifiLoginController::class, 'index'])->name('wifilogin.index');
Route::get('/userregister', [WifiLoginController::class, 'register'])->name('wifilogin.register');
Route::post('/register-store', [WifiLoginController::class, 'store'])->name('wifilogin.store');

//customer profile
Route::get('/cust_login', [CustomerProfileController::class, 'index'])->name('customer.custLogin');
Route::post('/customer-login', [CustomerProfileController::class, 'login'])->name('customer.login');

//customer auth middleware
Route::middleware(['customerAuth'])->group(function(){
    Route::get('/cust_home', [CustomerProfileController::class, 'custHome'])->name('customer.custHome');
    Route::get('/cust_subscription', [CustomerProfileController::class, 'custSubscription'])->name('customer.custSubscription');
    Route::get('/cust_profile', [CustomerProfileController::class, 'custProfile'])->name('customer.custProfile');
    Route::post('/cust_change_pwd', [CustomerProfileController::class, 'changePassword'])->name('customer.changePassword');
    Route::get('/cust_logout', [CustomerProfileController::class, 'logout'])->name('customer.custLogout');
});

Route::get('login1', function(){
    return view('auth.login1');
});

require __DIR__ . '/auth.php';
