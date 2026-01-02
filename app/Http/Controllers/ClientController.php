<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Models\ClientSubscriptions;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    public function dashboard()
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        $today = date('Y-m-d');
        $this_year = date('Y');
        $this_month = date('m');

        //monthly sale
        $monthly_subs_sale = ClientSubscriptions::where('camp_id', $camp_id)
            ->whereYear('purchaseDateTime', $this_year)
            ->whereMonth('purchaseDateTime', $this_month)
            ->sum('price');

        //monthly invoice count
        $monthly_subs_count = ClientSubscriptions::where('camp_id', $camp_id)
            ->whereYear('purchaseDateTime', $this_year)
            ->whereMonth('purchaseDateTime', $this_month)
            ->count('id');

        //total customers count
        $total_customers = Customers::where('camp_id', $camp_id)
            ->where('status', 1) // Assuming status 1 means active customers
            ->count('id');

        // Logic for client dashboard
        return view('Client.client_analysis', compact('camp', 'monthly_subs_sale', 'monthly_subs_count', 'total_customers'));
    }

    public function getAnalysisBarchartData(Request $request)
    {
        $date_range = $request->input('date_range');

        $camp_id = Session::get('active_camp_id');
        $dates = ClientSubscriptions::where('camp_id', $camp_id)
            ->selectRaw('DATE(purchaseDate) as date')
            ->distinct()
            ->orderByDesc('date')
            ->limit($date_range)
            ->pluck('date');

        $salesData = $dates->map(function ($date) {
            $camp_id = Session::get('active_camp_id');

            $total = ClientSubscriptions::where('camp_id', $camp_id)
                ->whereDate('purchaseDate', $date)
                ->sum('price');
            return [
                'date' => $date,
                'total' => $total,
            ];
        });

        $salesData = $salesData->reverse()->values();

        return response()->json($salesData);
    }

}//class
