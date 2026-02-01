<?php

namespace App\Http\Controllers;

use App\Models\AccessPlanes;
use App\Models\Camps;
use App\Models\Packages;
use App\Models\Subscriptions;
use App\Models\Vouchers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InvoiceController extends Controller
{
    public function index(){
        $user_id = auth()->user()->id;
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        return view('Invoice.invoice', compact('camp'));
    }

    public function storeSubscription(Request $request)
    {
        $user_id = auth()->user()->id;
        $camp_id = Session::get('active_camp_id');

        $customer_id = $request->input('cmb_customer');
        $package_id = $request->input('cmb_subscription_packages');
        $purchased_date = date('Y-m-d');
        $purchased_time = date('Y-m-d H:i:s');

        //get price
        $package = Packages::find($package_id);
        $price = $package->price;

        $status = 1; //Active package

        //create subscription
        $subscription = Subscriptions::create([
            'customer_id' => $customer_id,
        ]);

        //accessable
        $accessable_type = Subscriptions::class;
        $accessable_id = $subscription->id;

        $invoice = AccessPlanes::create([
            'camp_id' => $camp_id,
            'user_id' => $user_id,
            'package_id' => $package_id,
            'paymethod_id' => 1, //default cash
            'accessable_type' => $accessable_type,
            'accessable_id' => $accessable_id,
            'purchaseDate' => $purchased_date,
            'purchaseDateTime' => $purchased_time,
            'price' => $price,
            'status' => 1, //Active status
        ]);

        if($invoice){
            return redirect()->route('invoice.index')->with('success', 'Subscription added successfully!');
        }
        else{
            return redirect()->route('invoice.index')->with('error', 'Subscription add failed!');
        }
    }//subscription store

    public function storeVoucher(Request $request)
    {
        date_default_timezone_set('Asia/Dubai');

        $user_id = auth()->user()->id;
        $camp_id = Session::get('active_camp_id');

        $package_id = $request->input('cmb_voucher_packages');
        $purchased_date = date('Y-m-d');
        $purchased_time = date('Y-m-d H:i:s');

        //get price
        $package = Packages::find($package_id);
        $price = $package->price;

        $status = 1; //Active package

        $voucher_id = $request->input("hide_voucher_id");
        $voucher = Vouchers::find($voucher_id);
        // $generated_code = $request->input('hide_voucher_no');
        $expire_at = now()->addDays(90);

        //accessable
        $accessable_type = Vouchers::class;
        $accessable_id = $voucher_id;

        $invoice = AccessPlanes::create([
            'camp_id' => $camp_id,
            'user_id' => $user_id,
            'package_id' => $package_id,
            'paymethod_id' => 1, //default cash
            'accessable_type' => $accessable_type,
            'accessable_id' => $accessable_id,
            'purchaseDate' => $purchased_date,
            'purchaseDateTime' => $purchased_time,
            'price' => $price,
            'status' => 1, //Active status
        ]);

        $voucher->status = 2; //active status
        $voucher->save();

        return redirect()->route('invoice.index')->with('success', 'Voucher added successfully!');
    }//store voucher

    public function generateVoucherCode(Request $request)
    {
        date_default_timezone_set('Asia/Dubai');

        $username = $request->input('username');
        $package_id = $request->input('package_id');

        $user_id = auth()->user()->id;
        $camp_id = Session::get('active_camp_id');

        $purchased_date = date('Y-m-d');
        $purchased_time = date('Y-m-d H:i:s');
        $expire_at = now()->addDays(90);

        //get price
        $package = Packages::find($package_id);
        $price = $package->price;

        $generated_code = "";
        //validate unique
        do{
            $generated_code = $this->generateNumericVoucherCode();
        }
        while(Vouchers::where('code', $generated_code)
                ->whereBetween('expire_date', [now(), $expire_at])
                ->exists()
            );

        //create voucher
        $voucher = Vouchers::create([
            'username' => $username,
            'code' => $generated_code,
            'expire_date' => $expire_at,
            'status' => 1, //pending
        ]);

        //accessable
        $accessable_type = Vouchers::class;
        $accessable_id = $voucher->id;

        $invoice = AccessPlanes::create([
            'camp_id' => $camp_id,
            'user_id' => $user_id,
            'package_id' => $package_id,
            'paymethod_id' => 1, //default cash
            'accessable_type' => $accessable_type,
            'accessable_id' => $accessable_id,
            'purchaseDate' => $purchased_date,
            'purchaseDateTime' => $purchased_time,
            'price' => $price,
            'status' => 1, //Active status
        ]);

        return response()->json([
            'code' => $generated_code,
            'package' => $package->name,
            'duration' => $package->duration,
            'price' => $package->price,
        ]);

    }//generate voucher code

    //-------------------------- Functions --------------------------//
    function generateNumericVoucherCode()
    {
        return (string) random_int(10000000, 99999999);
    }
}//class


