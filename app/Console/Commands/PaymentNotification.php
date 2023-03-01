<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use User;

class PaymentNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payment notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $date  = date('Y-m-d');
        // $data =  DB::table('book_departures')->get();
        // if(count($data) > 0){
        //     foreach($data as $row){
        //         $schedule = DB::table('payment_schedules')->where('departure_id',105)->get();
        //         foreach($schedule as $key=>$value){
        //             // $count = DB::table('departure_booking_price_updates')->where('booking_unique_id',$row->unique_id)->where('departure_id',105)->get();
        //             // $update = DB::table('departure_booking_price_updates')->where('booking_unique_id',$row->unique_id)->where('departure_id',105)->first();
        //             //dd(count($count));
        //           // if(count($count) == 0){
        //           //   if($key == 0){
        //                 $user = DB::table('users')->where('id',$row->user_id)->first();
        //                 dd($user);

        //           //   }
        //           // }
        //         }
        //     }
        // }
    }
}
