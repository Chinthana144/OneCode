<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Models\CampUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $camps = DB::table('camps')->where('status', '1')->get();
        $users = User::all();
        $campusers = CampUsers::all();

        return view('CampUsers.campusers_view', compact('camps', 'users', 'campusers'));
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
        $camp_id = $request->input('cmb_camps');
        $user_id = $request->input('cmb_users');

        $exists = CampUsers::where('camp_id', $camp_id)
            ->where('user_id', $user_id)
            ->exists();

        if ($exists) {
            return redirect()->route('campusers.index');

            session()->flash('failed', 'camp user already added!');
        } else {
            CampUsers::create([
                'camp_id' => $camp_id,
                'user_id' => $user_id,
            ]);

            session()->flash('success', 'Shop added successfully!');
            return redirect()->route('campusers.index');
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
    public function update(Request $request)
    {
        $campuser_id = $request->input('hide_campuser_id');
        $camp_id = $request->input('cmb_edit_camps');
        $user_id = $request->input('cmb_edit_users');

        $exists = CampUsers::where('camp_id', $camp_id)
            ->where('user_id', $user_id)
            ->exists();

        if ($exists) {
            return redirect()->route('campusers.index');

            session()->flash('failed', 'camp user already added!');
        } else {
            $campuser = CampUsers::find($campuser_id);

            $campuser->camp_id = $camp_id;
            $campuser->user_id = $user_id;

            $campuser->save();

            session()->flash('success', 'Shop added successfully!');
            return redirect()->route('campusers.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $campuser_id = $request->input('hide_campuser_id');

        $campuser = CampUsers::find($campuser_id);
        $campuser->delete();

        return redirect()->route('campusers.index');
    }

    //ajax methods
    public function getOneCampuser(Request $request)
    {
        $campuser_id = $request->campuser_id;

        $campuser = CampUsers::find($campuser_id);

        return response()->json(['success' => true, 'message' => 'show one camp user', 'data' => $campuser]);
    }
}
