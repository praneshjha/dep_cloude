@if($senTo == 'wat')
	<h1>Booked Departure (Company Name)- {{Auth::user()->company_name}}</h1>
	<h2>Name - {{Auth::user()->name}}</h2>
	<h2>Email - {{Auth::user()->email}}</h2>
	<h2>Phone - {{Auth::user()->mobile}}</h2>

	<p>Departure Name : {{$departure->title}}</p>
	<p>Departure Date : {{$departure->start_date}}</p>
	<p>No of N/D : {{$departure->no_of_nights}}/{{$departure->no_of_days}}</p>
	<p>Total Units : {{$departure->total_seat}}</p>
	<p>Booked Units : {{$book_seat}}</p>
	<p>Price : {{$currency}} {{$price}}</p>
	<p>Booking Date & Time : {{date('d M, Y h:i A', strtotime("+5 hours +30 minutes"))}}</p>
	<p>@if($lead_pasanger_name != '') Note : {{$lead_pasanger_name}} @endif</p>
	<p>@if($note != '') Note : {{$note}} @endif</p>
	<table border="1">
	      <tr>
	        <th>Sharing</th>
	        <th>Transport Type</th>
	        <th>Hotel Type</th>
	        <th>Meal Plan</th>
	        <th>Minimum Pax</th>
	        <th>Price</th>
	      </tr>
	    @foreach($book_details_mail as $row)
	      <tr>
	      	<td>{{$row->sairing}}</td>
	      	<td>{{$row->transport_type}}</td>
	      	<td>{{$row->hotel_type}}</td>
	      	<td>{{$row->meal_plan}}</td>
	      	<td>{{$row->group_size}}</td>
	      	<td>{{$row->currency_symbol}} {{($row->price)}}</td>
	      </tr>
	    @endforeach
	</table>

	<p><b>Payment Schedule</b></p>
	<table border="1">
	 
	        <?php $count = 0;?>
	        @foreach($payment_schedule as $key=>$row)
	            <?php  
	                    if(date('Y-m-d') > $row->date)
	                    {
	                    $count = $count + $row->percentage;
	                    }
	            ?>
	        @endforeach

	        <?php
	            echo '<th>Minimum Booking Amount</th><td>'.$currency .'-' .number_format(($price * $count)/100).'</td>';
	        ?>
	    @foreach($payment_schedule as $key=>$row)
	        @if($key == 0)
	            @if(date('Y-m-d') < $row->date)
	             <th>Minimum Booking Amount</th> : <td>{{$currency}} - {{number_format(($price * $row->percentage)/100)}}</td>
	            @endif  
	        @endif
	        @if($key > 0)

	        @if($key == 0)
	        @endif

	        @if(date('Y-m-d') > $row->date)
	           <tr>
	                <td>{{date('d M, Y', strtotime($row->date))}}</td>
	                <td>{{$currency}} {{number_format(($price * $row->percentage)/100)}}</td>
	           </tr>
	        @else
	            <tr>
	            	<td>{{date('d M, Y', strtotime($row->date))}}</td>
	            	<td>{{$currency}} {{number_format(($price * $row->percentage)/100)}}</td>
	            </tr>
	        @endif
	           
	        @endif
	    @endforeach
	</table>
@else
	<h2>Dear,</h2>
	<h3>{{Auth::user()->name}}</h3>

	<p>Departure Name : {{$departure->title}}</p>
	<p>Departure Date : {{$departure->start_date}}</p>
	<p>No of N/D : {{$departure->no_of_nights}}/{{$departure->no_of_days}}</p>
	<p>Total Units : {{$departure->total_seat}}</p>
	<p>Booked Units : {{$book_seat}}</p>
	<p>Price : {{$currency}} {{$price}}</p>
	<p>Booking Date & Time : {{date('d M, Y h:i A', strtotime("+5 hours +30 minutes"))}}</p>
	<p>@if($lead_pasanger_name != '') Note : {{$lead_pasanger_name}} @endif</p>
	<p>@if($note != '') Note : {{$note}} @endif</p>
	<table border="1">
	      <tr>
	        <th>Sharing</th>
	        <th>Transport Type</th>
	        <th>Hotel Type</th>
	        <th>Meal Plan</th>
	        <th>Minimum Pax</th>
	        <th>Price</th>
	      </tr>
	    @foreach($book_details_mail as $row)
	      <tr>
	      	<td>{{$row->sairing}}</td>
	      	<td>{{$row->transport_type}}</td>
	      	<td>{{$row->hotel_type}}</td>
	      	<td>{{$row->meal_plan}}</td>
	      	<td>{{$row->group_size}}</td>
	      	<td>{{$row->currency_symbol}} {{($row->price)}}</td>
	      </tr>
	    @endforeach
	</table>

	<p><b>Payment Schedule</b></p>
	<table border="1">
	 
	        <?php $count = 0;?>
	        @foreach($payment_schedule as $key=>$row)
	            <?php  
	                    if(date('Y-m-d') > $row->date)
	                    {
	                    $count = $count + $row->percentage;
	                    }
	            ?>
	        @endforeach

	        <?php
	            echo '<th>Minimum Booking Amount</th><td>'.$currency .'-' .number_format(($price * $count)/100).'</td>';
	        ?>
	    @foreach($payment_schedule as $key=>$row)
	        @if($key == 0)
	            @if(date('Y-m-d') < $row->date)
	             <th>Minimum Booking Amount</th> : <td>{{$currency}} - {{number_format(($price * $row->percentage)/100)}}</td>
	            @endif  
	        @endif
	        @if($key > 0)

	        @if($key == 0)
	        @endif

	        @if(date('Y-m-d') > $row->date)
	           <tr>
	                <td>{{date('d M, Y', strtotime($row->date))}}</td>
	                <td>{{$currency}} {{number_format(($price * $row->percentage)/100)}}</td>
	           </tr>
	        @else
	            <tr>
	            	<td>{{date('d M, Y', strtotime($row->date))}}</td>
	            	<td>{{$currency}} {{number_format(($price * $row->percentage)/100)}}</td>
	            </tr>
	        @endif
	           
	        @endif
	    @endforeach
	</table>
	<p><b>For more departure details</b>: <a href="http://departurecloud.com/login">Login Now</a> </p>

@endif