<?php

namespace App\Http\Controllers\Departure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Departure;
use App\AgentItinerary;
use App\PaymentSchedule;

class ItineraryPdfController extends Controller
{
    public function pdfItinerayCreate(Request $request)
    {
        
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $departure = Departure::where('id', $route_id)->first();
        $payment = PaymentSchedule::where('departure_id',$route_id)->get();
        $pdf_itinerary = AgentItinerary::where('departure_id', $route_id)
                ->first();
        if(isset($pdf_itinerary)){
            if($pdf_itinerary->pdf_file != null){
                $infoPath = pathinfo(public_path('/agentitinerary/'.$pdf_itinerary->pdf_file));
                $extension = $infoPath['extension'];
            }else{
                $extension = '';
            }
        }else{
            $extension = '';
        }

        if($departure->departure_type == 5){
                $f_originating = DB::table('departure_flight_details')
                ->where('departure_id', $departure->id)->get();
                $f_returning = DB::table('return_flight_details')
                ->where('departure_id', $departure->id)->get();

            foreach ($f_originating as $key => $f_origin) {
                $d_date = date("d M, Y", strtotime($f_origin->flight_date));
                $a_date = date("d M, Y", strtotime($f_origin->flight_arrival_date));
                $f_origin->departure_itinerary = "Departure - Flight - ".$d_date;
                $f_origin->arrival_itinerary = "Arrival - Flight - ".$a_date;
            }
            foreach ($f_returning as $key => $f_return) {
                $rd_date = date("d M, Y", strtotime($f_return->flight_date));
                $ra_date = date("d M, Y", strtotime($f_return->flight_arrival_date));
                $f_return->departure_itinerary = "Departure - Flight - ".$rd_date;
                $f_return->arrival_itinerary = "Arrival - Flight - ".$ra_date;
            }
            $default_iti0 = $f_originating;
            $default_iti1 = $f_returning;
            $default_iti2 = [];

        }elseif($departure->departure_type == 4){
            $hotels2 = DB::table('departure_hotels')
                ->where('departure_id', $departure->id)->get();
               
            foreach ($hotels2 as $key => $hotel) {
                $checkin = date("d M, Y", strtotime($departure->start_date));
                $checkout = date("d M, Y", strtotime($departure->end_date));
                $hotel->checkin = "Checkin - Hotel - ".$checkin;
                $hotel->checkout = "Checkout - Hotel - ".$checkout;
            }  
            $default_iti0 = [];
            $default_iti1 = [];
            $default_iti2 = $hotels2;

        }elseif($departure->departure_type == 3){
            $f_originating = DB::table('departure_flight_details')
                ->where('departure_id', $departure->id)->get();
            $f_returning = DB::table('return_flight_details')
                ->where('departure_id', $departure->id)->get();

            foreach ($f_originating as $key => $f_origin) {
                $d_date = date("d M, Y", strtotime($f_origin->flight_date));
                $a_date = date("d M, Y", strtotime($f_origin->flight_arrival_date));
                $f_origin->departure_itinerary = "Departure - Flight - ".$d_date;
                $f_origin->arrival_itinerary = "Arrival - Flight - ".$a_date;
            }
            foreach ($f_returning as $key => $f_return) {
                $rd_date = date("d M, Y", strtotime($f_return->flight_date));
                $ra_date = date("d M, Y", strtotime($f_return->flight_arrival_date));
                $f_return->departure_itinerary = "Departure - Flight - ".$rd_date;
                $f_return->arrival_itinerary = "Arrival - Flight - ".$ra_date;
            }

            $hotels1 = DB::table('departure_hotels')
                ->where('departure_id', $departure->id)->get();
            foreach ($hotels1 as $key => $hotel) {
                $checkin = date("d M, Y", strtotime($departure->start_date));
                $checkout = date("d M, Y", strtotime($departure->end_date));
                $hotel->checkin = "Checkin - Hotel - ".$checkin;
                $hotel->checkout = "Checkout - Hotel - ".$checkout;
            }

            $default_iti0 = $f_originating;
            $default_iti1 = $f_returning;
            $default_iti2 = $hotels1;
        }else{
            $default_iti0 = [];
            $default_iti1 = [];
            $default_iti2 = [];
        }
        if($pdf_itinerary){
            if(Auth::user()->main_user_type = 1 || Auth::user()->main_user_type = 2)
            {
              return view('departure.itinerary_pdf_edit',compact('pdf_itinerary','departure','payment','departure','extension','default_iti0','default_iti1','default_iti2'));
            }
            else{
                return view('404');
            }
        }
        else{
            return view('departure.itinerary_pdf',compact('pdf_itinerary','departure','payment','departure','extension','default_iti0','default_iti1','default_iti2'));
        }

    }
    public function pdfItinerayStore(Request $request)
    {
        //  $data = $request->all();
        //  $user = auth()->user(); 
        //  $route_ids = $request->route('id'); 
        //  $route_id = (int)$route_ids;

        //  $pdf_itinerary = new AgentItinerary;
        //  $pdf_itinerary->title = $request->title;
        //  $pdf_itinerary->description = $request->description;
        //  $pdf_itinerary->departure_id = $route_id;
        //  $pdf_itinerary->tenant_id = $user->tenant_id;
        //  $pdf_itinerary->user_id = $user->id;
        //  $pdf_itinerary->dep_type = "main";
        //  $pdf_itinerary->unique_key = Str::random(10).time();
        //      if($request->hasFile('pdf_name')){
        //      $pdf_files = $request->file('pdf_name');
        //      $extension = $request->pdf_name->getClientOriginalExtension();
        //      $name = $request->pdf_name->getClientOriginalName();
        //      $filenames = explode('.pdf',$name);
        //      $filename = $filenames[0].'-'.Str::random(3).'.'.$extension;
        //      $storagePath = Storage::disk('s3')->put('departure_cloud/itinerary/'.$filename, file_get_contents($pdf_files), 'public');
        //      $pdf_itinerary->pdf_file = $filename;                    
        //  };
        //  $pdf_itinerary->save();
        //  $status = [
        //          'message'=> "Sussess!",
        //      ];
        //  return response()->json($status);

        $data = $request->all();
        $user = auth()->user(); 
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        
        if(isset($request->title)){
            $api ="AIzaSyBySWVWveKOPu6p1leZoy_N3_Df3WIXm98";
            $site =$request->title;
            $adress="https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$site&category=CATEGORY_UNSPECIFIED&strategy=DESKTOP&key=$api";
            $curl_init = curl_init($adress);
            curl_setopt($curl_init,CURLOPT_RETURNTRANSFER,true);
           
            $response = curl_exec($curl_init);
            curl_close($curl_init);
           
            $googledata = json_decode($response,true);
           
             $snapdata = $googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"];
             $snaps =$snapdata["screenshot"];
            $snap = $snaps["data"];
            //$data = base64_decode($snap);
            $base64String= $snap; //64 bit code    
                //dd($base64String);
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                $imageName =  Str::random(5).time() . '.jpg';
                $imageFile = Image::make($image)->fit(464, 260)->stream();
                // $imageFile = $imageFile->__toString();
                $relPath = '/ScreenShot/';
                $fp = fopen( public_path().$relPath.$imageName, "w+");
                fwrite($fp, $image);
                fclose($fp);
                //Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
        }
        else{
            $imageName = '';   
        }


        $pdf_itinerary = new AgentItinerary;
        $pdf_itinerary->title = $request->title;
        $pdf_itinerary->description = $imageName;
        $pdf_itinerary->departure_id = $route_id;
        $pdf_itinerary->tenant_id = $user->tenant_id;
        $pdf_itinerary->user_id = $user->id;
        $pdf_itinerary->dep_type = "main";
        $pdf_itinerary->unique_key = Str::random(10).time();
        if($request->hasFile('pdf_name')){ 
            $pdf_files = $request->file('pdf_name');
            $extension = $pdf_files->getClientOriginalExtension();
            $name = $pdf_files->getClientOriginalName();
            if($extension == 'pdf'){
                $filenames = explode('.pdf',$name);
            }elseif ($extension == 'jpg') {
                $filenames = explode('.jpg',$name);
            }elseif ($extension == 'jpeg') {
                $filenames = explode('.jpeg',$name);
            }elseif ($extension == 'png') {
                $filenames = explode('.png',$name);
            }elseif ($extension == 'PNG') {
                $filenames = explode('.PNG',$name);
            }elseif ($extension == 'JPG') {
                $filenames = explode('.JPG',$name);
            }else{
                $filenames = explode('.JPEG',$name);
            }

            $filename = $filenames[0].'-'.Str::random(5).'.'.$extension;
            //$storagePath = Storage::disk('s3')->put('clouddeparture/itinerary/pdf/'.$filename, file_get_contents($pdf_files), 'public');

            //$filenamess = explode('.pdf',$filenames);
            //$filename = $filenamess[0].'-'.time().'.'.$extension;
            $relPath = '/agentitinerary/';
            if (!file_exists(public_path($relPath))) {
                mkdir(public_path($relPath), 777, true);
            }
            $pdf_files->move( public_path().$relPath, $filename );
            $pdf_itinerary->pdf_file = $filename;                    
        };

        $pdf_itinerary->save();
        $status = [
            'url'=> url('/departure/pricing',$route_id),
                'message'=> "Sussess!",
            ];
        return response()->json($status);
     }

