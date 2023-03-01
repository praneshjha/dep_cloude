<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyProfilePolicy
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

    public function company_profile_view()
    {
        $permission = User::getPermissions();
        if(in_array('company-profile-view', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function company_profile_edit()
    {
        $permission = User::getPermissions();
        if(in_array('company-profile-edit', $permission)) {
            return true;
        }
        else{
            return false;
        }
    }
}
