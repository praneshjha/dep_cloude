<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeparturePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function departure_view()
    {
        $permission = User::getPermissions();
        if(in_array('departure-view', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function departure_hold()
    {
        $permission = User::getPermissions();
        if(in_array('departure-hold', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function departure_booking_history()
    {
        $permission = User::getPermissions();
        if(in_array('departure-booking-history', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function departure_book()
    {
        $permission = User::getPermissions();
        if(in_array('departure-book', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function departure_create()
    {
        $permission = User::getPermissions();
        if(in_array('departure-create', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function departure_edit()
    {
        $permission = User::getPermissions();
        if(in_array('departure-edit', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }
    public function departure_clone()
    {
        $permission = User::getPermissions();
        if(in_array('departure-clone', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function mailers()
    {
        $permission = User::getPermissions();
        if(in_array('mailers', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }
}
