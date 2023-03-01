<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Departure;
use App\Price;
use App\Helpers\MailHelper;
use Mail;
class PricingModuleController extends Controller
{
	public function updatePriceModal(Request $request)
	{
		$data = $request->all();
		Price::where('departure_id',$request->edit_id)->delete();
		foreach($request->price_type_id as $key => $value) {
			if($request->price_inr[$value] || $request->price_usd[$value]){
			$price_update = new Price;
			$price_update->price_inr = $request->price_inr[$value];
			$price_update->price_usd = $request->price_usd[$value];
			$price_update->symbol_inr = $request->symbol_inr[$value];
			$price_update->symbol_usd = $request->symbol_usd[$value];
			$price_update->price_type_id = $value;
			$price_update->departure_id = $request->edit_id;
			$price_update->save();
			//dd($price_update);
			}
		}
		$status = [
		       'message'=> "Success!",
		   ];
		return response()->json($status);
	}

	public function testingMail(Request $request)
	{
		$getData = MailHelper::setMailConfig();

		Mail::send('test_mail',[], function ($m) use ($request, $getData) {
            $m->from($getData['from_mail'], $getData['from_name']);
            $m->to($request->email);
            //$m->cc('raj.kumar@watconsultingservices.com');
            $m->subject('Departure Cloud - Mail Testing 2');
        });
		$status = [
		    'message'=> "Testing Mail Successfully!",
		];
		return response()->json($status);
	}
}
