<?php

namespace App\Http\Controllers;

use App\Models\AccessPlanes;
use App\Models\Camps;
use App\Models\Customers;
use App\Models\Packages;
use App\Models\Subscriptions;
use App\Models\Vouchers;
use App\Services\HotspotUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use function PHPUnit\Framework\isEmpty;

class WifiLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customer_id = $request->input('id');
        $customer = Customers::find($customer_id);

        $mac = $request->query('mac', null);  // if 'mac' doesn't exist, use null
        $ip = $request->query('ip', null);
        // $username = $request->query('username', null);
        $dst = $request->query('dst', null);

        return view('WifiLogin.customer_dashboard', compact('mac', 'ip', 'dst', 'customer'));
    }

    /*
    * validate customer login
    */
    public function login(Request $request)
    {
        /*
        * find customer
        * if(customer_camp == current_camp){
        * if(has running subscription){check MAC address, bind and unbind, allow login}
        * elseif(has active subscription){Add MAC address, allow login}
        * else{return to index page with error}
        * }

        * else{
        * if(customer_type != labor){assign MAC and allow login}
        * else{return to index page with error}
        * }
        */

        date_default_timezone_set('Asia/Dubai');

        $camp_id = $request->input('camp_id');
        $mac = $request->input('mac');
        $ip = $request->input('ip');
        $link_login = $request->input('link_login');
        $username = $request->input('cust_username');
        $password = $request->input('cust_password');

        //camp data
        $camp_data = Camps::find($camp_id);
        $host = $camp_data->mikritikIP;
        $camp_user = $camp_data->mikrotikUsername;
        $camp_pwd = $camp_data->mikrotikPassword;
        $port = $camp_data->mikritikPort;

        $hotspot_user = new HotspotUsers($host, $camp_user, $camp_pwd, $port);

        //search customer by username
        $customer = Customers::where('username', $username)
            ->where('password', $password)
            ->where('status', 1) //active
            ->first();

        //check customer
        if($customer){
            //check camp
            if($camp_id == $customer->camp_id){
                $customer_id = $customer->id;

                //check running subscriptions
                $running_subscription = Subscriptions::where('customer_id', $customer_id)
                    ->where('status', 2) //running
                    ->whereDate('subscriptionEndTime', '>=', now())
                    ->where('camp_id', $camp_id)
                    ->first();

                //check active subscriptions
                $active_subscription = Subscriptions::where('customer_id', $customer_id)
                    ->where('status', 1) //active
                    ->where('camp_id', $camp_id)
                    ->first();

                if($running_subscription){
                    if($customer->mac_address != $mac){
                        //unbind previous mac address
                        $hotspot_user->unbindMacAddressFromUser($customer->mac_address);

                        //update mac address
                        $running_subscription->macAddress = $mac;
                        $running_subscription->save();

                        //update customer mac address
                        $customer->mac_address = $mac;
                        $customer->save();
                    }

                    //bind new mac address
                    $hotspot_user->bindMacAddressToUser($username, $mac);

                    //redirect uri
                    $redirectUrl = 'https://cloudtik.trizent.net/userlogin?id=' . $customer->id;

                    return redirect($redirectUrl);
                }//running subscription
                elseif($active_subscription){
                    //make it running
                    $active_subscription->status = 2; //running
                    $active_subscription->subscriptionStartTime = now();
                    $active_subscription->subscriptionEndTime = now()->addDays($active_subscription->package->duration);
                    $active_subscription->save();

                    //update customer mac address
                    $customer->login_datetime = now();
                    $customer->expiry_datetime = now()->addDays($active_subscription->package->duration);
                    $customer->mac_address = $mac;
                    $customer->save();

                    //add hotspot user
                    // $hotspot_user->addHotspotUser($username, $mac);

                    //bind mac address to hotspot user
                    $hotspot_user->bindMacAddressToUser($username, $mac);

                    //redirect uri
                    $redirectUrl = 'https://cloudtik.trizent.net/userlogin?id=' . $customer->id;

                    return redirect($redirectUrl);
                }//has active
                else{
                    //no active or running package
                    return redirect()->route('wifilogin.index')
                        ->with('error', 'No active or running package found.');
                }//no acrive or running package
            }//same camp
            else{
                //check customer type
                if($customer->customerType_id == 1){
                    //no access in this camp
                    return redirect()->route('wifilogin.index')
                        ->with('error', 'You don\'t have access in this camp.');
                }//labor
                else{
                    //unbind previous camp mac address
                    $old_camp_id = $customer->camp_id;
                    $old_camp = Camps::find($old_camp_id);

                    $old_host = $old_camp->mikritikIP;
                    $old_camp_user = $old_camp->mikrotikUsername;
                    $old_camp_pwd = $old_camp->mikrotikPassword;
                    $old_port = $old_camp->mikritikPort;

                    $old_hotspot_user = new HotspotUsers($old_host, $old_camp_user, $old_camp_pwd, $old_port);

                    $old_hotspot_user->unbindMacAddressFromUser($customer->mac_address);

                    //change camp for staff
                    $customer->camp_id = $camp_id;

                    //bind mac address for new camp
                    $hotspot_user->bindMacAddressToUser($username, $mac);

                    //redirect uri
                    $redirectUrl = 'https://cloudtik.trizent.net/userlogin?id=' . $customer->id;

                    return redirect($redirectUrl);
                }
            }//different camp
        }//has customer
        else{
            //no customer found or hotspot user not connected
            return redirect()->route('wifilogin.index')
                ->with('error', 'Invalid username or password, or hotspot service is not connected.');
        }//no customer

    }//login

    public function authSubscription(Request $request)
    {
        date_default_timezone_set('Asia/Dubai');

        /*
        * find customer
        * if(customer camp == current camp)
        * {get active access_plan give access to active access_plan only}
        */

        $camp_id = $request->input('camp_id');
        $mac = $request->input('mac');
        $ip = $request->input('ip');
        $link_login = $request->input('link_login');
        $username = $request->input('username');
        $password = $request->input('password');

        //camp data
        $camp_data = Camps::find($camp_id);
        $host = $camp_data->mikritikIP;
        $camp_user = $camp_data->mikrotikUsername;
        $camp_pwd = $camp_data->mikrotikPassword;
        $port = $camp_data->mikritikPort;

        $hotspot_user = new HotspotUsers($host, $camp_user, $camp_pwd, $port);

        //search customer by username
        $customer = Customers::where('username', $username)
            ->where('password', $password)
            ->where('status', 1) //active
            ->first();
        $customer_id = $customer->id;

        if($customer)
        {
            if($customer->camp_id == $camp_id)
            {
                //get subscription
                $accessPlans = AccessPlanes::where('camp_id', $camp_id)
                    ->where('status', 1)
                    ->whereHasMorph(
                        'accessable',
                        [Subscriptions::class],
                        function ($query) use ($customer_id){
                            $query->where('customer_id', $customer_id);
                        }
                    )
                    ->orderBy('id', 'DESC')
                    ->first();

                if($accessPlans)
                {
                    //package
                    $package_id = $accessPlans->package_id;
                    $package = Packages::find($package_id);
                    $duration = $package->duration;

                    $accessPlans->mac_address = $mac;
                    $accessPlans->ip_address = $ip;
                    $accessPlans->login_at ??= Carbon::now();
                    $accessPlans->expire_at ??= Carbon::now()->addDays($duration);
                    $accessPlans->status = 2;

                    $accessPlans->save();

                    //bind mac address
                    // $hotspot_user->bindMacAddressToUser($username, $mac);

                    //create hotspot user
                    $hotspot_user->addHotspotUser($username, $password, $mac);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Login successful.',
                        'login_datetime' => $accessPlans->login_at,
                        'expiry_datetime' => $accessPlans->expire_at,
                    ]);
                }//has access plan
                else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No active subscription found, please contact Sales Person.',
                    ]);
                }//no active plan
            }//same camp
            else
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please login with correct camp login page.',
                ]);
            }//wrong camp
        }//has customer
        else{
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password, please try again.',
            ]);
        }//not customer
    }//auth subscription

    public function authVoucher(Request $request)
    {
        date_default_timezone_set('Asia/Dubai');

        $camp_id = $request->input('camp_id');
        $mac = $request->input('mac');
        $ip = $request->input('ip');
        // $link_login = $request->input('link_login');

        $code = $request->input('code');

        //camp data
        $camp_data = Camps::find($camp_id);
        $host = $camp_data->mikritikIP;
        $camp_user = $camp_data->mikrotikUsername;
        $camp_pwd = $camp_data->mikrotikPassword;
        $port = $camp_data->mikritikPort;

        $hotspot_user = new HotspotUsers($host, $camp_user, $camp_pwd, $port);

        /*
        * find voucher in database voucher table
        * check expire date
        * find access plan from voucher id
        * get mac address, bind mac address
        * change access plan status to 2
        * find package from package_id => get duration
        * add login and expire date time
        * save everything
        */
        $today = now();
        $current_expire_date = now()->addDays(90);
        $voucher = Vouchers::where('code', $code)
            ->whereDate('expire_date', '<=', $current_expire_date)
            ->where('status', 1)
            ->first();

        if($voucher){
            //find access plan
            $access_plan = AccessPlanes::where('accessable_type', Vouchers::class)
                ->where('accessable_id', $voucher->id)
                ->first();

            //get package
            $package = Packages::find($access_plan->package_id);
            $duration = $package->duration;

            $login_at = now();
            $expire_at = now()->addDays($duration);

            $access_plan->login_at ??= $login_at;
            $access_plan->expire_at ??= $expire_at;
            $access_plan->mac_address = $mac;
            $access_plan->ip_address = $ip;
            $access_plan->status = 2;

            $access_plan->save();

            //create hotspot user
            $hotspot_user->addHotspotUser($code, $code, $mac);

            //voucher status
            $voucher->status = 2;
            $voucher->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'login_datetime' => $access_plan->login_at,
                'expiry_datetime' => $access_plan->expire_at,
            ]);
        }//has voucher
        else{
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect voucher code or voucher is expired, please try again.',
            ]);
        }//no voucher
    }//auth voucher

    //use this login in future
    public function basicLogin(Request $request){
       date_default_timezone_set('Asia/Dubai');

        $camp_id = $request->input('camp_id');
        $mac = $request->input('mac');
        $ip = $request->input('ip');
        $link_login = $request->input('link_login');
        $username = $request->input('cust_username');
        $password = $request->input('cust_password');

        //camp data
        $camp_data = Camps::find($camp_id);
        $host = $camp_data->mikritikIP;
        $camp_user = $camp_data->mikrotikUsername;
        $camp_pwd = $camp_data->mikrotikPassword;
        $port = $camp_data->mikritikPort;

        $hotspot_user = new HotspotUsers($host, $camp_user, $camp_pwd, $port);

        //search customer by username
        $customer = Customers::where('username', $username)
            ->where('password', $password)
            ->where('status', 1) //active
            ->first();

        if($customer){
            //check camp
            if($camp_id == $customer->camp_id){
                $customer_id = $customer->id;

                //check running subscriptions
                $running_subscription = Subscriptions::where('customer_id', $customer_id)
                    ->where('status', 2) //running
                    ->whereDate('subscriptionEndTime', '>=', now())
                    ->where('camp_id', $camp_id)
                    ->first();

                //check active subscriptions
                $active_subscription = Subscriptions::where('customer_id', $customer_id)
                    ->where('status', 1) //active
                    ->where('camp_id', $camp_id)
                    ->first();

                if($running_subscription){
                    if($customer->mac_address == '' || isEmpty($customer->mac_address)){
                        //re assign mac address
                        //update mac address
                        $running_subscription->macAddress = $mac;
                        $running_subscription->save();

                        //update customer mac address
                        $customer->mac_address = $mac;
                        $customer->save();

                        //bind new mac address
                        $hotspot_user->bindMacAddressToUser($username, $mac);

                        //redirect uri
                        $redirectUrl = 'https://cloudtik.trizent.net/userlogin?id=' . $customer->id;
                        return redirect($redirectUrl);
                    }//no
                    elseif($customer->mac_address == $mac){
                        //bind new mac address
                        $hotspot_user->bindMacAddressToUser($username, $mac);

                        //redirect uri
                        $redirectUrl = 'https://cloudtik.trizent.net/userlogin?id=' . $customer->id;
                        return redirect($redirectUrl);
                    }
                    else{
                        return redirect()->back()->with('error', 'Please reset your subscription.');
                    }//mac address un match
                }//has running subscription
                elseif($active_subscription){
                    //make it running
                    $active_subscription->status = 2; //running
                    $active_subscription->subscriptionStartTime = now();
                    $active_subscription->subscriptionEndTime = now()->addDays($active_subscription->package->duration);
                    $active_subscription->save();

                    //update customer mac address
                    $customer->login_datetime = now();
                    $customer->expiry_datetime = now()->addDays($active_subscription->package->duration);
                    $customer->mac_address = $mac;
                    $customer->save();

                    //bind mac address to hotspot user
                    $hotspot_user->bindMacAddressToUser($username, $mac);

                    //redirect uri
                    $redirectUrl = 'https://cloudtik.trizent.net/userlogin?id=' . $customer->id;

                    return redirect($redirectUrl);
                }//active package
                else{
                    return redirect()->back()->with('error', 'No subscription found');
                }//no active or running subscription
            }//has camp
            else{
                return redirect()->back()->with('error', 'Please login with correct camp login page');
            }
        }//has customer
        else{
            return redirect()->back()->with('error', 'Invalid username or password, or hotspot service is not connected.');
        }//no customer

    }//login 1 method

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
        $camp_id = $request->input('camp_id', 1); //default camp id 1
        $customer_type_id = 1; //labor
        $customer_name = $request->input('customer_name');
        $contact_no = $request->input('contact_no');
        $customer_pwd = $request->input('customer_pwd');
        $customer_email = "";
        $username = $contact_no;
        $status = 1; //active

        $exists = Customers::where('username', $username)->exists();

        if ($exists) {
            return redirect()->route('wifilogin.register')
                ->with('error', 'This phone number is already registered. Please use a different phone number.');
        } else {
            Customers::create([
                'camp_id' =>  $camp_id,
                'customerType_id' => $customer_type_id,
                'fullname' => $customer_name,
                'phone' => $contact_no,
                'email' => $customer_email,
                'username' => $username,
                'password' => $customer_pwd,
                'status' => $status,
            ]);
            session()->flash('success', 'Shop added successfully!');
            return redirect()->route('wifilogin.index')
                ->with('success', 'You have successfully registered. Please login to continue.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }

    public function register(Request $request){
        $camp_id = $request->input('camp_id', 1); //default camp id 1
        $camp = Camps::find($camp_id);

        return view('WifiLogin.customer_register', compact('camp'));
    }
}
