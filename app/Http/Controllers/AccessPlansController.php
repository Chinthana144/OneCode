<?php

namespace App\Http\Controllers;

use App\Models\AccessPlanes;
use App\Models\Camps;
use App\Models\Customers;
use App\Models\Subscriptions;
use App\Models\Vouchers;
use App\Services\HotspotUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\isEmpty;

class AccessPlansController extends Controller
{
    public function index()
    {
        $camp_id = Session::get('active_camp_id');
        $camps = Camps::where('status', 1)->get();
        $access_plans = AccessPlanes::where('purchaseDate', date('Y-m-d'))
            ->where('camp_id', $camp_id)
            ->paginate(10);

        return view('AccessPlans.access_plans_view', compact('access_plans', 'camps'));
    }

    public function accessPlanSearch(Request $request)
    {
        $camp_id = Session::get('active_camp_id');
        $camps = Camps::where('status', 1)->get();

        $search = $request->input('txt_search');

        $access_plans = AccessPlanes::where('camp_id', $camp_id)
            ->where(function ($query) use ($search) {

                // Search by purchase date (string match)
                $query->orWhere('purchaseDateTime', 'LIKE', "%{$search}%");

                // Search by polymorphic relations
                $query->orWhereHasMorph(
                    'accessable',
                    [Subscriptions::class, Vouchers::class],
                    function ($q, $type) use ($search) {

                        if ($type === Subscriptions::class) {
                            $q->whereHas('customer', function ($c) use ($search) {
                                $c->where('fullname', 'LIKE', "%{$search}%")
                                ->orWhere('username', 'LIKE', "%{$search}%");
                            });
                        }

                        if ($type === Vouchers::class) {
                            $q->where('code', 'LIKE', "%{$search}%")
                                ->orWhere('username', 'LIKE', "%{$search}%");
                        }
                    }
                );
            })
            ->paginate(10);

        return view('AccessPlans.access_plans_view', compact('access_plans', 'camps', 'search'));
    }//search

    public function destroy(Request $request)
    {
        $access_plan_id = $request->input('hide_access_plan_id');
        $access_plan = AccessPlanes::find($access_plan_id);

        $access_type = $access_plan->accessable_type;
        if($access_type == 'App\Models\Subscriptions')
        {
            $subscription = Subscriptions::find($access_plan->accessable_id);
            $subscription->delete();
        }
        if($access_type == 'App\Models\Vouchers')
        {
            $vouchers = Vouchers::find($access_plan->accessable_id);
            $vouchers->delete();
        }

        $access_plan->delete();

        return redirect()->route('access_plans.index')->with('success', 'Access Plan deleted successfully!');
    }

    public function resetStatus(Request $request)
    {
        $camp_id = $request->input('camp_id');
        $access_plan_id = $request->input('access_plan_id');
        $access_plan = AccessPlanes::find($access_plan_id);

        $camp_data = Camps::find($camp_id);

        $host = $camp_data->mikritikIP;
        $camp_user = $camp_data->mikrotikUsername;
        $camp_password = $camp_data->mikrotikPassword;
        $port = $camp_data->mikritikPort;

        $hotspot = new HotspotUsers($host, $camp_user, $camp_password, $port);

        $mac_address = $access_plan->mac_address;

        if($request->action == 'reset')
        {
            if($mac_address != "" || !isEmpty($mac_address))
            {
                $hotspot->unbindMacAddressFromUser($mac_address);
            }
            $access_plan->status = 1;
            $access_plan->mac_address = "";

            $access_plan->save();

            return redirect()->route('access_plans.index')->with('success', 'Access Plan reset successfully!');
        }
        if($request->action == 'cancel')
        {
            if($mac_address != "" || !isEmpty($mac_address))
            {
                $hotspot->unbindMacAddressFromUser($mac_address);
            }
            $access_plan->status = 4;
            $access_plan->mac_address = "";

            $access_plan->save();

            return redirect()->route('access_plans.index')->with('success', 'Access Plan canceled successfully!');
        }
    }//reset status

