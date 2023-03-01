<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Auth;

class User extends Authenticatable 
{
    use HasApiTokens, Notifiable;
    //implements MustVerifyEmail

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','last_name', 'email', 'password', 'mobile', 'company_id', 'company_name', 'tenant_id','address','city','country','main_user_type','state','pin','remember_token','text_password','company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getPermissions()
    {
      $role_id = Auth::user()->role_id;
      $permissions = array();
      if($role_id != '0'){
          $permission_id = PermissionRole::where('role_id', $role_id)->pluck('permission_id');
          foreach($permission_id as $key => $id){
                $name = Permission::where('id', $id)->value('name');
                array_push($permissions,$name);
          }
      }
      else{
          $per_names= Permission::pluck('name');
          foreach($per_names as $key => $value){
              array_push($permissions,$value);
          }
      }
      return $permissions;
     }
 }
