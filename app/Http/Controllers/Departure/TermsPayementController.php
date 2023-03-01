<?php

namespace App\Http\Controllers\Departure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Departure;
use App\AgentItinerary;
use App\CurrencyConversion;
use App\PaymentSchedule;
use App\CancelSchedule;
use App\DeparturePrice;
use App\TermMaster;;

class TermsPayementController extends Controller
{
    public function TermsPaymentCreate($id){
         $termsPayment = Departure::find($id);
         $payment  = PaymentSchedule::where('departure_id',$id)->get();
         $sum = PaymentSchedule::where('user_id',$termsPayment->user_id)->where('departure_id',$id)->get()->SUM('percentage');
         $status = Departure::where('id',$id)->first();
         $departure = Departure::find($id);
         if(ucfirst(auth::user()->country) == 'India'){
            $type = 'INR';
            $single = 5;
            $price = 8;
         }else{
            $type = 'USD';
            $price = 2;
            $single = 5;
         }
         $percentage_sum = PaymentSchedule::where('departure_id',$id)->sum('percentage');
         if(count($payment) >0){
            if(Auth::user()->main_user_type = 1 || Auth::user()->main_user_type = 2)
            {
              return view('terms_payment.edit',compact('id','termsPayment','type','price','status','payment','single','sum','departure'));
            }
            else{
                return view('404');
            }
         }else{
             return view('terms_payment.create',compact('id','termsPayment','type','price','status','single','payment','departure'));
         }
    }
    public function TermsPayemntStore(Request $request, $id){
        //$id = request()->route('id');
        $total = 0;
        $departure =Departure::find($id);
              
      foreach($request->percentage as $key=>$value){
        $total = $total + $value;
      }
       if($total == 100){
           foreach($request->percentage as $key=>$value){
                    $payment = new PaymentSchedule;
                    $payment->user_id = auth::user()->id;
                    $payment->departure_id = $id;
                    $payment->date = date("y-m-d", strtotime($request->date[$key]));
                    //$payment->price = $request->price[$key];
                    $payment->percentage = $request->percentage[$key];
                    //$payment->single_supplement = $request->single[$key];
                   // $payment->total = $request->total[$key];
                    $payment->save();
           }
           $status = [
              'url'=> url('/cancelation-schedule',$id),
            ];
        }
        else{
            $status = [
              'msg'=> 'msg',
            ];

        }
              
        return response()->json($status);
    }
    
    public function TermsPaymentUpdate(Request $request,$id){
       
       //$id = request()->route('id');
        $total = 0;
       $departure =Departure::find($id);
              
      foreach($request->percentage as $key=>$value){
        $total = $total + $value;
      }

       if($total == 100){
          PaymentSchedule::where('user_id',auth::user()->id)->where('departure_id',$id)->delete();
           foreach($request->percentage as $key=>$value){
                    // if($key == 1){
                    //   dd(date("y-m-d", strtotime($request->date[$key])));
                    // }
                    $payment = new PaymentSchedule;
                    $payment->user_id = auth::user()->id;
                    $payment->departure_id = $id;
                    $payment->date = date("y-m-d", strtotime($request->date[$key]));
                    //$payment->price = $request->price[$key];
                    $payment->percentage = $request->percentage[$key];
                    //$payment->single_supplement = $request->single[$key];
                   // $payment->total = $request->total[$key];
                   $payment->change_status = 1;
                   $payment->save();
           }
            
            $departure = Departure::find($id);
            $departure->change_status = 1;
            $departure->save();

           $status = [
              'url'=> url('/cancelation-schedule',$id),
            ];
        }
        else{
            $status = [
              'msg'=> 'msg',
            ];

        }
              
        return response()->json($status);

    }
    public function TermsIndex(Request $request){
        $id = request()->route('id');
        $departure = Departure::find($id);
        $term_master = TermMaster::where('tenant_id', auth()->user()->tenant_id)
                ->first();
        $inclusion = DB::table('inclusions')
                        ->where('departure_id',$departure->id)
                        ->count();
        $DeparturePrice = DeparturePrice::where('departure_id',$departure->id)->count();
        $payment_schedule = PaymentSchedule::where('departure_id',$departure->id)->count();
        $cancelation_schedule = CancelSchedule::where('departure_id',$departure->id)->count();
        $departure = Departure::find($departure->id);
        if(isset($departure->termspayment))
        {
            if(Auth::user()->main_user_type = 1 || Auth::user()->main_user_type = 2)
            {
              return view('terms.edit',compact('departure','inclusion','DeparturePrice','payment_schedule','cancelation_schedule'));
            }
            else{
                return view('404');
            }
        }
        return view('terms.create',compact('departure','term_master'));
    }
    public function TermsStore(Request $request){
        $id = request()->route('id');
        $departure =Departure::find($id);
        $departure->termspayment = $request->termspayment;
        $departure->save();
        
        if($departure->status == 1){
           if(Auth::user()->main_user_type = 2)
           { 
            $status = [
                  'url'=> url('/my-departures/card-view'),
                ];
           }
           else{
            $status = [
                  'url'=> url('/my-departures/card-view'),
                ];
           }
        }
        else{
             $status = [
                  'msg'=> 'msg',
                ];
        //return response()->json($status);
        }
        return response()->json($status);
        
    }
   public function PaymentScheduleDelete($id, $dep){
     $delete = PaymentSchedule::find($id);
     $delete->delete();
     if($delete){
        $update = Departure::find($dep);
        if($update->status == 1){
            $update->status = 0;
            $update->flag_status = 0;
            $update->save();
        }
        return redirect()->back();
     }

     return redirect()->back();
   }
    public function currencyConverion(){
        $data=DB::table('currency_conversions')->first();
        // foreach($currency_converion as $row){
        //      $data = $row->indian_currency;
        // }
        return view('terms_payment.currency_converion',compact('data'));
    }
    public function currencyConverionUpdate(Request $request){
        $data = CurrencyConversion::find($request->id);
        $data->indian_currency = $request->currency_conversion;
        $data->save();
        return redirect()->back()->with('msg','Success');
    }
    // master Terms module

    public function TermsMasterIndex(Request $request){

      $term_master = TermMaster::where('tenant_id', auth()->user()->tenant_id)
                ->first();
      if($term_master){
        return view('terms.terms_master_edit',compact('term_master'));
      }
      else{
        return view('terms.terms_master_create');
      }
    }

    public function TermsMasterStore(Request $request){
      $term_master = new TermMaster;
      $term_master->conditions = $request->terms_master;
      $term_master->user_id = auth()->user()->user_id;
      $term_master->tenant_id = auth()->user()->tenant_id;
      $term_master->save();
      return redirect()->back();
    }

    public function TermsMasterUpdate(Request $request){
      $term_master = TermMaster::find($request->id);
      $term_master->conditions = $request->terms_master;
      $term_master->save();
      return redirect()->back();
    }
}
