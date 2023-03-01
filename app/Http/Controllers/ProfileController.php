<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\UserDestination;
use App\Departure;
use App\HoldDepartureSeat;
use App\BookDeparture;
use App\HoldDuration;
use App\DepartureColumnType;
use App\DepartureType;
use App\User;
use App\DeparturePrice;
use App\SmtpIntegration;
use Auth;
use Hash;
use Mail;
use DB;
use App\Helpers\MailHelper;


class ProfileController extends Controller
{
    public function PublicId(Request $request)
    {
              //print_r($request->get('pkg_id'));die;
       if($request->get('pkg_id')){
           $unique_id = $request->get('pkg_id');
           $id_count = User::where('company_id', $unique_id)
                       ->count();
           if($id_count>0){
               echo 'not-unique';
           }
           else{
              echo 'unique';
           }
          
       }
    }
    public function UserProfile()
    {
        $permission = User::getPermissions();
        if (Gate::allows('company_profile_view',$permission)) 
        { 
            $user = User::where('tenant_id',Auth::user()->tenant_id)->first();
            $c_person = User::where('id',$user->contact_person_id)->select('name','last_name')->first();
            if($c_person){
                if($c_person->last_name != null){
                        $user->flname = $c_person->name.' '.$c_person->last_name;
                }else{
                    $user->flname = $c_person->name;
                }
            }else{
                $user->flname = $user->name.' '.$user->last_name;
            }
            $user_destination = UserDestination::where('user_id',Auth::user()->id)->get();
            $currency = DB::table('currencies')->get();
            $users = User::where('tenant_id',Auth::user()->tenant_id)->orderBy('name','ASC')->get();

            
            foreach ($users as $key => $user_val) {
                if($user_val->last_name != null){
                    $user_val->flname = $user_val->name.' '.$user_val->last_name;
                }else{
                    $user_val->flname = $user_val->name;
                }
            }
            $smtp_data = SmtpIntegration::where('user_id',$user->id)->first();
            return view('profile.index',compact('user_destination','permission','currency','user','users','smtp_data'));
        }
        else{
            return abort(403);
        }
    }
    public function UserEdit(){
        $edit = User::find(Auth::user()->id);
        $user_destination = UserDestination::where('user_id',Auth::user()->id)->get();
        return view('profile.edit',compact('edit','user_destination'));
    }
    public function userProfileUpdate(Request $request){
        //dd('jvnn');
        $currency = explode(',',$request->currency);
        $id = $request->t_id;
        $update = User::find($id);
        $update->contact_person_id = $request->name;
        $update->additional_person_id = $request->additional_person_id;
        $update->company_name = $request->company_name;
        $update->mobile = $request->mobile;
        //$update->email = $request->email;
        $update->city = $request->city;
        $update->state = $request->state;
        $update->country = $request->country;
        $update->pin = $request->pin_code;
        $update->website = $request->website;
        $update->address = $request->address;
        $update->facebook = $request->facebook;
        $update->twitter = $request->twitter;
        $update->linkedin = $request->linkedin;
        $update->youtube = $request->youtube;
        $update->pinterest = $request->pinterest;
        $update->instagram = $request->instagram;
        $update->about = $request->about;
        $update->currency_code = $currency[0];
        $update->currency_symbol = $currency[1];
        //dd($update);
         if($request->hasFile('banner_image')){ 
           // unlink($delete_image_path);
            $banner_image = $request->file('banner_image');
            $extensions = $request->banner_image->getClientOriginalExtension();
            $names = $request->banner_image->getClientOriginalName();
            $filenames = $names;
            $filenamess = explode('.png',$filenames);
            $filename = time().'.'.$extensions;
            $path = '/BannerImage/';
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 777, true); 
                }  
            $banner_image->move( public_path().$path, $filename );  
        $update->banner_image = $filename; 
        }


        if($request->hasFile('logo')){ 
           // unlink($delete_image_path);
            $image = $request->file('logo');
            $extension = $request->logo->getClientOriginalExtension();
            $name = $request->logo->getClientOriginalName();
            $filenames = $name;
            $filenamess = explode('.png',$filenames);
            $filename = time().'.'.$extension;
            $path = '/companyLogo/';
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 777, true); 
                }  
                $image->move( public_path().$path, $filename );   
            }else{
                //return $request->old_logo;
                $filename = $request->old_logo;
            }
            $update->logo = $filename; 
        //$update->save();
        if(auth::user()->main_user_type == 1){
         UserDestination::where('user_id',auth::user()->id)->delete();
          if($request->destinations){
            $destArrays = json_decode($request->destinations);
            foreach($destArrays as $value)
                {

                    $destination = new UserDestination;
                    $destination->country = $value->country;
                    $destination->destination_name = $value->name;
                    $destination->latitude = $value->lat;
                    $destination->longitude = $value->long;
                    $destination->region = $value->region;
                    $destination->country_iso_3 = $value->iso3;
                    $destination->user_id = auth::user()->id;
                    $destination->save();
                }
            }
        }
        $update->save();
        $last_id = $update->id;
        $last_tenant_id = $update->tenant_id;
        $getID = SmtpIntegration::where('tenant_id', $last_tenant_id)->select('id')->first();
        if($getID){
            $edit = SmtpIntegration::find($getID->id);
            $edit->user_name = $request->user_name;
            $edit->password = $request->password;
            $edit->port = $request->port;
            $edit->host = $request->host;
            $edit->from_mail = $request->from_mail;
            $edit->from_name = $request->from_name;
            $edit->reply_to = $request->reply_to;
            $edit->user_id = $last_id;
            $edit->tenant_id = $last_tenant_id;
            $edit->save();
        }else{
            $edit = new SmtpIntegration;
            $edit->user_name = $request->user_name;
            $edit->password = $request->password;
            $edit->port = $request->port;
            $edit->host = $request->host;
            $edit->from_mail = $request->from_mail;
            $edit->from_name = $request->from_name;
            $edit->reply_to = $request->reply_to;
            $edit->user_id = $last_id;
            $edit->tenant_id = $last_tenant_id;
            $edit->save();
        }

        
        return redirect()->route('profile')->with('message', 'Profile Updated Successfully');

    }
    public function ChangePassword(){
        return view('profile.change_password');
    }
    public function PasswordUpdate(Request $request){

        $getData = MailHelper::setMailConfig();

        $data = $request->all();
        $email_password = User::find(auth()->user()->id);
        if(!Hash::check($data['old_password'], auth()->user()->password)){
            return back()->with('error','The current password does not match in our records!');
        }
        else{
            $email_password->password = Hash::make($request->new_password);
            $email_password ->text_password = $request->new_password;
        }        
        $email_password->save();

        Mail::send('mail.change_password', [], function ($m) use ($request, $getData) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to(auth()->user()->email)->subject('Departure Cloud - Password Changed Sucessfully');
            });
        return back()->with('message', 'Password updated successfully.'); 
        
    }
   // public function publicProfile(Request $request, $id, $public)
   // {
   //      $dep_id = BookDeparture::where('user_id',Auth::user()->id)
   //                          ->pluck('departure_id')
   //                          ->toArray();
   //      $user_id = Departure::whereIn('id',$dep_id)
   //                          ->pluck('user_id')
   //                          ->toArray();
   //      $user_data = User::whereIn('id', $user_id)
   //                  ->pluck('id')
   //                  ->toArray();
   //      $value = in_array($id, $user_data);
   //      $c = User::find($id);
   //      $users = User::where(['tenant_id'=>$c->tenant_id, 'main_user_type'=>1,'user_type'=>0])->first();
   //      //dd($value);
   //      //$users = User::where('id', $id)->first();
   //      $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd','pvt','ltd','pvt.','ltd.'), '', $users->company_id);
   //      $strlower = Str::lower($removeSC);
   //      $arr_cn = explode(' ', $strlower);
   //      $str_cn = implode('-', $arr_cn);
   //      $mainstrs = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
   //      $mainstr = rtrim($mainstrs, '-');
        
   //      if(($value == true || $value == 1) && ($public == $mainstr)){
   //          $date = date("Y-m-d");
   //          //$user = User::where('id', $id)->first();
   //          $departures = Departure::where('tenant_id', $users->tenant_id)
   //                  ->where('approve',1)
   //                  ->whereDate('start_date', '>=', $date)
   //                  ->orderBy('id', 'DESC')
   //                  ->paginate(10);

   //          if(count($departures)>0){
   //              foreach ($departures as $key => $value) {
   //               $hold = HoldDepartureSeat::where('departure_id',$value->id) 
   //                          ->sum('hold_seat');
   //                  if($hold){
   //                      $value->hold_sum = $hold;
   //                  }
   //                  else{
   //                      $value->hold_sum = 0;
   //                  }
   //              }
   //          }
   //          if(count($departures)>0){
   //              foreach ($departures as $key => $value) {
   //                 $book = BookDeparture::where('departure_id',$value->id)
   //                       ->where('status',1) 
   //                       ->sum('booked_seat');
   //                 $single_book = BookDeparture::where('departure_id',$value->id) 
   //                              ->sum('single_supplement_booked_seat');
   //                  $book_date = DB::table('book_departures')
   //                              ->join('users','book_departures.user_id','users.id')
   //                              ->select('book_departures.*','users.company_name')
   //                              ->where('departure_id',$value->id)
   //                              ->get();
   //                  $value->book_date = $book_date;
   //                  if($book){
   //                      $value->book_sum = $book;
   //                  }
   //                  else{
   //                      $value->book_sum = 0;
   //                  }
   //                  if($single_book){
   //                      $value->single_book_sum = $single_book;
   //                  }
   //                  else{
   //                      $value->single_book_sum = 0;
   //                  }
   //                 // return $value->book_sum + $value->single_book_sum;

   //                 $hold_till = DB::table('hold_departures')->where('departure_id',$value->id)->get();
   //                  if(count($hold_till)>0){
   //                      foreach($hold_till as $row){
   //                        if($row->departure_id == $value->id){
   //                        $hold = $row->hold_till;
   //                        }
   //                       }
   //                  }else{
   //                      $hold = 0;
   //                  }

   //                  $today = date("Y-m-d");
   //                  $date1=date_create($today);
   //                  $date2=date_create($value->start_date);
   //                  $diff=date_diff($date1,$date2);
   //                  $date = $diff->format("%R%a");

   //                  if(($hold < $date) && ($value->available_seat > 0)){
   //                      $popup = '.bd-example-modal-sm';
   //                  }
   //                  else{
   //                      $popup = 0;
   //                  }
                    
   //              }
   //          }
   //          else{
   //              $popup = 0;
   //              $hold = 0;
   //          }
   //          foreach($departures as $row){
   //              $departure_destination = DB::table('departure_destinations')
   //              ->join('destinations','departure_destinations.destination_id','destinations.id')
   //              ->select('destinations.country_name','destinations.dest_name')
   //              ->where('departure_destinations.departure_id',$row->id)
   //              ->get();
   //              $row->destination = $departure_destination;
   //              $departure_prices = DeparturePrice::where('departure_id',$row->id)->get();
   //              $row->departure_price = $departure_prices;
   //              $departure_first_prices = DeparturePrice::where('departure_id',$row->id)->first();
   //              $row->departure_first_price = $departure_first_prices;
   //          }  
           
   //          $holdduration = HoldDuration::all(); 
   //          $departureCount = Departure::where('dep_type','main')
   //                          ->where('tenant_id',auth()->user()->tenant_id)
   //                          ->where('user_id',Auth::user()->id)
   //                          ->get();
   //          $total = count($departureCount);
   //          $active_departureCount = Departure::where('dep_type','main')
   //                          ->where('tenant_id',auth()->user()->tenant_id)
   //                          ->whereDate('start_date', '>=', $date)
   //                          ->where('approve',1)
   //                          ->get();
   //          $active = count($active_departureCount);
   //          $pending_departureCount = Departure::where('dep_type','main')
   //                          ->where('tenant_id',auth()->user()->tenant_id)
   //                          ->whereDate('start_date', '>=', $date)
   //                          ->where('approve',0)
   //                          ->get();
   //          $pending = count($pending_departureCount);
   //          $inactive_departureCount = Departure::where('dep_type','main')
   //                          ->where('tenant_id',auth()->user()->tenant_id)
   //                          ->whereDate('start_date', '<=', $date)
   //                          ->where('approve',1)
   //                          ->get();
   //          $inactive = count($inactive_departureCount);
            
   //          // $removeSC = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’','Pvt.','Ltd.','Pvt','Ltd'), '', $users->company_id);
   //          // $strlower = Str::lower($removeSC);
   //          // $arr_cn = explode(' ', $strlower);
   //          // $str_cn = implode('-', $arr_cn);
   //          // $mainstr = str_replace( array('--', '---', '----', '----'), '-', $str_cn);
   //          $users->company_url = strtolower($users->company_id);
   //          $user_dest = UserDestination::where('user_id',$users->id)->get();

   //          return view('profile.public_profile',compact('users','departures','total','holdduration','pending','inactive','active','popup','hold','date','user_dest'));
   //      }
   //      else{
   //          return view('404');
   //      }
   // }
    public function SupplierProfile($public){
           
        $users_id = User::where('company_id',$public)->first();
        $users = User::where('tenant_id',$users_id->tenant_id)->first();
        if($users->contact_person_id == ""){
           $users->contactName = $users->name.' '.$users->last_name;
        }else{
            $cuser = User::where('id', $users->contact_person_id)
                ->first();
            $users->contactName = $cuser->name.' '.$cuser->last_name;
        }
        if($users){
            $date = date("Y-m-d");
            //$user = User::where('id', $id)->first();
            $departures = Departure::where('tenant_id', $users_id->tenant_id)
                    ->where('status',1)
                    ->where('approve',1)
                    ->whereDate('start_date', '>=', $date)
                    ->orderBy('id', 'DESC')
                    ->paginate(10);
            $columns = "";
            $departure_types = new \stdClass();
            if(count($departures)>0){
                foreach ($departures as $key => $value) {
                    $hold = HoldDepartureSeat::where('departure_id',$value->id) 
                            ->sum('hold_seat');
                    if($hold){
                        $value->hold_sum = $hold;
                    }
                    else{
                        $value->hold_sum = 0;
                    }
                   $book = BookDeparture::where('departure_id',$value->id) 
                            ->sum('booked_seat');
                   $single_book = BookDeparture::where('departure_id',$value->id) 
                                ->sum('single_supplement_booked_seat');
                    $book_date = DB::table('book_departures')
                                ->join('users','book_departures.user_id','users.id')
                                ->select('book_departures.*','users.company_name')
                                ->where('departure_id',$value->id)
                                ->get();
                    $value->book_date = $book_date;
                    if($book){
                        $value->book_sum = $book;
                    }
                    else{
                        $value->book_sum = 0;
                    }
                    if($single_book){
                        $value->single_book_sum = $single_book;
                    }
                    else{
                        $value->single_book_sum = 0;
                    }
                   // return $value->book_sum + $value->single_book_sum;

                   $hold_till = DB::table('hold_departures')->where('departure_id',$value->id)->get();
                    if(count($hold_till)>0){
                        foreach($hold_till as $row){
                          if($row->departure_id == $value->id){
                          $hold = $row->hold_till;
                          }
                         }
                    }else{
                        $hold = 0;
                    }

                    $today = date("Y-m-d");
                    $date1=date_create($today);
                    $date2=date_create($value->start_date);
                    $diff=date_diff($date1,$date2);
                    $date = $diff->format("%R%a");

                    if(($hold < $date) && ($value->available_seat > 0)){
                        $popup = '.bd-example-modal-sm';
                    }
                    else{
                        $popup = 0;
                    }

                    $departure_destination = DB::table('departure_destinations')
                        ->join('destinations','departure_destinations.destination_id','destinations.id')
                        ->select('destinations.country_name','destinations.dest_name')
                        ->where('departure_destinations.departure_id',$value->id)
                        ->get();
                    $value->destination = $departure_destination;
                    $departure_prices = DeparturePrice::where('departure_id',$value->id)->get();
                    $value->departure_price = $departure_prices;
                    $departure_first_prices = DeparturePrice::where('departure_id',$value->id)->first();
                    if(isset($departure_first_prices->id)){
                        $value->departure_first_price = $departure_first_prices;
                    }
                    else{
                        $value->departure_first_price = 0;
                    }

                    $columns = DepartureColumnType::where('departure_type_id', $value->departure_type)->get()->pluck('column_name_id');
                    $columns = json_encode($columns);
                    $departure_types = DepartureType::where('id', $value->departure_type)->first();
                    
                }
            }
            else{
                $popup = 0;
                $hold = 0;
            } 
            
           
            $users->company_url = strtolower($users->company_id);
            $user_dest = UserDestination::where('user_id',$users->id)->get();

            return view('profile.public_profile',compact('users','departures','popup','hold','date','user_dest','columns','departure_types'));
        }
        else{
            return view('404');
        }
   }
   public function BuyerProfile($id){
    $user = User::where('tenant_id',$id)->first();
     return view('profile.buyer_public_profile',compact('user'));
   }

    public function abcLowerCase(){
        $user = User::where('company_id','!=',"")->get();
        foreach ($user as $key => $value) {
            $users = User::find($value->id);
            $users->profile_urls = url('profile').'/'.$value->company_id;
            $users->save();
        }
        return response()->json("raj Kumar");
    }
   
}