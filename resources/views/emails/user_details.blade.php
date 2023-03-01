<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<p>
	<b>Public ID:</b> {{ $public_id }}
	</p>
	<p>
	<p>
	<b>Name:</b> {{ $name }}
	</p>
	<p>
	<b>Mobile:</b> {{ $mobile }}
	</p>
	<p>
	<b>Email:</b> {{ $email }}
	</p>
	<p>
	<b>Company Name:</b> {{ $company_name }}
	</p>
	<p>
	<b>City:</b> {{ $city }}
	</p>
	@if(isset($state))
	<p>
	<b>State:</b> {{ $state }}
	</p>
	@endif
	<p>
	<b>Country:</b> {{ $country }}
	</p>
	<p>
	<b>Address:</b> {{ $address }}
	</p>
	<p>
	<b>Postal code/Zip code:</b> {{ $pin }}
	</p>
	<p>
	<b>User Type:</b> {{ $type }}
	</p>
	@if(isset($url))
	<p>
	<b>Url:</b> <i>{{ $url }}</i>
	</p>
	@endif

</body>
</html>