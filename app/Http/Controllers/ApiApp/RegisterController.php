<?php
   
namespace App\Http\Controllers\ApiApp;
   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiApp\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\UserDestination;
use Illuminate\Support\Str;
use App\Role;
use DB;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success =  $user->createToken('MyApp')-> accessToken;
            $logo = ($user->logo == null)?'1649239151.png':$user->logo;
            $logo_url = 'https://www.departurecloud.com/companyLogo/';
            // Update app Token
            $token = User::find($user->id);
            $token->fcm_token_app = $request->FCMToken;
            $token->save();
            
            $role_id = $user->role_id;
            if($role_id != 0){
                $permissions = DB::table('permission_roles')->join('permissions' ,'permissions.id', 'permission_roles.permission_id')
                    ->select('permissions.name')
                    ->where('permission_roles.role_id', $role_id)
                    ->distinct()
                    ->get();
            }
            else{
                $permissions = DB::table('permissions')
                    ->select('permissions.name')
                    ->distinct()
                    ->get();
            }

            $response = [
                'error' => false,
                'token'    => $success,
                'profile_image' => $logo,
                'profile_url' => $logo_url,
                'user_type' => $user->main_user_type,
                'permissions' => $permissions,
                'email' => $user->email, 
                'password' => $user->text_password, 
                'message' => 'User login successfully.',
            ];
    
    
            return response()->json($response, 200);
   
            // return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    public function profile(Request $request)
    {
        $base_url = url('');
        $user_data = Auth::user();
        $user['id'] = $user_data->id;
        $user['name'] = $user_data->name;
        $user['mobile'] = $user_data->mobile;
        $user['company_name'] = $user_data->company_name;
        $banner_image = ($user_data->banner_image == null)?'cover.jpg':$user_data->banner_image;
        // $banner_image = $base_url.'/assets1/images/'.$banner_image;
        $banner_image = $base_url.'/BannerImage/'.$banner_image;
        $logo = ($user_data->logo == null)?'company_logo.png':$user_data->logo;
        $logo = $base_url.'/companyLogo/'.$logo;
        $city = ($user_data->city == null)?'':$user_data->city;
        $state = ($user_data->state == null)?'':$user_data->state;
        $pin = ($user_data->pin == null)?'':$user_data->pin;
        $website = ($user_data->website == null)?'':$user_data->website;
        $facebook = ($user_data->facebook == null)?'':$user_data->facebook;
        $instagram = ($user_data->instagram == null)?'':$user_data->instagram;
        $youtube = ($user_data->youtube == null)?'':$user_data->youtube;
        $linkedin = ($user_data->linkedin == null)?'':$user_data->linkedin;
        $twitter = ($user_data->twitter == null)?'':$user_data->twitter;
        $pinterest = ($user_data->pinterest == null)?'':$user_data->pinterest;
        $user['banner_image'] = $banner_image;
        $user['logo'] = $logo;
        $user['city'] = $city;
        $user['state'] = $state;
        $user['pin'] = $pin;
        $user['website'] = $website;
        $user['facebook'] = $facebook;
        $user['instagram'] = $instagram;
        $user['youtube'] = $youtube;
        $user['linkedin'] = $linkedin;
        $user['twitter'] = $twitter;
        $user['pinterest'] = $pinterest;

        $social_network = array(
            array(
                "url" => $website,
                "image" => $base_url."/social_icons/website.png"
            ),
            array(
                "url" => strtolower($facebook),
                "image" => $base_url."/social_icons/facebook.png"
            ),
            array(
                "url" => strtolower($instagram),
                "image" => $base_url."/social_icons/instagram.png"
            ),
            array(
                "url" => strtolower($youtube),
                "image" => $base_url."/social_icons/youtube.png"
            ),
            array(
                "url" => strtolower($linkedin),
                "image" => $base_url."/social_icons/linkedin.png"
            ),
            array(
                "url" => strtolower($twitter),
                "image" => $base_url."/social_icons/twitter.png"
            ),
            array(
                "url" => strtolower($pinterest),
                "image" => $base_url."/social_icons/pinterest.png"
            )
        );
        $s_network = [];
        foreach($social_network as $key => $social){
            if($social['url'] != ''){
                $s_network[] = $social;
            }
        }


        $user_destination = UserDestination::where('user_id',Auth::user()->id)->get();
        $status = array(
            'error' => false,
            'user' => $user,
            'social_network' => $s_network,
            'user_destination' => $user_destination
        );
        return Response($status,200);   
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if(!Hash::check($request->current_password, Auth::user()->password)){
            $error = [
                'error'=>'Your current password does not matches with the password'
            ];
            return $this->sendError('Validation Error.', $error);
        }

        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json([
            'error' => false,
            'message' => 'Password updated Successfully!!'
        ]);  
    }

    public function profileImageUpdate(Request $request)
    {
        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'avatar' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $user = User::find($id);
        if($request->avatar){         
            $image = $request->avatar;
            $image = base64_decode($image);
            $imageName = Str::random(12) . '.png';
            Storage::disk('public')->put('BannerImage/'.$imageName, $image);
            $user->banner_image = $imageName;
            $user->save();
            $profile_image = url('')."/BannerImage/".$imageName;
            $message = 'Profile image uploaded succesfully!';
            $status = [
                'error' => false, 
                'message' => $message,
                'profile_image' => $profile_image
            ];
            return response()->json($status, 200);             
        }
        else{
            $message = 'Sorry, we could not upload this file. Try saving it in a different format and upload again.';   
            $status = [
                'error' => true, 
                'message' => $message,
            ];            
            return response()->json($status, 400);
        }

    }

    public function permissions(){
        $permission = User::getPermissions();
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
        $status = [
            'error' => false, 
            'role' => $role,
            'permission_allow' => $permission_allow,
            'permission' =>$permission,
            'permissions' => $permissions,
        ];
        return response()->json($status, 200);
    }

    public function forgotPassword(Request $request){
        $input = $request->only('email');
        $validator = Validator::make($input, [
            'email' => "required|email"
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $response = Password::sendResetLink($input);
    
        $message = $response == Password::RESET_LINK_SENT ? 'Mail send successfully' : GLOBAL_SOMETHING_WANTS_TO_WRONG;
        
        return response()->json($message);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $status = array(
            'error' => false,
            'message' => 'Logout succesfully!',
        );
        return Response($status,200);  
    }
}