<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Models\CampUsers;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $roles = Roles::all();

        return view('Users.users-list', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /*
    * login for API authentication
    */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user_camps = CampUsers::where('user_id', $user->id)->get();

        $camps = [];

        foreach ($user_camps as $camp_user) {
            $camps[] = [
                'camp_id' => $camp_user->camp_id,
                'camp_name' => $camp_user->camps->name,
                'camp_location' => $camp_user->camps->location,
                'user_id' => $camp_user->user_id,
                'user_name' => $camp_user->users->name,
            ];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
            'user_camps' => $camps,
        ]);
    }

    //logout
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //data
        $username = $request->input('name');
        $email = $request->input('email');
        //check duplicates
        $exists = User::where('name', $username)
            ->orwhere('email', $email)
            ->exists();

        if ($exists) {
            return redirect()->route('users.index');
        } else {
            $password = Hash::make($request->input('password'));
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $password;
            $user->role_id = $request->input('cmb_role');

            $user->save();

            return redirect()->route('users.index');
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
        $user_id = $request->input('hide_edit_user_id');

        $user = User::find($user_id);

        $user->name = $request->input('edit_name');
        $user->email = $request->input('edit_email');
        $user->role_id = $request->input('cmb_role');

        $user->save();

        return redirect()->route('users.index');
    }

    public function update_pwd(Request $request)
    {
        $user_id = $request->input('hide_user_id');
        $route = $request->input('hide_route');

        $new_password = Hash::make($request->input('re_password'));

        $user = User::find($user_id);

        $user->password = $new_password;

        $user->save();

        if ($route == 'user') {
            return redirect()->route('users.index');
        }
        if ($route == 'profile') {
            return redirect()->route('users.profile')->with('success', 'Password changed successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function userProfile(){
        $user = auth()->user();
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        return view('Users.user-profile', compact('user', 'camp'));
    }

    //ajax methods
    public function getOneUser(Request $request)
    {
        $user_id = $request->input('user_id');

        $user = User::find($user_id);

        return response()->json($user);
    }
}
