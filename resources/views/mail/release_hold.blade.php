@if($forceRelease == 'yes')
Dear {{$user->name}} {{$user->last_name}}
<p>{{$hold}} units held by you in departure for <b>{{$destination}}</b> are released  by supplier. To contact supplier pls <a href="{{route('login')}}">login</a> to departure cloud.</p>

@else
Dear {{$user->name}} {{$user->last_name}},

<p>{{$hold}} units held by you in departure for <b>{{$destination}}</b> are released as per policy defined by supplier.To hold again pls <a href="{{route('login')}}">login</a> to departure cloud.</p>

@endif
