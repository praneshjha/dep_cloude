@if($senTo == 'wat')
	<h1>Booked Departure (Company Name)- {{Auth::user()->company_name}}</h1>
	<h2>Name - {{Auth::user()->name}}</h2>
	<h2>Email - {{Auth::user()->email}}</h2>
	<h2>Phone - {{Auth::user()->mobile}}</h2>

	<h3>Departure - {{$departure->title}}</h3>
	<h3>Book Units - {{$book_seat}}</h3>
	<h3>Single Supplement Book Unit - {{$single_supplement_booked_seat}}</h3>
	@if(ucfirst(auth::user()->country) == 'India')
	<h3>Total Price - Rs. {{intval($book_seat * $departure->price_inr) + ($single_supplement_booked_seat * $departure->single_supplyment_price_inr )}}</h3>
	@else
	<h3>Total Price - $ {{intval($book_seat * $departure->price_usd) + ($single_supplement_booked_seat * $departure->single_supplyment_price_usd)}} </h3>
	@endif

	<h3>Book Date - {{date("d-m-Y H:i:s")}}</h3>
	<h3>Note - {{$note}}</h3>
@else
	<h1>Dear,</h1>
	<h2>{{Auth::user()->name}}</h2>

	<h3>Departure - {{$departure->title}}</h3>
	<h3>Book Units - {{$book_seat}}</h3>
	<h3>Single Supplement Book Unit - {{$single_supplement_booked_seat}}</h3>
	@if(ucfirst(auth::user()->country) == 'India')
	<h3>Total Price - Rs. {{intval($book_seat * $departure->price_inr) + ($single_supplement_booked_seat * $departure->single_supplyment_price_inr )}}</h3>
	@else
	<h3>Total Price - $ {{intval($book_seat * $departure->price_usd) + ($single_supplement_booked_seat * $departure->single_supplyment_price_usd)}} </h3>
	@endif

	<h3>Booking Date - {{date("d-m-Y H:i:s")}}</h3>
	<h3>Note - {{$note}}</h3>
@endif
