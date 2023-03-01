<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\User;
use Notification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //return redirect()->route('itinerary_list');
        if (Auth::user()->email_verified_at == null) {
           return Redirect::to('/thank-you');
        }
        //return redirect()->route('all_departure');
        return redirect()->route('dashboard');
    }
}
