
<h1>Departure Holed (Company Name)- {{Auth::user()->company_name}}</h1>
<h2>Name - {{Auth::user()->name}}</h2>
<h2>Email - {{Auth::user()->email}}</h2>
<h2>Phone - {{Auth::user()->mobile}}</h2>

<h3>Departure - {{$departure->title}}</h3>
<h3>Hold Units - {{$hold_seat}}</h3>
<h3>Request Mode - {{$extra_hold}} Extra</h3>
<h3>Hold Duration - {{$mail_duration}}</h3>
<h3>Note - {{$note}}</h3>