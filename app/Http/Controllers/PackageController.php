<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Models\Customers;
use App\Models\CustomerType;
use App\Models\Packages;
use App\Services\UserProfiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $active_camp_id = Session::get('active_camp_id');
        $packages = Packages::where('camp_id', $active_camp_id)
            ->where('status', 1)
            ->paginate(10);
        $camp = Camps::find($active_camp_id);

        $customer_types = CustomerType::all();

        return view('Packages.packages_view', compact('packages', 'camp', 'customer_types'));
    }

    public function packageSearch(Request $request)
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $customer_types = CustomerType::all();
        $search = $request->input('package_search');

        $packages = Packages::where('camp_id', $camp_id)
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orwhere('duration', 'LIKE', "%$search%")
                    ->orwhere('price', 'LIKE', "%$search%")
                    ->orWhereHas('CustomerType', function ($q) use ($search) {
                        $q->where('customerType', 'LIKE', "%$search%");
                    });
            })
            ->paginate(10);

        return view('Packages.packages_view', compact('packages', 'camp', 'customer_types', 'search'));
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
        $active_camp_id = Session::get('active_camp_id');
        $validated = $request->validate([
            'cmb_customer_type' => 'required|integer',
            'name' => 'required|max:40',
            'duration' => 'required|integer',
            'price' => 'required|numeric',
            'bandwidth' => 'required|max:6',
            'downloadlimit' => 'required|max:6',
            'uploadlimit' => 'required|max:6',
        ]);

        $stat = $request->has('chk_package_stat') ? 1 : 0;

        Packages::create([
            'camp_id' => $active_camp_id,
            'customerType_id' => $validated['cmb_customer_type'],
            'name' => $validated['name'],
            'duration' => $validated['duration'],
            'price' => $validated['price'],
            'bandwidth' => $validated['bandwidth'],
            'downloadlimit' => $validated['downloadlimit'],
            'uploadlimit' => $validated['uploadlimit'],
            'status' => $stat,
        ]);

        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
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
        $active_camp_id = Session::get('active_camp_id');

        $package_id = $request->input('hide_package_id');

        $package = Packages::find($package_id);
        $old_name = $package->name;

        $package->customerType_id = $request->input('cmb_customer_type');
        $package->name = $request->input('name');
        $package->duration = $request->input('duration');
        $package->price = $request->input('price');
        $package->bandwidth = $request->input('bandwidth');
        $package->downloadlimit = $request->input('downloadlimit');
        $package->uploadlimit = $request->input('uploadlimit');
        $package->status = $request->has('chk_package_stat') ? 1 : 0;

        $package->save();

        return redirect()->route('packages.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //ajax methods
    public function getCustomerPackages(Request $request)
    {
        $camp_id = $request->input('camp_id');
        $customer_id = $request->input('customer_id');
        $customer = Customers::find($customer_id);
        $customer_type_id = $customer->customerType_id;

        $packages = Packages::where('camp_id', $camp_id)
            ->where('customerType_id', $customer_type_id)
            ->where('status', 1)
            ->get();

        return response()->json($packages);
    }

    public function getOnePackage(Request $request)
    {
        $package_id = $request->input('package_id');

        $package = Packages::find($package_id);

        return response()->json($package);
    } //get one package
}
