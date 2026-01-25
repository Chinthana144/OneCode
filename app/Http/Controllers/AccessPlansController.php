<?php

namespace App\Http\Controllers;

use App\Models\AccessPlanes;
use Illuminate\Http\Request;

class AccessPlansController extends Controller
{
    public function index()
    {
        $access_plans = AccessPlanes::where('purchaseDate', date('Y-m-d'))->paginate(10);

        return view('AccessPlans.access_plans_view', compact('access_plans'));
    }
}//class
