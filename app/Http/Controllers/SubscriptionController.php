<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Models\Customers;
use App\Models\Packages;
use App\Models\Subscriptions;
use App\Models\ClientSubscriptions;
use App\Services\HotspotUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        return view('Invoice.invoice', compact('camp'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $camp_id = Session::get('active_camp_id');
        $camp_data = Camps::find($camp_id);

        $customer_id = $request->input('cmb_customer');
        $package_id = $request->input('cmb_packages');
        $purchased_date = date('Y-m-d');
        $purchased_time = date('Y-m-d H:i:s');

        //get price
        $package = Packages::find($package_id);
        $price = $package->price;

        //set paymethod id for cash
        $paymethod_id = 1; //cash payment method id

        if($request->action == 'recharge')
        {
            $stat_id = 2; //active status id
            //update running subscriptions
            Subscriptions::where('customer_id', $customer_id)
                ->where('status', 2)
                ->update(['status' => 3]);

            //get current expiry date
            $customer = Customers::find($customer_id);
            $current_expiry = $customer->expiry_datetime;

            $new_start_datetime = $current_expiry ?? date('Y-m-d H:i:s');
            $new_start_datetime = Carbon::parse($new_start_datetime);
            $new_end_datetime = $new_start_datetime->addDays($package->duration);

            //update customer expiry date
            $customer->expiry_datetime = $new_end_datetime;
            $customer->save();

            //create subscription
            $subscription = Subscriptions::create([
                'camp_id' => $camp_id,
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'package_id' => $package_id,
                'paymethod_id' => $paymethod_id,
                'purchaseDate' => $purchased_date,
                'purchaseDateTime' => $purchased_time,
                'subscriptionStartTime'=> $new_start_datetime,
                'subscriptionEndTime' => $new_end_datetime,
                'price' => $price,
                'macAddress' => $customer->mac_address,
                'status' => $stat_id,
            ]);

            return redirect()->route('invoice.index')->with('success', 'Subscription recharged successfully');
        }//add new subscription
        else
        {
            $stat_id = 1; //active status id
            //create subscription
            $subscription = Subscriptions::create([
                'camp_id' => $camp_id,
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'package_id' => $package_id,
                'paymethod_id' => $paymethod_id,
                'purchaseDate' => $purchased_date,
                'purchaseDateTime' => $purchased_time,
                'price' => $price,
                'macAddress' => '0',
                'status' => $stat_id,
            ]);

            return redirect()->route('invoice.index')->with('success', 'Subscription added successfully');
        }//add subscription
    }//store

    //add subscription from API
    public function addSubscriptionFromAPI(Request $request)
    {
        $user_id = $request->input('user_id');
        $camp_id = $request->input('camp_id');

        $customer_id = $request->input('customer_id');
        $package_id = $request->input('package_id');
        $purchased_date = date('Y-m-d');
        $purchased_time = date('Y-m-d H:i:s');

        //get price
        $package = Packages::find($package_id);
        $price = $package->price;

        //set status id to active
        $stat_id = 1;

        //set paymethod id for cash
        $paymethod_id = 1; //cash payment method id

        //create subscription
        $subscription = Subscriptions::create([
            'camp_id' => $camp_id,
            'user_id' => $user_id,
            'customer_id' => $customer_id,
            'package_id' => $package_id,
            'paymethod_id' => $paymethod_id,
            'purchaseDate' => $purchased_date,
            'purchaseDateTime' => $purchased_time,
            'price' => $price,
            'macAddress' => '0',
            'status' => $stat_id,
        ]);

        return response()->json([
            'success' => true,
            'subscription_id' => $subscription->id,
            'message' => 'Subscription added successfully',
        ]);
    }//add subscription from api

    public function rechargeSubscriptionFromAPI(Request $request)
    {
        $user_id = $request->input('user_id');
        $camp_id = $request->input('camp_id');

        $customer_id = $request->input('customer_id');
        $package_id = $request->input('package_id');

        $purchased_date = date('Y-m-d');
        $purchased_time = date('Y-m-d H:i:s');

        //get price
        $package = Packages::find($package_id);
        $price = $package->price;

        //set paymethod id for cash
        $paymethod_id = 1; //cash payment method id

        //check running sub
        $has_running_subscription = Subscriptions::where('customer_id', $customer_id)
            ->where('status', 2)
            ->exists();

        if($has_running_subscription){
            $stat_id = 2; //active status id
            //update running subscriptions
            Subscriptions::where('customer_id', $customer_id)
                ->where('status', 2)
                ->update(['status' => 3]);

            //get current expiry date
            $customer = Customers::find($customer_id);
            $current_expiry = $customer->expiry_datetime;

            $new_start_datetime = $current_expiry ?? date('Y-m-d H:i:s');
            $new_start_datetime = Carbon::parse($new_start_datetime);
            $new_end_datetime = $new_start_datetime->addDays($package->duration);

            //update customer expiry date
            $customer->expiry_datetime = $new_end_datetime;
            $customer->save();

            //create subscription
            Subscriptions::create([
                'camp_id' => $camp_id,
                'user_id' => $user_id,
                'customer_id' => $customer_id,
                'package_id' => $package_id,
                'paymethod_id' => $paymethod_id,
                'purchaseDate' => $purchased_date,
                'purchaseDateTime' => $purchased_time,
                'subscriptionStartTime'=> $new_start_datetime,
                'subscriptionEndTime' => $new_end_datetime,
                'price' => $price,
                'macAddress' => $customer->mac_address ?? '0',
                'status' => $stat_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer subscription recharged!',
            ]);
        }//has running subscription
        else{
            return response()->json([
                'success' => false,
                'message' => 'Customer does not have running subscription!',
            ]);
        }//else no running subscription
    }

    /*
    * Get subscription by user and date
    * below both methods should use same map function parameters
    * so that we can use same view for both
    */
    public function getSubscriptionByUserDate(Request $request)
    {
        $user_id = $request->input('user_id');
        $camp_id = $request->input('camp_id');
        $search_date = $request->input('search_date');

        $subscriptions = Subscriptions::where('camp_id', $camp_id)
            ->where('user_id', $user_id)
            ->whereDate('purchaseDate', $search_date)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($subs){
                return [
                    'id' => $subs->id,
                    'purchaseDate' => $subs->purchaseDate,
                    'customer_name' => $subs->customer->fullname,
                    'username' => $subs->customer->username,
                    'package_name' => $subs->package->name,
                    'package_duration' => $subs->package->duration,
                    'price' => $subs->price,
                    'expiry_datetime' => $subs->customer->expiry_datetime ?? 'N/A',
                    'status' => $subs->status,
                ];
            });

        return response()->json($subscriptions, 200);
    }//get subscription by user date

    //search subscriptions
    public function searchSubscriptionsByUser(Request $request)
    {
        $user_id = $request->input('user_id');
        $camp_id = $request->input('camp_id');
        $search = $request->input('search');

        $subscriptions = Subscriptions::where('camp_id', $camp_id)
            ->where('user_id', $user_id)
            ->where(function ($query) use ($search) {
                $query->where('purchaseDateTime', 'LIKE', "%$search%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('fullname', 'LIKE', "%$search%")
                            ->orwhere('phone', 'LIKE', "%$search%")
                            ->orwhere('username', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('package', function ($qu) use ($search) {
                        $qu->where('name', 'LIKE', "%$search%")
                            ->orwhere('duration', 'LIKE', "%$search%")
                            ->orwhere('price', 'LIKE', "%$search%");
                    });
            })
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($subs){
                return [
                    'id' => $subs->id,
                    'purchaseDate' => $subs->purchaseDate,
                    'customer_name' => $subs->customer->fullname,
                    'username' => $subs->customer->username,
                    'package_name' => $subs->package->name,
                    'package_duration' => $subs->package->duration,
                    'price' => $subs->price,
                    'expiry_datetime' => $subs->customer->expiry_datetime ?? 'N/A',
                    'status' => $subs->status,
                ];
            });

        return response()->json($subscriptions, 200);
    }//search subscriptions API

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $camp_id = Session::get('active_camp_id');
        $today = date('Y-m-d');

        $subscriptions = Subscriptions::where('camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $camp = Camps::find($camp_id);
        $all_camps = Camps::all();

        //check user client
        $user = auth()->user();
        if ($user->role_id == 4) {
            $client_subs = ClientSubscriptions::where('camp_id', $camp_id)
                ->orderBy('id', 'DESC')
                ->paginate(10);

            return view('Client.client_subscription', compact('client_subs', 'camp'));
        }

        return view('Subscriptions.subscription_view', compact('subscriptions', 'camp', 'all_camps'));
    }

    public function subscriptionSearch(Request $request)
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $all_camps = Camps::all();
        $search = $request->input('subscription_search');

        $subscriptions = Subscriptions::where('camp_id', $camp_id)
            ->where(function ($query) use ($search) {
                $query->where('purchaseDateTime', 'LIKE', "%$search%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('fullname', 'LIKE', "%$search%")
                            ->orwhere('phone', 'LIKE', "%$search%")
                            ->orwhere('username', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('package', function ($qu) use ($search) {
                        $qu->where('name', 'LIKE', "%$search%")
                            ->orwhere('duration', 'LIKE', "%$search%")
                            ->orwhere('price', 'LIKE', "%$search%");
                    });
            })
            ->paginate(10);

        return view('Subscriptions.subscription_view', compact('subscriptions', 'camp', 'search', 'all_camps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $subscription_id = $request->input('subscription_id');

        //hotspot user delete
        $camp_id = Session::get('active_camp_id');
        $camp_data = Camps::find($camp_id);

        $host = $camp_data->mikritikIP;
        $camp_user = $camp_data->mikrotikUsername;
        $camp_password = $camp_data->mikrotikPassword;
        $port = $camp_data->mikritikPort;

        $hotspot = new HotspotUsers($host, $camp_user, $camp_password, $port);

        $subscription = Subscriptions::find($subscription_id);
        $customer_id = $subscription->customer_id;
        $username = $subscription->customer->username;
        $customer = Customers::find($customer_id);

        if ($subscription && $hotspot->isConnected) {
            // Delete the subscription
            $subscription->delete();

            try {
                // Delete hotspot user
                $hotspot->deleteHotspotUser($username);
            } catch (\Exception $e) {
                echo "Error deleting hotspot user: " . $e->getMessage();
            }

            // Update customer expiry date
            $customer->expiry_datetime = null; // Set to null or any default value
            $customer->save();

            return redirect()->route('subscription.show')->with('success', 'Subscription removed successfully');
        }
        return redirect()->route('subscription.show')->with('error', 'Subscription removed failed.');
    }

    //cancel subscription
    public function cancelSubscription(Request $request)
    {
        $subscription_id = $request->input('cancel_subscription_id');

        $subscription = Subscriptions::find($subscription_id);

        $camp_id = $subscription->camp_id;

        //change status
        $subscription->status = 5;
        $subscription->save();

        //unbind hotspot user from old camp
        $camp_data = Camps::find($camp_id);
        $host = $camp_data->mikritikIP;
        $camp_user = $camp_data->mikrotikUsername;
        $camp_pwd = $camp_data->mikrotikPassword;
        $port = $camp_data->mikritikPort;

        $hotspot_user = new HotspotUsers($host, $camp_user, $camp_pwd, $port);

        if($subscription->macAddress != '0' || !isEmpty($subscription->macAddress))
        {
            $hotspot_user->unbindMacAddressFromUser($subscription->macAddress);
        }

        return redirect()->route('subscription.show')->with('success', 'Subscription canceled successfully!');
    }//cancel subscription

    //reset mac address
    public function resetMacAddress(Request $request){
        $customer_id = $request->input('reset_customer_id');
        $subscription_id = $request->input('reset_subscription_id');

        $customer = Customers::find($customer_id);
        $subscription = Subscriptions::find($subscription_id);

        $customer->mac_address = '';
        $subscription->macAddress = '';
        $subscription->status = 1; //set to active status

        $customer->save();
        $subscription->save();

        return redirect()->route('subscription.show')->with('success', 'MAC address reset successfully!');
    }//reset mac address

    public function resetMacAddressAPI(Request $request)
    {
        $customer_id = $request->input('reset_customer_id');
        $subscription_id = $request->input('reset_subscription_id');

        $customer = Customers::find($customer_id);
        $subscription = Subscriptions::find($subscription_id);

        $customer->mac_address = '';
        $subscription->macAddress = '';

        $customer->save();
        $subscription->save();

        return response()->json([
            'success' => true,
            'message' => 'MAC address reset successfully!',
        ]);
    }

    //change camp
    public function changeCamp(Request $request)
    {
        $subscription_id = $request->input('hide_change_subscription_id');
        $new_camp_id = $request->input('cmb_camp');

        $subscription = Subscriptions::find($subscription_id);
        $old_camp_id = $subscription->camp_id;

        //change status
        $subscription->status = 4; //transferred
        $subscription->save();

        //customer change camp
        $customer = Customers::find($subscription->customer_id);
        $customer->camp_id = $new_camp_id;
        $customer->save();

        //create new subscription in new camp
        Subscriptions::create([
            'camp_id' => $new_camp_id,
            'user_id' => $subscription->user_id,
            'customer_id' => $subscription->customer_id,
            'package_id' => $subscription->package_id,
            'paymethod_id' => $subscription->paymethod_id,
            'purchaseDate' => $subscription->purchaseDate,
            'purchaseDateTime' => $subscription->purchaseDateTime,
            'subscriptionStartTime'=> $subscription->subscriptionStartTime,
            'subscriptionEndTime' => $subscription->subscriptionEndTime,
            'price' => "0",
            'macAddress' => $subscription->macAddress,
            'status' => 1, //active
        ]);

        //unbind hotspot user from old camp
        $camp_data = Camps::find($old_camp_id);
        $host = $camp_data->mikritikIP;
        $camp_user = $camp_data->mikrotikUsername;
        $camp_pwd = $camp_data->mikrotikPassword;
        $port = $camp_data->mikritikPort;

        $hotspot_user = new HotspotUsers($host, $camp_user, $camp_pwd, $port);

        if($subscription->macAddress != '0' || !isEmpty($subscription->macAddress))
        {
            $hotspot_user->unbindMacAddressFromUser($subscription->macAddress);
        }

        return redirect()->route('subscription.show')->with('success', 'Camp changed successfully!');

    }//change camp

    //receipt print
    public function receiptPrint(Request $request)
    {
        $subscription_id = $request->query('subscription_id');

        $subscription = Subscriptions::find($subscription_id);

        return view('Receipts.receipt01', compact('subscription'));
    }

    public function getOneSubscription(Request $request)
    {
        $subscription_id = $request->input('subscription_id');

        $subscription = Subscriptions::find($subscription_id);

        return response()->json([
            'success' => true,
            'subscription_id' => $subscription->id,
            'camp_id'=> $subscription->camp_id,
            'camp_name' => $subscription->camp->name,
            'customer_id' => $subscription->customer_id,
            'customer_name' => $subscription->customer->fullname,
            'username' => $subscription->customer->username,
            'package_name' => $subscription->package->name,
            'package_price' => $subscription->price,
            'purchase_date' => $subscription->purchaseDate,
            'start_datetime' => $subscription->subscriptionStartTime ?? 'N/A',
            'end_datetime' => $subscription->subscriptionEndTime ?? 'N/A',
            'expiry_datetime' => $subscription->customer->expiry_datetime ?? 'N/A',
            'mac_address' => $subscription->macAddress,
            'package_duration' => $subscription->package->duration,
            'status' => $subscription->status,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $subscription_id = $request->input('hide_subscription_id');
        $status_id = $request->input('cmb_status');

        $subscription = Subscriptions::find($subscription_id);

        $subscription->status = $status_id;

        $subscription->save();

        return response()->json([
            'success' => true,
            'message' => 'Successfully status changed',
        ]);
    } //update status

    public function changeStatusSubscription(Request $request)
    {
        $subscription_id = $request->input('status_subscription_id');
        $status_id = $request->input('cmb_status');

        $subscription = Subscriptions::find($subscription_id);

        $subscription->status = $status_id;

        $subscription->save();

        return redirect()->route('subscription.show')->with('success', 'Subscription status changed successfully!');
    }//change status subscription

    public function getSubscriptionByCounter(Request $request)
    {
        $counter_id = $request->input('counter_id');

        $total = Subscriptions::where('counter_id', $counter_id)
            ->sum('price');

        $count = Subscriptions::where('counter_id', $counter_id)
            ->count('id');

        return response()->json([
            'success' => true,
            'invoice_count' => $count,
            'total' => $total,
        ]);
    }

    public function getSubscriptionByCustomer(Request $request)
    {
        $customer_id = $request->input('customer_id');

        $subs = Subscriptions::join('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->where('customer_id', $customer_id)
            ->select('packages.name', 'packages.duration', 'subscriptions.purchaseDate', 'subscriptions.subscriptionStartTime', 'subscriptions.subscriptionEndTime', 'subscriptions.price', 'subscriptions.status')
            ->orderBy('subscriptions.id', 'DESC')
            ->limit(10)
            ->get();

        return response()->json($subs, 200);
    }

    public function getRunningSubscriptionByCustomer(Request $request)
    {
        $customer_id = $request->input('customer_id');

        $running_sub = Subscriptions::where('customer_id', $customer_id)
            ->where('status', 2)
            ->orderBy('id', 'DESC')
            ->first();

        return response()->json($running_sub);
    }//get running subscription

    /*
    * get API methods here
    */
    //get one subscription by id
    public function getOneSubscriptionAPI(Request $request){
        $subscription_id = $request->input('subscription_id');

        $subscription = Subscriptions::find($subscription_id);

        if ($subscription) {
            return response()->json([
                'success' => true,
                'subscription' => [
                    'id' => $subscription->id,
                    'customer_id' => $subscription->customer_id,
                    'customer_name' => $subscription->customer->fullname,
                    'username' => $subscription->customer->username,
                    'package_name' => $subscription->package->name,
                    'package_price' => $subscription->price,
                    'purchase_date' => $subscription->purchaseDate,
                    'start_datetime' => $subscription->subscriptionStartTime ?? 'N/A',
                    'end_datetime' => $subscription->subscriptionEndTime ?? 'N/A',
                    'expiry_datetime' => $subscription->customer->expiry_datetime ?? 'N/A',
                    'mac_address' => $subscription->macAddress,
                    'package_duration' => $subscription->package->duration,
                    'status' => $subscription->status,
                ],
            ]);
        }
        return response()->json(['success' => false, 'message' => 'Subscription not found'], 404);
    }

    /*
    * get chart data
    */
    //get donut chart data
    public function getDonutChartData(Request $request)
    {
        $camp_id = $request->input('camp_id');
        $user_id = $request->input('user_id');
        $today = $request->input('search_date') ?? date('Y-m-d');

        $subscriptions = Subscriptions::where('camp_id', $camp_id)
            ->where('user_id', $user_id)
            ->where('purchaseDate', $today)
            ->groupBy('package_id')
            ->selectRaw('package_id, COUNT(*) as count, SUM(price) as total_price')
            ->with(['package' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();

        //generate color
        $colors = [];
        $values = [];
        $titles = [];
        foreach ($subscriptions as $subscription) {
            $values[] = $subscription->total_price;
            $titles[] = $subscription->package->name;
            $colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }
        $data = [
            'values' => $values,
            'colors' => $colors,
            'titles' => $titles,
        ];

        return response()->json($data);
    }//get donut chart data

    //get bar chart data
    public function getBarChartData(Request $request){
        $camp_id = $request->input('camp_id');
        $user_id = $request->input('user_id');

        //get last 7 days sales data
        $sale_data = Subscriptions::where('camp_id', $camp_id)
            ->where('user_id', $user_id)
            ->groupBy('purchaseDate')
            ->selectRaw('purchaseDate, SUM(price) as total_price')
            ->orderBy('purchaseDate', 'DESC')
            ->limit(7)
            ->get();

        $dates = [];
        $total_prices = [];
        foreach ($sale_data as $data) {
            $month_num = substr($data->purchaseDate, 5, 2) ?? '01'; // Default to '01' if month is null
            $day_num = substr($data->purchaseDate, 8, 2) ?? '01'; // Default to '01' if day is null
            $dates[] = $month_num .'/'. $day_num; // Use formatted date as title


            // $dates[] = $data->purchaseDate;
            $total_prices[] = $data->total_price ?? 0; // Use null coalescing operator to handle null values


        }

        $dates = array_reverse($dates);
        $total_prices = array_reverse($total_prices);

        $data = [
            'dates' => $dates,
            'total_prices' => $total_prices,
        ];

        return response()->json($data);
    }
}//class
