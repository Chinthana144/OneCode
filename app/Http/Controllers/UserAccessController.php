<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Models\PageAccess;
use App\Models\Pages;
use App\Models\User;
use Illuminate\Http\Request;

class UserAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $camps = Camps::all();
        $pages = Pages::all();
        $user_accesses = PageAccess::paginate(10);
        return view('UserAccess.user_access', compact('user_accesses', 'users', 'camps', 'pages'));
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
        $page_id = $request->input('cmb_pages');

        $has_create = $request->input('chk_create') ? 1 : 0;
        $has_view = $request->input('chk_view') ? 1 : 0;
        $has_edit = $request->input('chk_edit') ? 1 : 0;
        $has_delete = $request->input('chk_delete') ? 1 : 0;

        //check if the user already has access to this page in the camp
        $existing_access = PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user_id)
            ->where('page_id', $page_id)
            ->first();
        if ($existing_access) {
            return redirect()->back()->with('error', 'User already has access to this page in the selected camp.');
        }
        else
        {
            $user_access = new PageAccess();
            $user_access->camp_id = $camp_id;
            $user_access->user_id = $user_id;
            $user_access->page_id = $page_id;
            $user_access->create = $has_create;
            $user_access->view = $has_view;
            $user_access->edit = $has_edit;
            $user_access->delete = $has_delete;
            $user_access->save();
            return redirect()->back()->with('success', 'User access created successfully.');
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
        $user_access_id = $request->input('user_access_id');
        $user_access = PageAccess::find($user_access_id);

        if ($user_access) {
            $user_access->create = $request->input('has_create') ? 1 : 0;
            $user_access->view = $request->input('has_view') ? 1 : 0;
            $user_access->edit = $request->input('has_edit') ? 1 : 0;
            $user_access->delete = $request->input('has_delete') ? 1 : 0;
            $user_access->save();
            return response()->json([
                'status' => true,
                'message' => 'User access updated successfully.',
            ]);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => 'User access not found.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user_access_id = $request->input('hide_access_id');
        $user_access = PageAccess::find($user_access_id);

        if ($user_access) {
            $user_access->delete();
            return redirect()->back()->with('success', 'User access deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'User access not found.');
        }
    }
}
