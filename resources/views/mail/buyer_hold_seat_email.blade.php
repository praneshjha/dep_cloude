Dear <b>{{auth()->user()->name}} {{auth()->user()->last_name}}</b>
<p>{{$hold_seat}} units are on hold for you in Departure.</p>
<p><b>Deaparture Name:</b> {{$departure->title}}</p>
<p><b>Departure Date:</b> {{date('d M, Y', strtotime($departure->start_date))}}</p>
<p><b>Supplier:</b> {{$fname}} {{$lname}}</p>
<p><b>No of units on hold:</b> {{$hold_seat}}</p>
<p><b>Units are on hold till:</b> {{$mail_duration}}</p>
<p><b>For More details pls click here:</b>: <a href="http://departurecloud.com/login">Login Now</a></p>


