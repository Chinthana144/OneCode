<?php

namespace App\Policies;

use App\Models\PageAccess;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AccessPlanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        $camp_id = Session::get('active_camp_id');
        $page_id = 13; // access plan page id = 13

        $has_permission = PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('view', 1)
            ->exists();
        return $has_permission ? 1 : 0;
    }

    public function create(User $user): bool
    {
        $camp_id = Session::get('active_camp_id');
        $page_id = 13; // access plan page id = 13

        $has_permission = PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('create', 1)
            ->exists();
        return $has_permission ? 1 : 0;
    }

    public function update(User $user): bool
    {
        $camp_id = Session::get('active_camp_id');
        $page_id = 13; // access plan page id = 13

        $has_permission = PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('edit', 1)
            ->exists();
        return $has_permission ? 1 : 0;
    }

    public function delete(User $user): bool
    {
        $camp_id = Session::get('active_camp_id');
        $page_id = 13; // camps page id = 13

        $has_permission = PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('delete', 1)
            ->exists();
        return $has_permission ? 1 : 0;
    }
}
