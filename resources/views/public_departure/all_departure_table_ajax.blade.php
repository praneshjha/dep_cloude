@if(count($departures)> 0 )                
    @foreach( $departures as $key => $departure )
        
        <tr class="paginate_remove">
            <td>{{ $departure->dep_id }}</td>
            <td><a href="{{route('all_departure_details',$departure->id)}}" title="{{$departure->title}}" data-toggle="tooltip">{{$departure->title}}</a></td>
            <td><a href="{{url('profile/'.$departure->company_url)}}" class="userprofileName">{{$departure->departure_ownner}}</a></td>
            <td>{{date('d M, Y', strtotime($departure->start_date))}}</td>
            <td>
                @if($departure->no_of_nights == null || $departure->no_of_days == null)
                    
                @else
                    {{$departure->no_of_nights}}N/{{$departure->no_of_days}}D
                @endif
            </td>
            <td>{{strtok($departure->from, ",")}}</td>
            <td>{{$departure->ending_at}}</td>
            <td>
                @if(($departure->total_seat)-($departure->hold_sum + $departure->book_sum) > 0)
                    {{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}
                @endif

            </td>
            <td>
                @foreach($departure->price as $price)
                    {{$price->currency_symbol}}  {{$price->price}}
                @endforeach
            </td>
            <td>
                <span class="badge bg-success">OPEN</span>
            </td>
        </tr>
    @endforeach                          
@endif 
     