<?php

namespace App\Http\Controllers;

use App\Models\AccessPlanes;
use App\Models\Camps;
use App\Models\Packages;
use App\Models\Subscriptions;
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
    }//store

    public function getVoucherNo()
    {
        $generated_code = $this->generateNumericVoucherCode();

        //get labor packages
        $labor_packages = Packages::where('customerType_id', 1)->get();

        return response()->json([
            'code' => $generated_code,
            'packages' => $labor_packages,
        ]);
    }

    //-------------------------- Functions --------------------------//
    function generateNumericVoucherCode()
    {
        return (string) random_int(10000000, 99999999);
    }
}//class


