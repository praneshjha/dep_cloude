Dear <b> {{$fname}} {{$lname}}</b>
<p>You have received request to hold {{$hold_seat}} units in your departure.</p>
<p><b>Hold Request By:</b> {{auth()->user()->name}} {{auth()->user()->last_name}}</p>
<p><b>Buyer Company Name:</b> {{auth()->user()->company_name}}</p>
<p><b>Deaparture Name:</b> {{$departure->title}}</p>
<p><b>Departure Date:</b> {{date('d M, Y', strtotime($departure->start_date))}}</p>
<p><b>No of units on hold:</b> {{$hold_seat}}</p>
<p><b>For More details pls click here:</b>: <a href="http://departurecloud.com/login">Login Now</a></p>
