<title>{{$departure_details->title}} | {{$departure_details->company_name}}</title>
<meta property="og:title" content="{{$departure_details->title}}">
<meta property="og:type" content="DepartureCloud"/>
<meta property="og:url" content="{{URL::current()}}">
<meta property="og:description" content="{{ strip_tags(htmlspecialchars_decode(Str::limit($departure_details->description, 200, ' ...'))) }}">
<meta property="og:image" content="{{$departure_details->logo_image}}">
<meta name="twitter:card" content="summary">
<meta name="twitter:creator" content="@Departure-Cloud">
<meta name="twitter:url" content="{{URL::current()}}">
<meta name="twitter:title" content="{{$departure_details->title}}">
<meta name="twitter:description" content="{{ strip_tags(Str::limit($departure_details->description, 200, ' ...')) }}">
<meta name="twitter:image" content="{{$departure_details->logo_image}}">