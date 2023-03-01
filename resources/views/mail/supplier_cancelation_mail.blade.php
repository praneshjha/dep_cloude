Dear {{$supplier->name}} {{$supplier->last_name}},                                                                              
<p>Following Cancellation request has been submitted to supplier.</p>
<p><b>Deaparture Name: </b>{{$departure->title}}</p>
<p><b>Departure Date: </b>{{date('d M, Y', strtotime($departure->start_date))}}</p>
<p><b>Supplier: </b>{{$departure->departure_ownner}}</p>
<p><b>No of units: </b>{{$unit}}</p>
<p><b>Cancellation charges: </b>{{$deduction_amount}}</p>
<p>To contact supplier pls <a href="https://departurecloud.com/login">Login</a> to Departure Cloud.</p>
