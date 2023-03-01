<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Departure;
use App\User;
use App\CancelSchedule;
use App\BookDeparture;
use App\DepartureBookingPriceUpdates;
use App\PaymentSchedule;
use Carbon\Carbon;
use Auth;
use DB;
use Mail;
use App\Notification;
use App\Traits\FireBaseNotification;
use App\Helpers\MailHelper;
class BookController extends Controller
{
    use FireBaseNotification;
    
    public function index(){
        $data = Departure::all();
        foreach($data as $value){
            $today = date("Y-m-d");
            $date1=date_create($value->start_date);
            $date2=date_create($today);
            $diff=date_diff($date1,$date2);
            $date = $diff->format("%R%a");
            if($date){
                 $value->id; 
                //$data =DB::table('departures')->where('id', $value->id)->update(['hold_seat' => 0]);
            }
        }
        $book = Departure::orderBy('id','DESC')->paginate(8);
        return view('book.index',compact('book'));
    }
    public function hold(Request $request){
       $id = $request->id;
       $total = $request->total_hold + $request->hold;
       $data =DB::table('departures')->where('id', $id)->update(['hold_seat' => $total]);
        if($data){
            return Redirect::back();
        }
    }
    public function store(Request $request){
        $id = $request->id;
        $total = $request->total_available + $request->book;
        $data =DB::table('departures')->where('id', $id)->update(['booked_seat' => $total]);
         if($data){
             return Redirect::back();
         }
    }
    public function BookingPriceUpdate(Request $request){
   
       $data = new DepartureBookingPriceUpdates;
       $data->booking_unique_id = $request->booking_unique_id;
       $data->user_id = $request->user_id;
       $data->departure_id = $request->departure_id;
       $data->price = $request->price;
       $data->date = date("Y-m-d", strtotime($request->date));
       $data->save();
       return response()->json($data);
    }
    public function BookingCancle(Request $request, $id)
    { 
        
        $getData = MailHelper::setMailConfig();

        $booking_id = $request->unique_id;
        $departure_id = $request->departure_id;
        $departure = Departure::find($departure_id);
        $supplier = User::where('tenant_id',$departure->tenant_id)->first();
        $supplier_forNoti = User::where('id',$departure->contact_person_id)->first();
        if($departure->additional_person_id !=""){
            $supplier_forNoti_add = User::where('id',$departure->additional_person_id)->first();
        }else{
            $supplier_forNoti_add = "";
        }
        $buyer = User::find(auth::user()->id);
        $paid_amount = DB::table('departure_booking_price_updates')->where('booking_unique_id',$booking_id)->where('departure_id',$departure_id)->sum('price');
        $date = date('Y-m-d');
        $cancelation = CancelSchedule::where('departure_id',$departure_id)
                    ->where('tenant_id',$departure->tenant_id)
                    ->get();
        $count = 0;
        foreach($cancelation as $row){
            if($row->date <= $date){
                $count = $count+1;
               //return $cancel = $row->percentage;
            } 
        }
        foreach($cancelation as $key=>$row){
            if($key == ($count-1)){
               $cancel = $row->percentage;
            } 
        }
        $payment_schedule = PaymentSchedule::where('departure_id',$departure_id)
                          ->where('user_id',$departure->user_id)
                          ->get();
        $payment_total = 0;
        foreach($payment_schedule as $payment){
            if($payment->date <= $date){
               $payment_total = $payment->percentage+$payment_total;
            }
        } 
        $booking = BookDeparture::where('unique_id',$booking_id)
                 ->where('user_id',auth::user()->id)
                 ->sum('price');
        $unit = BookDeparture::where('unique_id',$booking_id)
                 ->where('user_id',auth::user()->id)
                 ->sum('booked_seat');
        $booking_data = BookDeparture::where('unique_id',$booking_id)
                 ->where('user_id',auth::user()->id)
                 ->first();
        
        $total_paid_amount = $booking * ($payment_total/100);
        $deduction_amount = $total_paid_amount * ($cancel/100);
        $refund_amount = $paid_amount - $deduction_amount;
        $booking_ids = BookDeparture::where('unique_id',$booking_id)
                 ->where('user_id',auth::user()->id)
                 ->get();
        foreach ($booking_ids as $key => $value) {
            $data = BookDeparture::find($value->id);
            $data->status = 0;
            $data->cancelation_date = $date;
            $data->save();
        }
        
        $dep_update = Departure::find($departure_id);
        $dep_update->available_seat = $departure->available_seat+$unit;
        $dep_update->save();
        
        // Notification Buyer

        $noti_buyer = new Notification;
        $noti_buyer->title = "Departure cloud - Cancellation";
        $noti_buyer->body = 'Following Cancellation request has been submitted to supplier Deaparture Name: '.$departure->title.' Departure Date: '.date('d M, Y', strtotime($departure->start_date)).' Supplier: '.$departure->departure_ownner.' No of units: '.$unit.' Cancellation charges: '.$deduction_amount. ' To contact supplier pls login to Departure Cloud.';
        $noti_buyer->body_html = '<p>Following Cancellation request has been submitted to supplier </p><p><b>Deaparture Name:</b> '.$departure->title.' </p><p><b>Departure Date:</b> '.date('d M, Y', strtotime($departure->start_date)).' </p><p><b>Supplier:</b> '.$departure->departure_ownner.' </p><p><b>No of units:</b> '.$unit.' </p><p><b>Cancellation charges:</b> '.$deduction_amount. ' </p><p>To contact supplier pls login to Departure Cloud.</p>';
        $noti_buyer->user_id = $buyer->id;
        $noti_buyer->type = "Cancellation";
        $noti_buyer->url_1 = url('login');
        $noti_buyer->save();
        $last_body_buyer = $noti_buyer->body;
        $last_title_buyer = $noti_buyer->title;
        $last_link_buyer = $noti_buyer->url_1;
        //++++++++Notification send Code for Buyer++++++++++++//

        $firebaseToken = User::where('id',$buyer->id)->whereNotNull('fcm_token')->value('fcm_token');

        $sendNotification = $this->sendNotificationBuyer($firebaseToken, $last_title_buyer, $last_body_buyer, $last_link_buyer,$noti_buyer->id);
        
        // Notification Buyer

        // Notification Supplier
        $noti_supplier = new Notification;
        $noti_supplier->title = "Departure cloud - Cancellation";
        $noti_supplier->body = 'Following Cancellation request has been submitted to supplier Deaparture Name: '.$departure->title.' Departure Date: '.date('d M, Y', strtotime($departure->start_date)).' Supplier: '.$departure->departure_ownner.' No of units: '.$unit.' Cancellation charges: '.$deduction_amount. ' To contact supplier pls login to Departure Cloud.';

        $noti_supplier->body_html = '<p>Following Cancellation request has been submitted to supplier.</p><p><b>Deaparture Name:</b> '.$departure->title.' </p><p><b>Departure Date:</b> '.date('d M, Y', strtotime($departure->start_date)).' </p><p><b>Supplier:</b> '.$departure->departure_ownner.' </p><p><b>No of units:</b> '.$unit.' </p><p><b>Cancellation charges:</b> '.$deduction_amount. ' </p><p>To contact supplier pls login to Departure Cloud.</p>';
        $noti_supplier->user_id = $supplier_forNoti->id;
        $noti_supplier->type = "Cancellation";
        $noti_supplier->url_1 = url('login');
        $noti_supplier->save();
        $last_body_supplier = $noti_supplier->body;
        $last_title_supplier = $noti_supplier->title;
        $last_link_supplier = $noti_supplier->url_1;

        //++++++++Notification send Code for Suplier++++++++++++//

        $firebaseToken = User::where('id',$supplier_forNoti->id)->whereNotNull('fcm_token')->value('fcm_token');
        $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_supplier, $last_body_supplier, $last_link_supplier,$noti_supplier->id);
        if($supplier_forNoti_add != ""){
            $firebaseTokens = User::where('id',$supplier_forNoti_add->id)->whereNotNull('fcm_token')->value('fcm_token');
            $sendNotification = $this->sendNotificationSupplier($firebaseTokens, $last_title_supplier, $last_body_supplier, $last_link_supplier,$noti_supplier->id);
        }
        
        
        // Notification Supplier

