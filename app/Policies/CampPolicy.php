<?php

namespace App\Policies;

use App\Models\Camps;
use App\Models\PageAccess;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Session;

class CampPolicy
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
        $page_id = 9; // camps page id = 9

        $has_permission = PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('view', 1)
            ->exists();
        return $has_permission ? 1 : 0;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $camp_id = Session::get('active_camp_id');
        $page_id = 9; // camps page id = 9

        $has_permission = PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('create', 1)
            ->exists();
        return $has_permission ? 1 : 0;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        $camp_id = Session::get('active_camp_id');
        $page_id = 9; // camps page id = 9

        $has_permission = PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('edit', 1)
            ->exists();
        return $has_permission ? 1 : 0;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Camps $camps): bool
    {
        $camp_id = Session::get('active_camp_id');
        $page_id = 9; // camps page id = 9

        $has_permission = PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('delete', 1)
            ->exists();
        return $has_permission ? 1 : 0;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Camps $camps): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Camps $camps): bool
    {
        return false;
    }
}
