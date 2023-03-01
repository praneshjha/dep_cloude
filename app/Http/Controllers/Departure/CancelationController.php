<?php

namespace App\Http\Controllers\Departure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Departure;
use App\CancelSchedule;
use App\PaymentSchedule;
use Auth;

class CancelationController extends Controller
{
    public function index($id){
        $cancel = CancelSchedule::where('departure_id',$id)->get();
        $pay_count = PaymentSchedule::where('departure_id',$id)->get();
        //$pay = PaymentSchedule::where('departure_id',$id)->first();
        
        $payment = PaymentSchedule::where('departure_id',$id)->where('date','!=','1970-01-01')->get();
        $pay = PaymentSchedule::where('departure_id',$id)->where('date','=','1970-01-01')->first();
        
        $departure = Departure::find($id);
        if(count($cancel) > 0){
            return view('cancelation.edit',compact('cancel','departure'));
        }
        return view('cancelation.create',compact('departure','payment','pay'));
    }
    public function store(Request $request){
        $id = request()->route('id');
        $departure =Departure::find($id);
       foreach($request->percentage as $key=>$value){
            $cancel = new CancelSchedule;
            $cancel->tenant_id = auth::user()->tenant_id;
            $cancel->departure_id = $id;
            $cancel->date = date("Y-m-d", strtotime($request->date[$key]));
            $cancel->percentage = $request->percentage[$key];
            $cancel->save();
       }
       $status = [
          'url'=> url('/departure/terms',$id),
        ];
        
        return response()->json($status);
    }
    public function update(Request $request){
        $id = request()->route('id');
        $total = 0;
       $departure =Departure::find($id);
              
          foreach($request->percentage as $key=>$value){
            $total = $total + $value;
          }
           //if($total == 100){
              $delete = CancelSchedule::where('tenant_id',auth::user()->tenant_id)->where('departure_id',$id)->delete();

               foreach($request->percentage as $key=>$value){
                    $cancel = new CancelSchedule;
                    $cancel->tenant_id = auth::user()->tenant_id;
                    $cancel->departure_id = $id;
                    $cancel->date = date("Y-m-d", strtotime($request->date[$key]));
                    $cancel->percentage = $request->percentage[$key];
                    $cancel->change_status = 1;
                    //return $cancel;
                    $cancel->save();
               }
               $status = [
                  'url'=> url('/departure/terms',$id),
                ];
            // }
            // else{
            //     $status = [
            //       'msg'=> 'msg',
            //     ];

            // }
        $departure = Departure::find($id);
        $departure->change_status = 1;
        $departure->save();   
         
        return response()->json($status);
    }
    public function delete($id,$dep){
        CancelSchedule::where('departure_id',$dep)->where('id',$id)->delete();
        return redirect()->back();
    }
}
