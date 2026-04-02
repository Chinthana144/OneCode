<?php

namespace App\Http\Controllers;

use App\Models\AccessPlanes;
use App\Models\Camps;
use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        $today = date('Y-m-d');
        $this_year = date('Y');
        $this_month = date('m');

        //daily subs
        $daily_invoice_total = AccessPlanes::where('camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->sum('price');

        //daily invoice count
        $daily_invoice_count = AccessPlanes::where('camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->count('id');

        //monthly sale
        $monthly_invoice_sale = AccessPlanes::where('camp_id', $camp_id)
            ->whereYear('purchaseDate', $this_year)
            ->whereMonth('purchaseDate', $this_month)
            ->sum('price');

        //running users
        $running_users = AccessPlanes::where('camp_id', $camp_id)
            ->where('status', 2)
            ->count('id');

        //if client, redirect to client dashboard
        $user = auth()->user();
        if ($user->role_id == 4) {
            return redirect()->route('client.dashboard');
        }

        return view('home', compact('camp', 'daily_invoice_total', 'daily_invoice_count', 'monthly_invoice_sale', 'running_users'));
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
        //
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

    //======================= Ajax Functions ====================//
    public function getBarchartData(Request $request)
    {
        $date_range = $request->input('date_range');

        $camp_id = Session::get('active_camp_id');
        $dates = AccessPlanes::where('camp_id', $camp_id)
            ->selectRaw('DATE(purchaseDate) as date')
            ->distinct()
            ->orderByDesc('date')
            ->limit($date_range)
            ->pluck('date');

        $salesData = $dates->map(function ($date) {
            $camp_id = Session::get('active_camp_id');

            $subscription_total = AccessPlanes::where('camp_id', $camp_id)
                ->whereDate('purchaseDate', $date)
                ->where('accessable_type', 'App\Models\Subscriptions')
                ->sum('price');

            $voucher_total = AccessPlanes::where('camp_id', $camp_id)
                ->whereDate('purchaseDate', $date)
                ->where('accessable_type', 'App\Models\Vouchers')
                ->sum('price');

            return [
                'date' => $date,
                'subscription_total' => $subscription_total,
                'voucher_total' => $voucher_total,
            ];
        });

        $salesData = $salesData->reverse()->values();

        return response()->json($salesData);
    }

    public function getDonutchartData(Request $request)
    {
        $date_range = $request->input('date_range');

        $camp_id = Session::get('active_camp_id');
        $today = date('Y-m-d');

        $packages = DB::table('access_planes')
            ->join('packages', 'access_planes.package_id', '=', 'packages.id')
            ->select('packages.name as package_name', DB::raw('SUM(access_planes.price) as total_sales'))
            ->where('access_planes.camp_id', $camp_id)
            ->whereDate('access_planes.purchaseDate', $today)
            ->groupBy('packages.name')
            ->get();

        return response()->json($packages);
    }

    public function getLineChartData(Request $request)
    {
        $date_range = $request->input('date_range');
        $camp_id = Session::get('active_camp_id');

        $dates = AccessPlanes::where('camp_id', $camp_id)
            ->selectRaw('DATE(purchaseDate) as date')
            ->distinct()
            ->orderByDesc('date')
            ->limit($date_range)
            ->pluck('date');

        $salesData = $dates->map(function ($date) {
            $camp_id = Session::get('active_camp_id');

            $total = AccessPlanes::where('camp_id', $camp_id)
                ->whereDate('purchaseDate', $date)
                ->sum('price');

            return [
                'date' => $date,
                'total' => $total,
            ];
        });
        $salesData = $salesData->reverse()->values();

        return response()->json($salesData);
    }//get line total

    public function getPieChartData()
    {
        $camp_id = Session::get('active_camp_id');
        $today = date('Y-m-d');

        $subscription_sale = AccessPlanes::where('camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->where('accessable_type', 'App\Models\Subscriptions')
            ->sum('price');

        $voucher_sale = AccessPlanes::where('camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->where('accessable_type', 'App\Models\Vouchers')
            ->sum('price');

        return response()->json([
            'data' => [floatval($subscription_sale), floatval($voucher_sale)],
            'labels' => ['Subscriptions', 'Vouchers'],
        ]);
    }
}//class
