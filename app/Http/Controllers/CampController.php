<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Models\CampUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;

class CampController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $camps = Camps::all();

        return view('camps.camp_view', compact('camps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('camps.camp_add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $camp = new Camps();

        $camp->name = $request->input('name');
        $camp->location = $request->input('location');
        $camp->contactPerson = $request->input('contactPerson');
        $camp->contactPhone = $request->input('contactPhone');
        $camp->contactEmail = $request->input('contactEmail');
        $camp->mikritikIP = $request->input('mikritikIP');
        $camp->mikritikPort = $request->input('mikritikPort');
        $camp->mikrotikUsername = $request->input('mikrotikUsername');
        $camp->mikrotikPassword = $request->input('mikrotikPassword');
        $camp->radiusSecret = $request->input('radiusSecret');
        $camp->radiusIP = $request->input('radiusIP');
        $camp->monthly_target = $request->input('monthly_target');
        $camp->status = $request->has('chk_camp_stat') ? 1 : 0;

        $camp->save();

        return redirect()->route('camps.index');
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
        $camp_id = $request->input('hide_camp_id');
        $camp = Camps::find($camp_id);

        return view('camps.camp_edit', compact('camp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $camp_id = $request->input('camp_id');
        $camp = Camps::find($camp_id);

        $camp->name = $request->input('name');
        $camp->location = $request->input('location');
        $camp->contactPerson = $request->input('contactPerson');
        $camp->contactPhone = $request->input('contactPhone');
        $camp->contactEmail = $request->input('contactEmail');
        $camp->mikritikIP = $request->input('mikritikIP');
        $camp->mikritikPort = $request->input('mikritikPort');
        $camp->mikrotikUsername = $request->input('mikrotikUsername');
        $camp->mikrotikPassword = $request->input('mikrotikPassword');
        $camp->radiusSecret = $request->input('radiusSecret');
        $camp->radiusIP = $request->input('radiusIP');
        $camp->monthly_target = $request->input('monthly_target');
        $camp->status = $request->has('chk_camp_stat') ? 1 : 0;

        $camp->save();

        return redirect()->route('camps.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //camp portal
    public function campPortal()
    {
        $user_id = Auth::id();
        $allowed_camps = CampUsers::where('user_id', $user_id)->get();

        return view('camps.camp_portal', compact('allowed_camps'));
    }

    public function select(Request $request)
    {
        $camp_id = $request->route('camp_id');
        Session::put('active_camp_id', $camp_id);

        $user = Auth::user();
        //if user is a client, redirect to client dashboard
        if ($user->role_id == 4) {
            return redirect()->route('client.dashboard');
        }

        if(Gate::allows('access-home')) {
            return redirect()->route('dashboard.home');
        }
        else{
            return redirect()->route('users.profile');
        }
    }//select
}
