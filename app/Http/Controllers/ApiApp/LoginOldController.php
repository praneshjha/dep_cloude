<?php

namespace App\Http\Controllers\ApiApp;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use App\User;
use Validator;
use Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $validator = Validator::make($request->all(),[
                'email' => 'required',
                'password' => 'required',
            ]
        );
        if($validator->fails()){
            $message = $validator->errors();
            $status = [
                'error' => true,
                'message' => $message
            ];
        return Response($status,200);
        }
        

    	if (Auth::attempt(['email' => $request->email, 'password' => $request->password]))
    	{
            $user = $request->user();
        	if($user->email_verified_at == null){
	            $status = array(
	                'error' => true,
	                'token' =>"",
	                'user' => new \stdClass(),
	                'message' => "Your Email is not Verified Please Verify Your Mail.!"
	            );
	            return Response($status,200);
	        }
	        else{
	        	$token =  $user->createToken('CloudTenant')->accessToken;
	            $last_login = User::find($user->id);
	            $last_login->last_login_at = Carbon::now()->toDateTimeString();
	            $last_login->save();
	            $status = array(
	                'error' => false,
	                'token' => $token,
	                'user' => $user,
	                'message' => "Logged in Successfully!!"
	            ); 
	            return Response($status,200);
	        }
        }
        else{
            return response()->json(['error'=>'Unauthorized'], 401);
        } 
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
