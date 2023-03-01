<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<p>
<b>Name :</b> {{$name}}</p>
<p>
<b>Company Name:</b> {{$company_name}}</p>
<p><b>Phone:</b> {{$phone}}</p>
<p><b>Email:</b> {{$email}}</p>
@if(isset($issue))
	<p><b>Issue:</b> {{$issue}}</p>
@endif

{{--<p><b>Preferable Time: </b>{{$time}}</p>
<p><b>Preferable Day: </b>{{$day}}</p>--}}
<p><b>Url : </b>{{$url}}</p>
</body>
</html>