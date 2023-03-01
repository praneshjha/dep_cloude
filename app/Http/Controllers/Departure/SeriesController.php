<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Departure;
use App\Destination;
use App\Country;
use App\DepartureDestination;
use App\Price;
use App\CountryDeparture;
use App\AllAirline;
use App\HotelCategory;
use App\AllDestination;
use App\HoldDeparture;
use App\HoldTill;
use App\HoldDuration;
use App\HoldDepartureSeat;
use App\BookDeparture;
use App\Itinerary;
use App\CurrencyConversion;
use App\DepartureFlightDetail;
use App\ReturnFlightDetail;
use App\UserDestination;
use App\PaymentSchedule;
use App\CancelSchedule;
use App\DeparturePrice;
use App\DepartureTag;
use App\DepartureType;
use App\Inclusion;
use App\AgentItinerarie;
use App\DepartureColumnType;
use App\DepartureBookingPriceUpdates;
use App\Exports\UsersExport;
use App\Exports\BuyerExport;
use App\DepartureHotel;
use Maatwebsite\Excel\Facades\Excel;
use Mail;

class SeriesController extends Controller
{
    public function seriesIndex(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $departure = Departure::where('id',$route_id)->first();
        return view('departure.series',compact('departure'));
    }

    public function seriesDeparture(Request $request)
    {
        $data = $request->all();
        $array_check=[];
            
        if($request->title){
            for($i = 0; $i < count($request->title); $i++) {
                if ($request->title[$i] != '') {
                    array_push($array_check, $request->title[$i]);
                }
            }
        }
        if(count($array_check)>0){
            foreach($request->title as $key=>$value ) { 
                $startFormat = $request->start_date[$key];
                $start_date = date("y-m-d", strtotime($startFormat));
                $endFormat = $request->end_date[$key];
                $end_date = date("y-m-d", strtotime($endFormat));
                $departure_id = $request->departure_id;
                $user = auth()->user();
                $departure_data = Departure::where('id', $departure_id)->first();
                $last_dep_id = Departure::orderBy('id','desc')->first();

                $departure = new Departure;
                $departure->title = $value;
                $departure->start_date = $start_date;
                $departure->end_date = $end_date;
                $departure->total_seat = $request->total_unit[$key];
                $departure->no_of_days = $departure_data->no_of_days;
                $departure->no_of_nights = $departure_data->no_of_nights;
                $departure->total_room = $departure_data->total_room;
                $departure->meal_type = $departure_data->meal_type;
                $departure->description = $departure_data->description;
                $departure->from = $departure_data->from;
                $departure->ending_at = $departure_data->ending_at;
                $departure->company_name = $departure_data->company_name;
                $departure->dep_type = $departure_data->dep_type;
                $departure->departure_type = $departure_data->departure_type;
                $departure->price_inr = $departure_data->price_inr;
                $departure->price_usd = $departure_data->price_usd;
                $departure->departure_ownner = $departure_data->departure_ownner;
                $departure->hotel_category = $departure_data->hotel_category;
                $departure->hold_duration = $departure_data->hold_duration;
                $departure->source = $departure_data->source;
                $departure->termspayment = $departure_data->termspayment;
                $departure->single_supplyment_price_inr = $departure_data->single_supplyment_price_inr;
                $departure->single_supplyment_price_usd = $departure_data->single_supplyment_price_usd;
                $departure->tenant_id = $user->tenant_id;
                $departure->user_id = $user->id;
                $departure->dep_id = ($last_dep_id->dep_id) +1;
                $departure->unique_key = Str::random(10).time();
                $departure->clone_type = "Series";
                $departure->save();
                $last_id = $departure->id;

                $inclusions = Inclusion::where('departure_id', $departure_id)->get();
                if(count($inclusions)>0){
                  Inclusion::where('departure_id', $last_id)->delete();
                  foreach ($inclusions as $inclusion) {
                      $dep_inclusions= new Inclusion;
                      $dep_inclusions->departure_id = $last_id;
                      $dep_inclusions->name = $inclusion->name;
                      $dep_inclusions->description = $inclusion->description;
                      $dep_inclusions->icon = $inclusion->icon;
                      $dep_inclusions->dep_type = $inclusion->dep_type;
                      $dep_inclusions->user_id = $user->id;
                      $dep_inclusions->tenant_id = $user->tenant_id;
                      $dep_inclusions->save();
                    }              
                }

                //Day Schedule
                $daySchedules = Itinerary::where('departure_id', $departure_id)->get();
                if(count($daySchedules)>0){
                    Itinerary::where('departure_id', $last_id)->delete();
                    foreach ($daySchedules as $key => $daySchedule) {
                        $dayItinerary = new Itinerary;
                        $dayItinerary->departure_id = $last_id;
                        $dayItinerary->day_number = $daySchedule->day_number;
                        $dayItinerary->day_heading = $daySchedule->day_heading;
                        $dayItinerary->destinations = $daySchedule->destinations;
                        $dayItinerary->description = $daySchedule->description;
                        $dayItinerary->user_id = $daySchedule->user_id;
                        $dayItinerary->tenant_id = $daySchedule->tenant_id;
                        $dayItinerary->dep_type = $daySchedule->dep_type;
                        $dayItinerary->unique_key = Str::random(10).time();
                        $dayItinerary->save();
                    }
                }
              
                $itinerary = AgentItinerarie::where('departure_id', $departure_id)
                          ->first();
                if($itinerary){
                    AgentItinerarie::where('departure_id', $last_id)->delete();
                    $itinerary_save = new AgentItinerarie;
                    $itinerary_save->departure_id = $last_id;
                    $itinerary_save->title = $itinerary->title;
                    $itinerary_save->description = $itinerary->description;
                    $itinerary_save->pdf_file = $itinerary->pdf_file;
                    $itinerary_save->tenant_id = $user->tenant_id;
                    $itinerary_save->user_id = $user->id;
                    $itinerary_save->dep_type = $itinerary->dep_type;
                    $itinerary_save->unique_key = Str::random(10).time();
                    //return $itinerary_save;
                    $itinerary_save->save();
                } 
                $hd = HoldDeparture::where('departure_id', $departure_id)
                          ->first();
                if($hd){
                    HoldDeparture::where('departure_id', $last_id)->delete();
                    $hold = new HoldDeparture;
                    $hold->user_id =  $user->id;
                    $hold->departure_id = $last_id;
                    $hold->hold_till = $hd->hold_till;
                    $hold->date = date("Y-m-d H:i:s");
                    $hold->save(); 
                }
             
                $PaymentSchedule = PaymentSchedule::where('departure_id', $departure_id)->get();
                if(count($PaymentSchedule)>0){
                    PaymentSchedule::where('departure_id', $last_id)->delete();
                    if($start_date == $departure_data->start_date){
                      foreach ($PaymentSchedule as $key => $ps) {
                          $Payment_Schedule = new PaymentSchedule;
                          $Payment_Schedule->departure_id = $last_id;
                          $Payment_Schedule->term_conditions = $ps->term_conditions;
                          $Payment_Schedule->date = $ps->date;
                          $Payment_Schedule->price = $ps->price;
                          $Payment_Schedule->percentage = $ps->percentage;
                          $Payment_Schedule->single_supplement = $ps->single_supplement;
                          $Payment_Schedule->total = $ps->total;
                          $Payment_Schedule->user_id = $user->id;
                          $Payment_Schedule->save();
                      }
                    }else{
                      $hds_till = DB::table('hold_departures')->where('departure_id',$departure_id)->select('hold_till')->first();
                      $day_arr = [];
                      $date_arr = [];
                      $date_range = [];
                      foreach ($PaymentSchedule as $key => $ps) {
                        $lastpaydate = PaymentSchedule::where('departure_id',$last_id)
                            ->orderBy('id','DESC')->first();
                          $Payment_Schedule = new PaymentSchedule;
                          $Payment_Schedule->departure_id = $last_id;
                          $Payment_Schedule->term_conditions = $ps->term_conditions;
                          if($ps->date == '1970-01-01'){
                            $Payment_Schedule->date = $ps->date;
                          }else{
                            $date1=date_create($ps->date);
                            $date2=date_create($start_date);
                            $diff=date_diff($date1,$date2);
                            $date_in_days = $diff->format("%R%a");

                            if($key == 1){
                                 array_unshift($date_range,$ps->date);

                                $ltrim = ltrim($date_in_days, '+');
                            }else{
                                $dat1=date_create(end($date_range));
                                $dat2=date_create($ps->date);
                                $diff1=date_diff($dat1,$dat2);
                                $diff_in_days = $diff1->format("%R%a");
                                
                                $ltrim = ltrim($date_in_days, '+')+$diff_in_days;
                                array_unshift($date_range,$lastpaydate->date);
                            }
                            $date_addition = date('Y-m-d', strtotime($ps->date. ' + '.$ltrim .' days'));
                            $Payment_Schedule->date = date('Y-m-d', strtotime($date_addition. ' - '.$hds_till->hold_till .' days'));
                            
                          }
                          
                          // //$Payment_Schedule->date = $ps->date;
                          $Payment_Schedule->price = $ps->price;
                          $Payment_Schedule->percentage = $ps->percentage;
                          $Payment_Schedule->single_supplement = $ps->single_supplement;
                          $Payment_Schedule->total = $ps->total;
                          $Payment_Schedule->user_id = $user->id;
                          $Payment_Schedule->save();
                          
                      }
                    }
                }
                $Pricing = DeparturePrice::where('departure_id', $departure_id)->distinct()->get();

                if(count($Pricing)>0){
                    DeparturePrice::where('departure_id', $last_id)->delete();
                    foreach ($Pricing as $key => $dp) {
                          $Pricing = new DeparturePrice;
                          $Pricing->departure_id = $last_id;
                          $Pricing->sharing = $dp->sharing;
                          $Pricing->transport_type = $dp->transport_type;
                          $Pricing->meal_type = $dp->meal_type;
                          $Pricing->hotel_type = $dp->hotel_type;
                          $Pricing->flight_class = $dp->flight_class;
                          $Pricing->passenger = $dp->passenger;
                          $Pricing->airport_transfers = $dp->airport_transfers;
                          $Pricing->other = $dp->other;
                          $Pricing->group_size = $dp->group_size;
                          $Pricing->price = $dp->price;
                          $Pricing->currency_symbol = $dp->currency_symbol;
                          $Pricing->currency_code = $dp->currency_code;
                          $Pricing->save();
                    }
                }

                $CancelSchedule = CancelSchedule::where('departure_id', $departure_id)          ->get();

                if(count($CancelSchedule)>0){
                    CancelSchedule::where('departure_id', $last_id)->delete();
                    if($start_date == $departure_data->start_date){
                      foreach ($CancelSchedule as $key => $cs) {
                        $cancel_Schedule = new CancelSchedule;
                        $cancel_Schedule->departure_id = $last_id;
                        $cancel_Schedule->date = $cs->date;
                        $cancel_Schedule->percentage = $cs->percentage;
                        $cancel_Schedule->total = $cs->total;
                        $cancel_Schedule->tenant_id = $cs->tenant_id;
                        $cancel_Schedule->save();
                      }
                    }else{
                        
                        $hd_till = DB::table('hold_departures')->where('departure_id',$departure_id)->select('hold_till')->first();
                        $date_range = [];
                      foreach ($CancelSchedule as $key => $cs) {
                        $lastpaydate = CancelSchedule::where('departure_id',$last_id)
                            ->orderBy('id','DESC')->first();
                        $cancel_Schedule = new CancelSchedule;
                        $cancel_Schedule->departure_id = $last_id;
                        if($cs->date == '1970-01-01'){
                          $cancel_Schedule->date = $cs->date;
                        }else{
                            $date1=date_create($cs->date);
                            $date2=date_create($start_date);
                            $diff=date_diff($date1,$date2);
                            $date_in_days = $diff->format("%R%a");
                            if($key == 1){
                                array_unshift($date_range,$cs->date);
                                $ltrim = ltrim($date_in_days, '+');
                            }else{
                                $dat1=date_create(end($date_range));
                                $dat2=date_create($cs->date);
                                $diff1=date_diff($dat1,$dat2);
                                $diff_in_days = $diff1->format("%R%a");
                                
                                $ltrim = ltrim($date_in_days, '+')+$diff_in_days;
                                array_unshift($date_range,$lastpaydate->date);;
                            }
                            $date_addition = date('Y-m-d', strtotime($cs->date. ' + '.$ltrim .' days'));

                            $cancel_Schedule->date = date('Y-m-d', strtotime($date_addition. ' - '.$hd_till->hold_till .' days'));
                        }
                        
                        $cancel_Schedule->percentage = $cs->percentage;
                        $cancel_Schedule->total = $cs->total;
                        $cancel_Schedule->tenant_id = $cs->tenant_id;
                        $cancel_Schedule->save();
                      }
                    }  
                }

                $origin_flights = DepartureFlightDetail::where('departure_id', $departure_id)->distinct()->get();

                if(count($origin_flights)>0){
                      DepartureFlightDetail::where('departure_id', $last_id)->delete();
                      foreach ($origin_flights as $key => $oflight) {
                          $originFs = new DepartureFlightDetail;
                          $originFs->departure_id = $last_id;
                          $originFs->flight_name = $oflight->flight_name;
                          $originFs->code = $oflight->code;
                          $originFs->flight_no = $oflight->flight_no;
                          $originFs->flight_date = $oflight->flight_date;
                          $originFs->flight_dep_time = $oflight->flight_dep_time;
                          $originFs->flight_arrival_time = $oflight->flight_arrival_time;
                          $originFs->flight_arrival_date = $oflight->flight_arrival_date;
                          $originFs->flight_dep_airport = $oflight->flight_dep_airport;
                          $originFs->flight_arrival_airport = $oflight->flight_arrival_airport;
                          $originFs->baggage = $oflight->baggage;
                          $originFs->save();
                      }
                }

                $returning_flights = ReturnFlightDetail::where('departure_id', $departure_id)->distinct()->get();

                if(count($returning_flights)>0){
                      ReturnFlightDetail::where('departure_id', $last_id)->delete();
                      foreach ($returning_flights as $key => $oflight) {
                          $returningFs = new ReturnFlightDetail;
                          $returningFs->departure_id = $last_id;
                          $returningFs->flight_name = $oflight->flight_name;
                          $returningFs->code = $oflight->code;
                          $returningFs->flight_no = $oflight->flight_no;
                          $returningFs->flight_date = $oflight->flight_date;
                          $returningFs->flight_dep_time = $oflight->flight_dep_time;
                          $returningFs->flight_arrival_time = $oflight->flight_arrival_time;
                          $returningFs->flight_arrival_date = $oflight->flight_arrival_date;
                          $returningFs->flight_dep_airport = $oflight->flight_dep_airport;
                          $returningFs->flight_arrival_airport = $oflight->flight_arrival_airport;
                          $returningFs->baggage_arriving = $oflight->baggage_arriving;
                          $returningFs->save();
                      }
                }

                $countries_departure = CountryDeparture::where('departure_id', $departure_id)->get();
                if(count($countries_departure)>0){
                      CountryDeparture::where('departure_id', $last_id)->delete();
                      foreach ($countries_departure as $key => $country_id) {
                          $country_dep = new CountryDeparture;
                          $country_dep->country_id = $country_id->country_id;
                          $country_dep->departure_id = $last_id;
                          $country_dep->save();
                      }
                }
                $destination_departure = DepartureDestination::where('departure_id', $departure_id)->get();
                if(count($destination_departure)>0){
                    DepartureDestination::where('departure_id', $last_id)->delete();
                    foreach ($destination_departure as $key => $destination_id) {
                          $country_dep = new DepartureDestination;
                          $country_dep->destination_id = $destination_id->destination_id;
                          $country_dep->departure_id = $last_id;
                          $country_dep->save();
                    }
                }
                $hotels = DepartureHotel::where('departure_id', $departure_id)->get();

                if(count($hotels)>0){
                    DepartureHotel::where('departure_id', $last_id)->delete();
                    foreach ($hotels as $key => $hotel) {
                      $Hotels = new DepartureHotel;
                      $Hotels->departure_id = $last_id;
                      $Hotels->name = $hotel->name;
                      $Hotels->destination_id = $hotel->destination_id;
                      $Hotels->hotel_category = $hotel->hotel_category;
                      $Hotels->total_room = $hotel->total_room;
                      $Hotels->user_id = $hotel->user_id;
                      $Hotels->tenant_id = $hotel->tenant_id;
                      $Hotels->save();
                    }
                }
            }
        }
        return response()->json(['status'=>$last_id],200);
    }
}
