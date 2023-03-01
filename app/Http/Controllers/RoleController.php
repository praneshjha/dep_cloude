<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Role;
use App\User;
use App\SmtpIntegration;
use Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use App\PermissionRole;
use Mail;
use App\Helpers\MailHelper;


class RoleController extends Controller
{
    public function index()
    {
        $permission = User::getPermissions();
        if (Gate::allows('role_view',$permission)) 
        {
            $role = Role::where('user_id',Auth::user()->id)->orderBy('id','desc')->get();
            $permissions = DB::table('permissions')
                ->select('module')
                ->distinct()
                ->get();
                foreach($permissions as $row){
                    $permission_allow = DB::table('permissions')
                        ->where('module',$row->module)
                        ->get();        
                        
                    $row->sub_module=$permission_allow;
                }
            return view('role.create_role',compact('role','permission_allow','permission','permissions'));
        }
        else{
            return abort(403);
        }
    }
    public function create(Request $request){
        // $validate = Role::where('name',$request->role)->first();
        // if(isset($validate->name) == $request->role){
        //     return redirect()->back()->with('msg','This role already exists');
        // }else{
        $save = new Role;
        $save->name = $request->role;
        $save->tenant_id = Auth::user()->tenant_id;
        $save->user_id =Auth::user()->id;
        $save->status =0;
        if($save->save()){
            return redirect()->back()->with('msg','Role Created');
        //}
     }
    }
    public function RoleEdit(Request $request){
       $edit = Role::find($request->id);
       $edit->name = $request->role;
       if($edit->save()){
        return redirect()->back()->with('msg','Role updated');
       }
    }
    public function user(Request $request)
    {
        //dd(Auth::user()->tenant_id);
        $keyword = $request->keyword;
        $permission = User::getPermissions();
        if (Gate::allows('user_view',$permission)) {
            $role = Role::where('user_id',Auth::user()->id)->get();
            $user = User::where(['tenant_id'=>Auth::user()->tenant_id,'user_type'=>1])
                ->where(function ($query) use($request){
                    if($request->keyword != ''){
                        $query->where('email','LIKE','%'.$request->keyword.'%')
                                ->orWhere('name','LIKE','%'.$request->keyword.'%');
                    }
            })
            ->orderBy('name','ASC')
            ->paginate(30);

            foreach($user as $row){
                $user_role = Role::where('id',$row->role_id)
                            ->get();
                $row->sub_name=$user_role;
            }
            // $smtp_data = SmtpIntegration::where('user_id',$user->id)->first();
            return view('role.user_create',compact('role','user', 'permission','keyword', ));
        }
        else{
            return abort(403);
        }
    }
    public function UserCreate(Request $request){

        $getData = MailHelper::setMailConfig();

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*"; 
        $length = rand(10, 16); 
        $password = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, 8 );
        $validate = User::where('email',$request->email)->first();
        if(isset($validate->email) == $request->email){
            return redirect()->back()->with('msg','Email id already exist!');
        }else{
        $selector = bin2hex(random_bytes(32));
        $email = $request->email;
        $name= $request->name;
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->phone;
        $user->tenant_id = Auth::user()->tenant_id;
        $user->role_id = $request->role;
        $user->user_type = 1;
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->main_user_type = Auth::user()->main_user_type;
        $user->verified = 1;
        $user->country = Auth::user()->country;
        $user->company_name = Auth::user()->company_name;
        $user->country = Auth::user()->country;
        $user->logo = Auth::user()->logo;
        $user->address = Auth::user()->address;
        $user->city = Auth::user()->city;
        $banner_image = Auth::user()->banner_image;
        $user->state = Auth::user()->state;
        $user->website = Auth::user()->website;
        $user->facebook = Auth::user()->facebook;
        $user->instagram = Auth::user()->instagram;
        $user->youtube = Auth::user()->youtube;
        $user->linkedin = Auth::user()->linkedin;
        $user->pinterest = Auth::user()->pinterest;
        $user->twitter = Auth::user()->twitter;
        $user->password = Hash::make($password);
        $user->text_password = $password;
        $user->remember_token = $selector;
        
        Mail::send('mail.tenant_user_create', ['email' => $email, 'password'=>$password, 'name'=>$name], function ($m) use ($user, $getData) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to($user->email);
            $m->subject('Departure Cloud - User Created Successfully');
        });
        if($user->save()){
           
            return redirect()->back()->with('msg','User Created Successfully');
        }
      }
    }
    public function UserUpdate(Request $request){
        $update = User::find($request->id);
        $update->name = $request->name;
        $update->mobile = $request->phone;
        $update->role_id = $request->role;
        $update->country = Auth::user()->country;
        $update->password = Hash::make($request->new_password);
        $update->text_password = $request->new_password;
        if($update->save()){
            //$user->sendEmailVerificationNotification();
            $last_id = $update->id;
            $edit = new SmtpIntegration;
            $edit->user_name = $request->user_name;
            $edit->password = $request->password;
            $edit->port = $request->port;
            $edit->host = $request->host;
            $edit->from_mail = $request->from_mail;
            $edit->reply_to = $request->reply_to;
            $edit->user_id = $last_id;
            $edit->save();
             return redirect()->back()->with('msg','User Updated Successfully');
         }

        
    }
    public function disable(Request $request, $id)
    {
        $user  = User::find($id);
        //dd($departures);
        if($user->verified == 0){
            $user->verified = 1;
            $user->save();
        }
        else{
            $user->verified = 0;
            $user->save();
        }
        return response()->json(['success'=>'Departure disabled successfully!']);
    }
    public function Userdelete(Request $request, $id){
        return User::find($id)->delete();
        return response()->json(['success'=>'User deleted successfully!']);
    }
    public function Roledelete(Request $request, $id){
        Role::find($id)->delete();
        return response()->json(['success'=>'User deleted successfully!']);
    }
    public function PermissionAllow(Request $request){
        $permission = $request->permission_id;
        $role_id = $request->role_id;
        $delete = DB::table('permission_roles')->where('role_id',$role_id)->delete();
        if($permission > 0){
            for ($i=0; $i<sizeof($permission); $i++)
            {
                //dd($i);
               $data = array();
               $data['permission_id']= $permission[$i];
               $data['role_id']   = $role_id;
               $query_insert = DB::table('permission_roles')->insert($data);
            }
        
        if($query_insert){
            return redirect()->back()->with('msg', 'Permission provided successfully');
        }
     }else{
        return redirect()->back()->with('msg', 'Permission removed successfully');
     }
    }
  
}