        Mail::send('mail.buyer_cancelation_mail', ['departure' => $departure,'total_price'=> $booking,'schedule_ammount'=>$total_paid_amount,'total_paid'=>$paid_amount, 'deduction_amount'=>$deduction_amount, 'refund_amount'=>$refund_amount, 'booking_data'=>$booking_data,'unit'=>$unit], function ($m) use ($buyer,$getData) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to($buyer->email);
            $m->subject('Departure cloud - Cancellation');
        });
        if($supplier_forNoti_add != ""){
            Mail::send('mail.supplier_cancelation_mail', ['supplier'=>$supplier_forNoti,'departure' => $departure,'total_price'=> $booking,'schedule_ammount'=>$total_paid_amount,'total_paid'=>$paid_amount, 'deduction_amount'=>$deduction_amount, 'refund_amount'=>$refund_amount, 'booking_data'=>$booking_data,'unit'=>$unit], function ($m) use ($supplier_forNoti,$supplier_forNoti_add,$getData) {
                $m->from($getData['from_mail'], $getData['from_name']);
                $m->to($supplier_forNoti->email);
                $m->cc($supplier_forNoti_add->email);
                $m->subject('Departure cloud - Cancellation');
             });
        }else{
            Mail::send('mail.supplier_cancelation_mail', ['supplier'=>$supplier_forNoti,'departure' => $departure,'total_price'=> $booking,'schedule_ammount'=>$total_paid_amount,'total_paid'=>$paid_amount, 'deduction_amount'=>$deduction_amount, 'refund_amount'=>$refund_amount, 'booking_data'=>$booking_data,'unit'=>$unit], function ($m) use ($supplier_forNoti,$getData) {
                $m->from($getData['from_mail'], $getData['from_name']);
                $m->to($supplier_forNoti->email);
                $m->subject('Departure cloud - Cancellation');
             });
        }
       return response()->json($data);
    }
    public function BookingCancleSup(Request $request, $id)
    { 
        $getData = MailHelper::setMailConfig();
        //dd($id);
        $booking_id = $request->unique_id;
        $departure_id = $request->departure_id;
        $departure = Departure::find($departure_id);
        $supplier = User::where('tenant_id',$departure->tenant_id)->first();
        $supplier_forNoti = User::where('id',$departure->contact_person_id)->first();
        $buyer = User::find(auth::user()->id);
        $paid_amount = DB::table('departure_booking_price_updates')->where('booking_unique_id',$booking_id)->where('departure_id',$departure_id)->sum('price');
        $date = date('Y-m-d');
        $cancelation = CancelSchedule::where('departure_id',$departure_id)
                    ->where('tenant_id',$departure->tenant_id)
                    ->get();
        $count = 0;
        foreach($cancelation as $row){
            if($row->date <= $date){
                $count = $count+1;
               //return $cancel = $row->percentage;
            } 
        }
        foreach($cancelation as $key=>$row){
            if($key == ($count-1)){
               $cancel = $row->percentage;
            } 
        }
        $payment_schedule = PaymentSchedule::where('departure_id',$departure_id)
                          ->where('user_id',$departure->user_id)
                          ->get();
        $payment_total = 0;
        foreach($payment_schedule as $payment){
            if($payment->date <= $date){
               $payment_total = $payment->percentage+$payment_total;
            }
        } 
        $booking = BookDeparture::where('unique_id',$booking_id)
                 ->sum('price');
       // dd(auth::user()->id);
        $unit = BookDeparture::where('unique_id',$booking_id)
                 ->sum('booked_seat');
        //dd($unit);
        $booking_data = BookDeparture::where('unique_id',$booking_id)
                 ->first();
        
        $total_paid_amount = $booking * ($payment_total/100);
        $deduction_amount = $total_paid_amount * ($cancel/100);
        $refund_amount = $paid_amount - $deduction_amount;
        $booking_ids = BookDeparture::where('unique_id',$booking_id)
                 ->get();
        foreach ($booking_ids as $key => $value) {
            $data = BookDeparture::find($value->id);
            $data->status = 0;
            $data->cancelation_date = $date;
            $data->save();
        }
        //dd($data);
        $dep_update = Departure::find($departure_id);
        $dep_update->available_seat = $departure->available_seat+$unit;
        $dep_update->save();
        
        // Notification Buyer

        $noti_buyer = new Notification;
        $noti_buyer->title = "Departure cloud - Cancellation";
        $noti_buyer->body = 'Following Cancellation request has been submitted from supplier Deaparture Name: '.$departure->title.' Departure Date: '.date('d M, Y', strtotime($departure->start_date)).' Supplier: '.$departure->departure_ownner.' No of units: '.$unit.' Cancellation charges: '.$deduction_amount. ' To contact supplier pls login to Departure Cloud.';
        $noti_buyer->body_html = '<p>Following Cancellation request has been submitted to supplier </p><p><b>Deaparture Name:</b> '.$departure->title.' </p><p><b>Departure Date:</b> '.date('d M, Y', strtotime($departure->start_date)).' </p><p><b>Supplier:</b> '.$departure->departure_ownner.' </p><p><b>No of units:</b> '.$unit.' </p><p><b>Cancellation charges:</b> '.$deduction_amount. ' </p><p>To contact supplier pls login to Departure Cloud.</p>';
        $noti_buyer->user_id = $buyer->id;
        $noti_buyer->type = "Cancellation";
        $noti_buyer->url_1 = url('login');
        $noti_buyer->save();
        $last_body_buyer = $noti_buyer->body;
        $last_title_buyer = $noti_buyer->title;
        $last_link_buyer = $noti_buyer->url_1;
        //++++++++Notification send Code for Buyer++++++++++++//

        $firebaseToken = User::where('id',$buyer->id)->whereNotNull('fcm_token')->value('fcm_token');

        $sendNotification = $this->sendNotificationBuyer($firebaseToken, $last_title_buyer, $last_body_buyer, $last_link_buyer,$noti_buyer->id);
        
        // Notification Buyer

        // Notification Supplier
        $noti_supplier = new Notification;
        $noti_supplier->title = "Departure cloud - Cancellation";
        $noti_supplier->body = 'Following Cancellation request has been submitted From supplier Deaparture Name: '.$departure->title.' Departure Date: '.date('d M, Y', strtotime($departure->start_date)).' Supplier: '.$departure->departure_ownner.' No of units: '.$unit.' Cancellation charges: '.$deduction_amount. ' To contact supplier pls login to Departure Cloud.';

        $noti_supplier->body_html = '<p>Following Cancellation request has been submitted to supplier.</p><p><b>Deaparture Name:</b> '.$departure->title.' </p><p><b>Departure Date:</b> '.date('d M, Y', strtotime($departure->start_date)).' </p><p><b>Supplier:</b> '.$departure->departure_ownner.' </p><p><b>No of units:</b> '.$unit.' </p><p><b>Cancellation charges:</b> '.$deduction_amount. ' </p><p>To contact supplier pls login to Departure Cloud.</p>';
        $noti_supplier->user_id = $supplier_forNoti->id;
        $noti_supplier->type = "Cancellation";
        $noti_supplier->url_1 = url('login');
        $noti_supplier->save();
        $last_body_supplier = $noti_supplier->body;
        $last_title_supplier = $noti_supplier->title;
        $last_link_supplier = $noti_supplier->url_1;

        //++++++++Notification send Code for Suplier++++++++++++//

        $firebaseToken = User::where('id',$supplier_forNoti->id)->whereNotNull('fcm_token')->value('fcm_token');
        $sendNotification = $this->sendNotificationSupplier($firebaseToken, $last_title_supplier, $last_body_supplier, $last_link_supplier,$noti_supplier->id);
        
        // Notification Supplier

        Mail::send('mail.buyer_cancelation_mail', ['departure' => $departure,'total_price'=> $booking,'schedule_ammount'=>$total_paid_amount,'total_paid'=>$paid_amount, 'deduction_amount'=>$deduction_amount, 'refund_amount'=>$refund_amount, 'booking_data'=>$booking_data,'unit'=>$unit], function ($m) use ($buyer, $getData) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to($buyer->email);
            $m->subject('Departure cloud - Cancellation');
        });

        Mail::send('mail.supplier_cancelation_mail', ['supplier'=>$supplier_forNoti,'departure' => $departure,'total_price'=> $booking,'schedule_ammount'=>$total_paid_amount,'total_paid'=>$paid_amount, 'deduction_amount'=>$deduction_amount, 'refund_amount'=>$refund_amount, 'booking_data'=>$booking_data,'unit'=>$unit], function ($m) use ($supplier_forNoti, $getData) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to($supplier_forNoti->email);
            $m->subject('Departure cloud - Cancellation');
         });
         //dd($data);
       return response()->json($data);
    }
}
