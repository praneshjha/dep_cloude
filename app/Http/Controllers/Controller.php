<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Notification;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        // $notification = Notification::orderBy('created_at', 'DESC')->take(25)->get();
        // $totals = Notification::where('status_view', 0)->count();
      
        // \View::share(['notification'=> $notification,'total_notification'=> $totals]); 
     }
}
