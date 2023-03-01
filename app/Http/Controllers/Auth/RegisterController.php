<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\UserDestination;
use Auth;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mobile' => ['required'],
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            // 'state' => ['required', 'string', 'max:255'],
        ]);
    }
     //'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //dd($_POST['destination']);
        $var = $data['public_id'];
        $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.','_','__','___','  ','   '), '', $var);
            $strlower = strtolower($removeSC);
            $arr_cn = explode(' ', $strlower);
            $str_cn = implode('-', $arr_cn);
            $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
            $mainstr = rtrim($mainstrs, '-');
            // dd($mainstr);
         
         $save = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'text_password' => $data['password'],
            'mobile' => $data['mobile'],
            'company_name' => $data['company_name'],
            'company_id' => $mainstr,
            'tenant_id' => $data['tenant_id'],
            'address' => $data['address'],
            'city' => $data['city'],
            'country' => $data['country'],
            'main_user_type' => $data['user'],
            'state' => $data['state'],
            'pin' => $data['pin'],
        ]);
        //$save->sendEmailVerificationNotification();
        $last_id = $save->id;
        $contactPersonId = User::find($last_id);
        $contactPersonId->contact_person_id = $last_id;
        $contactPersonId->save();
       //  if($data['user'] == 1){
       //   foreach($_POST['destination'] as $value)
       //      {
       //          $destination = new UserDestination;
       //          $destination->destination_name = $value;
       //          $destination->user_id = $last_id;
       //          $destination->save();
       //      }
       // }
        $destArrays = json_decode($data['destinations']);
        if($destArrays){
            foreach ($destArrays as $value) {
                if($value != null){
                    $destination  = new UserDestination;
                    $destination->country = $value->country;
                    $destination->destination_name = $value->name;
                    $destination->latitude = $value->lat;
                    $destination->longitude = $value->long;
                    $destination->region = $value->region;
                    $destination->country_iso_3 = $value->iso3;
                    $destination->country_iso_2 = $value->iso2;
                    $destination->user_id = $last_id;
                    $destination->save();
                }
            }
        }
        $save->sendEmailVerificationNotification();

        $names = $save->name .' '. $save->last_name;
        if($save->main_user_type == 1){
            $type = "Supplier";
        }else{
            $type = "Buyer";
        }
        $email_data = ['name'=>$names, 'email'=>$save->email, 'company_name' => $save->company_name, 'mobile' => $save->mobile, 'city' => $save->city,'state' => $save->state,'country' => $save->country,'address'=> $save->address,'pin' => $save->pin, 'type'=>$type,'url'=> url()->current(),'public_id'=>$save->company_id];

        Mail::send('emails.user_details',$email_data,function($mail) use($data){
            $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->to('contact@watconsultingservices.com')->subject("Departure Cloud New Registration!");
            //$mail->Bcc('ajay.sharma@watconsultingservices.com');
        });

        // capture_lead start to tutterflycrm
        $curl_data = json_encode(array("token"=> "ed465d3dbc0183f66314e0503a30db1f2f6cd31a7514de8c46b8186a38163603","lead"=>array("first_name"=>$data['name'], "last_name"=>$data['last_name'],"city"=>$data['city'],"country"=>$data['country'], "email"=>$data['email'], "mobile"=>$data['mobile'], "phone"=>$data['mobile'], "company"=>$data['company_name'], "website"=>'',"campaign_name"=>"DepartureCloud Registration form","custom_fields"=>array("lead_source"=>"Web", "products"=>"Departure Cloud","no_of_travellers_handled_annually"=>""))));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://watc.tutterflycrm.com/tfc/api/capture_lead');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($curl_data))                                                                       
        );

        $result = curl_exec($ch);

        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        curl_close($ch);
        // capture_lead end
        
      return $save;
    }
}