    public function pdfItinerayUpdate(Request $request, $id)
    { 
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $pdf = $request->pdf_name;
        $file_name_new = 'pdf_name';
        $data = $request->all();
        if(isset($request->title)){
            $api ="AIzaSyBySWVWveKOPu6p1leZoy_N3_Df3WIXm98";
            $site =$request->title;
            $adress="https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$site&category=CATEGORY_UNSPECIFIED&strategy=DESKTOP&key=$api";
            $curl_init = curl_init($adress);
            curl_setopt($curl_init,CURLOPT_RETURNTRANSFER,true);
           
            $response = curl_exec($curl_init);
            curl_close($curl_init);
           
            $googledata = json_decode($response,true);
           
             $snapdata = $googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"];
             $snaps =$snapdata["screenshot"];
            $snap = $snaps["data"];
            //$data = base64_decode($snap);
            $base64String= $snap; //64 bit code    
                //dd($base64String);
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                $imageName =  Str::random(5).time() . '.jpg';
                $imageFile = Image::make($image)->fit(464, 260)->stream();
                // $imageFile = $imageFile->__toString();
                $relPath = '/ScreenShot/';
                $fp = fopen( public_path().$relPath.$imageName, "w+");
                fwrite($fp, $image);
                fclose($fp);
                //Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
        }
        else{
            $imageName = '';
        }
        if($request->pdf_name)
        { 
            $pdf_files = $request->file('pdf_name');
            $extension = $pdf_files->getClientOriginalExtension();
            $name = $pdf_files->getClientOriginalName();
            $filename = $name.'.'.$extension;
             if($extension == 'pdf'){
                $filenames = explode('.pdf',$name);
            }elseif ($extension == 'jpg') {
                $filenames = explode('.jpg',$name);
            }elseif ($extension == 'jpeg') {
                $filenames = explode('.jpeg',$name);
            }elseif ($extension == 'png') {
                $filenames = explode('.png',$name);
            }elseif ($extension == 'PNG') {
                $filenames = explode('.PNG',$name);
            }elseif ($extension == 'JPG') {
                $filenames = explode('.JPG',$name);
            }else{
                $filenames = explode('.JPEG',$name);
            }
            $filename = $filenames[0].'-'.Str::random(3).'.'.$extension;
            //$storagePath = Storage::disk('s3')->put('clouddeparture/itinerary/pdf/'.$filename, file_get_contents($pdf_files), 'public');

            //$filenamess = explode('.pdf',$filenames);
            //$filename = $filenamess[0].'-'.time().'.'.$extension;
            $relPath = '/agentitinerary/';
            if (!file_exists(public_path($relPath))) {
                mkdir(public_path($relPath), 777, true);
            }
            $pdf_files->move( public_path().$relPath, $filename );    
        }else{
            $filename = $request->old_pdf;
        }
        // $user = auth()->user(); 
        // $dep_id = AgentItinerary::where('id', $id)
        //         ->value('departure_id');
        $pdf_itinerary = AgentItinerary::find($request->itinerary_id);
        $pdf_itinerary->title = $request->title;
        $pdf_itinerary->description = $imageName;
        $pdf_itinerary->pdf_file = $filename;
        $pdf_itinerary->change_status = 1;
        $pdf_itinerary->save();
        $dep_id = $pdf_itinerary->departure_id;
        
        $departure = Departure::find($dep_id);
        $departure->change_status = 1;
        $departure->save();
        $status = [
            'url'=> url('/departure/pricing',$route_id),
            'message'=> "Sussess!",
        ];
        return response()->json($status);
    
      }

}
