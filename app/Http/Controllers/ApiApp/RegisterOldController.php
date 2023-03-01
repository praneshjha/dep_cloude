<?php

namespace App\Http\Controllers\ApiApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use App\User;
use App\UserDestination;
use Hash;
use Validator;
use Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(),[

            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8',
            'mobile' => 'required',
            'company_name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required',
            'company_id' => 'required|between:4,12|unique:users',
        ]);
        if($validator->fails()){   
            return response()->json($validator->messages(), 200);
        }
        $alpha =substr(str_shuffle("abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 11);
        
        $user = new User;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->main_user_type = $request->user_type;
        $user->password =Hash::make($request->password);
        $user->text_password = $request->password;
        $user->company_name = $request->company_name;
        $user->company_id = $request->company_id;
       	$user->tenant_id = $alpha.time();
        $user->address = $request->address;
        $user->city = $request->city;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->pin = $request->zip_code;
        $user->remember_token = Str::random(180).time();
        $user->email_verified_at = Carbon::now()->toDateTimeString(); 
        $user->save();
        $last_id = $user->id;
        // $destArrays = json_decode($request->destinations);
        // if($destArrays){
        //     foreach ($destArrays as $value) {
        //         if($value != null){
        //             $destination  = new UserDestination;
        //             $destination->country = $value->country;
        //             $destination->destination_name = $value->name;
        //             $destination->latitude = $value->lat;
        //             $destination->longitude = $value->long;
        //             $destination->region = $value->region;
        //             $destination->country_iso_3 = $value->iso3;
        //             $destination->country_iso_2 = $value->iso2;
        //             $destination->user_id = $last_id;
        //             $destination->save();
        //         }
        //     }
        // }
        // $last_email = $user->email;
        // $last_token = $user->remember_token;
        // if($last_email){
        //     $email_data = [
        //         'token_user' => $last_token,
        //         'email' => $last_email
        //     ];
        //     $mail_sent = Mail::send('mail-app.verify_mail',$email_data,function($mail) use($request, $last_email)
        //     {
        //         $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        //         $mail->to($last_email);
        //         $mail->subject('Departure Cloud - Mail Verification Link');
        //     });
        // }
        $token =  $user->createToken('CloudTenant')->accessToken;
        //$token = $user->createToken('DookCustomer')->accessToken;

        $status = array(
            'error' => false,
            'token' =>$token,
            'message' => "Registration completed successfully."
        );
        return response()->json($status, 200);      
    }

    public function verifyEmail($token_user, $mail)
    {
        try {
            $user_data = User::where('email',$mail)
            		->where('remember_token',$token_user)
            		->first();

            if($user_data){
                if($user_data->email_verified_at == null){
                    $user = User::find($user_data->id);
                    $user->email_verified_at = Carbon::now()->toDateTimeString();
                    $user->save();

                    return view('auth.login');
                }
                else{
                    return view('auth.login');
                }
            }
            else{
                return view('auth.login');
           }
        } 
        catch (DecryptException $e) {
            $status = array(
                'error' => true,
                'message' => "Unauthorized access.!"
            );
        return response()->json($status, 401);
        }
    }

    // Country List

    public function getCountry(Request $request)
    {
        $countries = DB::table('countries')
        		->select("country_name")
                ->get();
        
        $status = array(
                'error' => false,
                'countries' => $countries
            );
        return response()->json($status, 200);
    }
}
