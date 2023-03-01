<?php

namespace App\Http\Controllers\Departure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CurrencyConversion;
use App\Departure;
use App\Inclusion;
use App\DeparturePrice;
use App\DepartureColumnType;
use Auth;
use DB;


class PricingController extends Controller
{
    public function PricingIndex(Request $request)
    {

        $id = request()->route('id');
        $inclusions = Inclusion::all();
        $hotel = DB::table('hotel_categories')->get();
        $departure = Departure::find($id);
        $indian_currency=CurrencyConversion::first();
        $inr = $indian_currency->indian_currency;
        $price = DeparturePrice::where('departure_id',$id)->get();
        $currency = DB::table('currencies')->get();
        $sairing = DB::table('departure_bed_sairings')->get();
        $data = DeparturePrice::where('departure_id',$request->id)->orderBy('id','DESC')->get();
        $first_data = DeparturePrice::where('departure_id',$request->id)->first();
        $departure = Departure::find($id);
        $columns = DepartureColumnType::where('departure_type_id', $departure->departure_type)->get()->pluck('column_name_id');
        $columns = json_encode($columns);

        $meal_plans = DB::table('meal_plans')->get();
        $flight_classes = DB::table('flight_classes')->get();
        $transport_types = DB::table('transport_types')->get();
        $passenger_types = DB::table('passenger_types')->get();
        $airport_transfers = DB::table('airport_transfers')->get();
        $dep_hotel_name = DB::table('departure_hotels')->where('departure_id',$departure->id)->select('name')->get();
        $departure_hotels = DB::table('departure_hotels')->where('departure_id',$departure->id)->select('hotel_category')->groupBy('hotel_category')->get();
        if(count($departure_hotels)>1){
            $hotel_categories = 'Mix';
        }
        else{
            $hotel_cat = DB::table('departure_hotels')->where('departure_id',$departure->id)->value('hotel_category');
            $hotel_categories = DB::table('hotel_categories')->where('hotel_category',$hotel_cat)->value('hotel_category');
        }
        if($departure->departure_type == 1){
            $mealplan = "MAP";
        }
        elseif($departure->departure_type == 2){
            $mealplan = "MAP";
        }
        elseif($departure->departure_type == 3){
            $mealplan = "CP";
        }
        elseif($departure->departure_type == 4){
            $mealplan = "CP";
        }
        else{
            $mealplan = "";
        }
        return view('pricing.create',compact('inr','departure','inclusions','hotel','currency','sairing','data','first_data','departure','columns','meal_plans','flight_classes','transport_types','passenger_types','airport_transfers','hotel_categories','mealplan','dep_hotel_name'));  
        
    }
    public function SelectCurrency(Request $request){
        $startDest = [];
        if($request->has('q')){
            $search = $request->q;
            $startDest = DB::table('countries')->select("id","currency_symbol")
                        ->where('currency_symbol','LIKE',"%$search%")
                        ->limit(5)
                        ->get();
            
        }else{
            $startDest = DB::table('countries')->select("id","currency_symbol")
                        ->limit(5)
                        ->get();
        }
        return $startDest;
        return response()->json($startDest);
    }
    public function StorePricing(Request $request)
    {
        //return $request->all();
        $id = request()->route('id');
        $currency = explode(',',$request->currency);
        // foreach($request->sharing as $key=>$data){
        //   if($data != ''){
       $price =new DeparturePrice;
       $price->sharing = $request->sharing;
       $price->currency_code = $currency[0];
       $price->currency_symbol = $currency[1];
       $price->transport_type = $request->transport_type;
       $price->hotel_type = $request->hotel; 
       $price->meal_type = $request->meal_type;
       $price->group_size = $request->group_size;
       $price->flight_class = $request->flight_class; 
       $price->passenger = $request->passenger;
       $price->airport_transfers = $request->airport_transfers;
       $price->other = $request->other;
       $price->price = $request->price;
       $price->departure_id = $id;
       $price->departure_type = $request->departure_type;
       $price->hotel_name = $request->hotel_name;
       $price->save();
        //     }
        // }
        
        $data = DeparturePrice::where('departure_id',$request->id)->get();
        // $status = [
        //         'url'=> url('/terms-payment-create',$id),
        //     ];
        return response()->json($data);  
    }
    public function UpdatePricing(Request $request)
    {
        $route_id = request()->route('id');
        $id = (int)$route_id;
        $price =DeparturePrice::where('id',$request->price_id)->first();
        // $currency = explode(',',$request->currency);
        // $price->currency_code = $currency[0];
        // $price->currency_symbol = $currency[1];
        $price->sharing = $request->sharing;
        $price->transport_type = $request->transport_type;
        $price->hotel_type = $request->hotel; 
        $price->flight_class = $request->flight_class; 
        $price->passenger = $request->passenger;
        $price->airport_transfers = $request->airport_transfers;
        $price->meal_type = $request->meal_type;
        $price->group_size = $request->group_size;
        $price->other = $request->other;
        $price->price = $request->price;
        $price->departure_id = $id;
        $price->change_status = 1;
        //$price->departure_type = $request->departure_type;
        $price->hotel_name = $request->hotel_name;
        $price->save();
        $dep_id = $price->departure_id;
        $departure = Departure::find($dep_id);
        $departure->change_status = 1;
        $departure->save();
          
        return response()->json($price); 
    }

    public function enableDisable($id){
      $pricings = DeparturePrice::where('id', $id)->select('status')->first();
      if($pricings->status == 1){
        $pricing = DeparturePrice::find($id);
        $pricing->status = 0;
        $pricing->save();
      }
      else{
        $pricing = DeparturePrice::find($id);
        $pricing->status = 1;
        $pricing->save();
      }
      return redirect()->back();
    }

    public function changePricingAll(Request $request, $id)
    {
        //$id = request()->route('id');
        $priceAll =DeparturePrice::where('departure_id',$id)->get();
        if (count($priceAll)>0) {
            foreach ($priceAll as $key => $priceA) {
                $currency = explode(',',$request->currency_change);
                $price = DeparturePrice::find($priceA->id);
                $price->currency_code = $currency[0];
                $price->currency_symbol = $currency[1];
                $price->save();
            }
        }   
        return response()->json($price); 
    }
    public function updateDefaultPrice($id){
        $default_pricings = DeparturePrice::where('id', $id)->select('default_price')->first();
        if($default_pricings->default_price == 1){
          $default_pricing = DeparturePrice::find($id);
          $default_pricing->default_price = 0;
          $default_pricing->save();
        }
        else{
          $default_pricing = DeparturePrice::find($id);
          $default_pricing->default_price = 1;
          $default_pricing->save();
        }
        return redirect()->back();
      }
    
}