    public function campTransfer(Request $request)
    {
        $access_plan_id = $request->input('ch_access_plan_id');
        $access_plan = AccessPlanes::find($access_plan_id);

        $this_camp_id = $access_plan->camp_id;
        $transfer_camp_id = $request->input('cmb_camp');

        if($this_camp_id != $transfer_camp_id)
        {
            $access_type = $access_plan->accessable_type;
            //subscription
            if($access_type == 'App\Models\Subscriptions')
            {
                $customer_id = $access_plan->accessable->customer_id;
                $customer = Customers::find($customer_id);

                //transfer customer
                $customer->camp_id = $transfer_camp_id;
                $customer->save();

                $access_plan->status = 5;//transfer status
                $access_plan->save();

                //create new invoice
                $invoice = AccessPlanes::create([
                    'camp_id' => $transfer_camp_id,
                    'user_id' => $access_plan->user_id,
                    'package_id' => $access_plan->package_id,
                    'paymethod_id' => 1, //default cash
                    'accessable_type' => $access_plan->accessable_type,
                    'accessable_id' => $access_plan->accessable_id,
                    'purchaseDate' => $access_plan->purchaseDate,
                    'purchaseDateTime' => $access_plan->purchaseDateTime,
                    'price' => 0,//already paid to previous camp
                    'status' => 1, //Active status
                ]);

                return redirect()->route('access_plans.index')->with('success', 'Subscription Transferred Successfully!');
            }//subscription transfer

            //vouchers
            if($access_type == 'App\Models\Vouchers')
            {
                $access_plan->status = 5;//transfer status
                $access_plan->save();

                //create new invoice
                $invoice = AccessPlanes::create([
                    'camp_id' => $transfer_camp_id,
                    'user_id' => $access_plan->user_id,
                    'package_id' => $access_plan->package_id,
                    'paymethod_id' => 1, //default cash
                    'accessable_type' => $access_plan->accessable_type,
                    'accessable_id' => $access_plan->accessable_id,
                    'purchaseDate' => $access_plan->purchaseDate,
                    'purchaseDateTime' => $access_plan->purchaseDateTime,
                    'price' => 0,//already paid to previous camp
                    'status' => 1, //Active status
                ]);

                return redirect()->route('access_plans.index')->with('success', 'Voucher Transferred Successfully!');
            }//vuucher transfer
        }
        else{
            return redirect()->route('access_plans.index')->with('error', 'Cannot transfer to the same camp!');
        }
    }//camp transfer

    //change expire date
    public function changeExpireDate(Request $request)
    {
        $access_plan_id = $request->input('exp_access_plan_id');
        $access_plan = AccessPlanes::find($access_plan_id);

        $expire_date = $request->input('expire_date');
        $expire_time = $request->input('expire_time');

        // dd($expire_date, $expire_time);
        $expire_time_formatted = date('H:i:s', strtotime($expire_time));
        $expire_datetime = $expire_date . ' ' . $expire_time_formatted;

        if($access_plan)
        {
            $access_plan->expire_at = $expire_datetime;

            $access_plan->save();

            return redirect()->route('access_plans.index')->with('success', 'Expire date updated Successfully!');
        }

        return redirect()->route('access_plans.index')->with('error', 'Expire date updated Failed!');
    }//change expire date

    //=========================AJAX methods =================================//
    public function getOneAccessPlan(Request $request)
    {
        $access_plan_id = $request->input();
        $access_plan = AccessPlanes::find($access_plan_id);

        $access_type = $access_plan[0]['accessable_type'];
        $access_data = $access_plan[0];

        $data = "";

        if($access_type == 'App\Models\Subscriptions')
        {
            $data = [
                'type' => "Subscription",
                'camp_id' => $access_data->camp_id,
                'camp_name' => $access_data->camp->name,
                'package_id' => $access_data->package_id,
                'package_name' => $access_data->package->name,
                'package_duration' => $access_data->package->duration,
                'mac_address' => $access_data->mac_address,
                'customer_name' => $access_data->accessable->customer->fullname,
                'customer_username' => $access_data->accessable->customer->username,
                'login_at' => $access_data->login_at,
                'expire_at' => $access_data->expire_at,
                'status' => $access_data->status,
            ];
        }
        else
        {
            $data = [
                'type' => "Voucher",
                'camp_id' => $access_data->camp_id,
                'camp_name' => $access_data->camp->name,
                'package_id' => $access_data->package_id,
                'package_name' => $access_data->package->name,
                'package_duration' => $access_data->package->duration,
                'mac_address' => $access_data->mac_address,
                'code' => $access_data->accessable->code,
                'login_at' => $access_data->login_at,
                'expire_at' => $access_data->expire_at,
                'status' => $access_data->status,
            ];
        }

        return response()->json($data);
    }//get one access plan

}//class
