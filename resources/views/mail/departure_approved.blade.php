<p>Dear {{$fname}} {{$lname}},</p>

Your Deaparture <b>{{$departure->title}}</b> for <b>{{$departure->ending_at}}</b> on <b>{{date('d M, Y', strtotime($departure->start_date))}}</b> has  been approved and published in Departure Cloud.To view your departure pls <a href="{{route('departure_details',$departure->id)}}">click here.</a>