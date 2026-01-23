<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Models\Customers;
use App\Models\Subscriptions;
use App\Models\CustomerType;
use App\Models\Packages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $camp_id = Session::get('active_camp_id');
        $customers = Customers::where('camp_id', $camp_id)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->paginate(10);
        $customer_types = CustomerType::all();
        $camp = Camps::find($camp_id);
        $camps = Camps::where('status', 1)->get();

        return view('Customers.customer_view', compact('customers', 'camp', 'camps', 'customer_types'));
    }

    public function customerSearch(Request $request)
    {
        $camp_id = Session::get('active_camp_id');
        $camps = Camps::where('status', 1)->get();
        $customer_types = CustomerType::all();
        $camp = Camps::find($camp_id);
        $search = $request->input('customer_search');

        $customers = Customers::where(function ($query) use ($search) {
            $query->where('fullname', 'LIKE', "%$search%")
                ->orwhere('phone', 'LIKE', "%$search%")
                ->orwhere('email', 'LIKE', "%$search%")
                ->orwhere('username', 'LIKE', "%$search%");
        })->paginate(10);

        return view('Customers.customer_view', compact('customers', 'search', 'camp', 'camps', 'customer_types'));
    } //search customers

    //get customers for api
    public function getCustomersByCamp(Request $request)
    {
        $camp_id = $request->input('camp_id');
        $customers = Customers::where('camp_id', $camp_id)
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        return response()->json($customers);
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
        $camp_id  = $request->input('cmb_camp');
        $customer_type_id = $request->input('cmb_customer_type');
        $fullname = $request->input('fullname');
        $phone = $request->input('phone');
        $email = $request->input('email') ?? '';
        $username = $request->input('username') ?? $phone; //default username is phone
        $password = $request->input('password');

        $stat = $request->has('chk_customer_stat') ? 1 : 0;

        $exists = Customers::where('username', $username)->exists();

        if ($exists) {
            return redirect()->route('customer.index');
            session()->flash('failed', 'camp user already added!');
        } else {
            Customers::create([
                'camp_id' => $camp_id,
                'customerType_id' => $customer_type_id,
                'fullname' => $fullname,
                'phone' => $phone,
                'email' => $email,
                'username' => $username,
                'password' => $password,
                'status' => $stat,
            ]);
            session()->flash('success', 'Customer added successfully!');
            return redirect()->route('customer.index');
        }
    }

    public function customerRegister(Request $request)
    {
        $default_camp_id = $request->input('camp_id') == null ? 1 : $request->input('camp_id');//default camp id
        $customer_type_id = 1; //labor
        $customer_name = $request->input('customer_name');
        $contact_no = $request->input('contact_no');
        $customer_pwd = $request->input('customer_pwd');
        $customer_email = "";
        $username = $contact_no;
        $status = 1; //active

        $exists = Customers::where('username', $username)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This contact number is already registered. Please use a different phone number.',
            ]);
        } else {
            Customers::create([
                'camp_id' =>  $default_camp_id,
                'customerType_id' => $customer_type_id,
                'fullname' => $customer_name,
                'phone' => $contact_no,
                'email' => $customer_email,
                'username' => $username,
                'password' => $customer_pwd, // Hash the password
                'status' => $status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer registered successfully!',
            ]);

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
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $customer_id = $request->input('hide_customer_id');

        //check duplicate username
        $exists = Customers::where('username', $request->input('edit_username'))
            ->where('id', '!=', $customer_id) //exclude current customer
            ->exists();
        if ($exists) {
            session()->flash('error', 'Username already exists!');
            return redirect()->route('customer.index');
        }

        $customer = Customers::find($customer_id);

        $customer->camp_id = $request->input('cmb_edit_camp');
        $customer->customerType_id = $request->input('cmb_edit_customer_type');
        $customer->fullname = $request->input('edit_fullname');
        $customer->phone = $request->input('edit_phone');
        $customer->email = $request->input('edit_email') ?? '';
        $customer->username = $request->input('edit_username') ?? $request->input('edit_phone'); //default username is phone
        $customer->password = $request->input('edit_password');
        $customer->status = $request->has('chk_edit_customer_stat') ? 1 : 0;

        $customer->save();

        session()->flash('success', 'Customer updated successfully!');
        return redirect()->route('customer.index');
    }

    //update expire date
    public function updateExpireDate(Request $request)
    {
        $customer_id = $request->input('hide_customer_id');
        $expire_date = $request->input('expire_date');
        $expire_time = $request->input('expire_time');

        //subscription
        $subscription_id = $request->input('hide_subscription_id');
        $subscription = Subscriptions::find($subscription_id);

        // dd($expire_date, $expire_time);
        $expire_time_formatted = date('H:i:s', strtotime($expire_time));
        $expire_datetime = $expire_date . ' ' . $expire_time_formatted;

         $customer = Customers::find($customer_id);
        // dd($expire_datetime);
        if($customer){
            //update customer expire date
            $customer->expiry_datetime = $expire_datetime;
            $customer->save();

            //update subscription expire date
            $subscription->subscriptionEndTime = $expire_datetime;
            $subscription->save();

            return redirect()->route('subscription.show')->with('success', 'Expire date updated successfully!');
        }
        else{
            return redirect()->route('subscription.show')->with('error', 'Customer not found!');
        }
    }

     //deactivate customer
    public function deactivateCustomer(Request $request){
        $customer_id = $request->input('customer_id');
        $customer = Customers::find($customer_id);

        if ($customer) {
            $customer->status = 0; //deactivate
            $customer->save();

            return redirect()->route('customer.index')->with('success', 'Customer removed successfully!');
        } else {
            return redirect()->route('customer.index')->with('error', 'Customer not found!');
        }
    }// deactivate customer

    //update customer API
    public function updateCustomer(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $customer = Customers::find($customer_id);

        if ($customer) {
            $customer->fullname = $request->input('fullname');
            $customer->phone = $request->input('phone');
            $customer->email = $request->input('email') ?? '';
            $customer->username = $request->input('username') ?? $request->input('phone');
            $customer->password = $request->input('password');
            // $customer->status = $request->has('chk_customer_stat') ? 1 : 0;

            $customer->save();

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ]);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //api search customer
    public function customersWithPackages(Request $request)
    {
        $camp_id = $request->input('camp_id');
        $search = $request->input('search');
        $customer_packages = [];

        $customers = Customers::where('camp_id', $camp_id)
            ->where(function ($query) use ($search) {
                $query->where('username', 'LIKE', "%{$search}%")
                    ->orwhere('fullname', 'LIKE',  "%{$search}%")
                    ->orwhere('phone', 'LIKE',  "%{$search}%");
            })
            ->limit(20)
            ->get();

        foreach ($customers as $customer) {
            $customer_type_id = $customer->customerType_id;

            $packages = Packages::where('camp_id', $camp_id)
                ->where('customerType_id', $customer_type_id)
                ->where('status', 1)
                ->get();

            $customer_packages[] = [
                'customer' => $customer,
                'packages' => $packages,
            ];
        }

        return response()->json($customer_packages);

    }//search customers

    //get search customers
    public function searchCustomers(Request $request)
    {
        $camp_id = $request->input('camp_id');
        $search = $request->input('search');

        $customers = Customers::where('camp_id', $camp_id)
            ->where(function ($query) use ($search) {
                $query->where('username', 'LIKE', "%{$search}%")
                    ->orwhere('fullname', 'LIKE',  "%{$search}%")
                    ->orwhere('phone', 'LIKE',  "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($customers);

    }//search customers

    //ajax methods
    public function getCustomers(Request $request)
    {
        $camp_id = Session::get('active_camp_id');
        $search = $request->input('q');

        $customer = Customers::where('camp_id', $camp_id)
            ->where(function ($query) use ($search) {
                $query->where('username', 'LIKE', "%{$search}%")
                    ->orwhere('fullname', 'LIKE',  "%{$search}%")
                    ->orwhere('phone', 'LIKE',  "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($customer);
    }

    public function getOneCustomer(Request $request)
    {
        $customer_id = $request->input('id');
        $customer = Customers::find($customer_id);

        return response()->json($customer);
    }

    //get customer types
    public function getCustomerTypes()
    {
        $customer_types = CustomerType::all();

        return response()->json($customer_types);
    }

    //store customer using AJAX
    public function storeOneCustomer(Request $request)
    {
        $camp_id = Session::get('active_camp_id');

        $customer = new Customers();

        $customer->camp_id = $camp_id;
        $customer->customerType_id = $request->input('cmb_customer_type');
        $customer->fullname = $request->input('fullname');
        $customer->phone = $request->input('phone');
        $customer->email = $request->input('email');
        $customer->username = $request->input('username');
        $customer->password = $request->input('password');
        $customer->status = $request->has('chk_customer_stat') ? 1 : 0;

        $exists = Customers::where('username',  $customer->username)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Customer already exists',
            ]);
        } else {

            $customer->save();

            return response()->json([
                'success' => true,
                'message' => 'Customer added successfully',
            ]);
        }
    }

    public function getCustomerByUsername(Request $request)
    {
        $username = $request->input('username');

        $has_customer = Customers::where('username', $username)
            ->exists();

        return response()->json($has_customer);
    }
}
