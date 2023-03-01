Dear <b>{{$fname}} {{$lname}}</b>
<p>You have received request to book {{$book_seat}} units in your departure.</p>
<p><b>Deaparture Name: </b> {{$departure->title}}</p>
<p><b>Departure Date: </b> {{date('d M, Y', strtotime($departure->start_date))}}</p>
<p><b>Buyer Company Name: </b> {{auth()->user()->company_name}}</p>
<p><b>Booked Request By:</b> {{auth()->user()->name}} {{auth()->user()->last_name}}</p>
<p><b>No of units booked: </b> {{$book_seat}}</p>
<b>Booking Date & Time: </b> {{date('d M, Y h:i A', strtotime("+5 hours +30 minutes"))}}
<p><b>Booking Details: </b></p>
<table border="1">
    <tr>
        @if(in_array(32, json_decode($columns)))
            <th>Room Sharing</th>
        @endif
        @if(in_array(33, json_decode($columns)))
            <th>Flight Class</th>
            <th>Age Bracket</th>
        @endif
        @if(in_array(35, json_decode($columns)))
            <th>Hotel Name</th>
            <th>Hotel Category</th>
        @endif
        @if(in_array(36, json_decode($columns)))
            <th>Transport Type</th>
        @endif
            <th>Airport Transfers</th>
        @if(in_array(38, json_decode($columns)))
            <th>Meal Plan</th>
        @endif
        <th>Price</th>
    </tr>
    @foreach($book_details_mail as $row)
        <tr>
            @if(in_array(32, json_decode($columns)))
                <td>{{$row->sairing}}</td>
            @endif
            @if(in_array(33, json_decode($columns)))
                <td>{{$row->flight_class}}</td>
                <td>{{$row->passenger}}</td>
            @endif
            @if(in_array(35, json_decode($columns)))
                <td>{{$row->hotel_name}}</td>
                <td>{{$row->hotel_type}}</td>
            @endif
            @if(in_array(36, json_decode($columns)))
                <td>{{$row->transport_type}}</td>
            @endif
            <td>{{$row->airport_transfers}}</td>
            @if(in_array(38, json_decode($columns)))
                <td>{{$row->meal_plan}}</td>
            @endif
            <td>{{$row->currency_symbol}} {{($row->price)}}</td>
        </tr>
    @endforeach
</table>

<p><b>Payment Schedule: </b></p>
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

<p><b>Cancellation Schedule: </b></p>
	<table border="1">
        <?php $count = 0;?>
        @foreach($cancel_schedule as $key=>$cancel)
            <?php  
                    if(date('Y-m-d') > $cancel->date)
                    {
                    $count = $count + $cancel->percentage;
                    }
            ?>
        @endforeach

        <?php
            echo '<th>Minimum Cancelation Charge</th><td>'.$currency .'-' .number_format(($price * $count)/100).'</td>';
        ?>
    @foreach($cancel_schedule as $key=>$cancel)
        @if($key == 0)
            @if(date('Y-m-d') < $cancel->date)
             <th>Minimum Cancelation Charge</th> : <td>{{$currency}} - {{number_format(($price * $cancel->percentage)/100)}}</td>
            @endif  
        @endif
        @if($key > 0)

        @if($key == 0)
        @endif

        @if(date('Y-m-d') > $cancel->date)
           <tr>
                <td>{{date('d M, Y', strtotime($cancel->date))}}</td>
                <td>{{$currency}} {{number_format(($price * $cancel->percentage)/100)}}</td>
           </tr>
        @else
            <tr>
            	<td>{{date('d M, Y', strtotime($cancel->date))}}</td>
            	<td>{{$currency}} {{number_format(($price * $cancel->percentage)/100)}}</td>
            </tr>
        @endif
           
        @endif
    @endforeach
</table>
<p><b>For More details pls click here: </b> <a href="http://departurecloud.com/login">Login Now</a></p>

