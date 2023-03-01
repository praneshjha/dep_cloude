<div class="card-box">
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th style="width: 5%;">#</th>
          <th style="width: 5%;">Day</th>
          <th>Heading</th>
          <th>Destination(s)</th>
          <th style="width: 10%;">Status</th>
          <th style="width: 5%;">Action</th>
        </tr>
      </thead>
      <tbody>
        @if(count($itineraries) > 0)
        @foreach($itineraries as $itinerary)
          <tr>
            <td>{{$loop->index +1}}</td>
            <td>{{$itinerary->day_number}}</td>
            <td>{{$itinerary->day_heading}}</td>
            <td>{{$itinerary->destNames}}</td>
            
            <td>
              @if($itinerary->status == '1')
                <a class="disableItinerary" data-id="{{ $itinerary->id }}" data-status="{{ $itinerary->status }}" style="cursor: pointer; color: #2f8263;">
                  Active
                </a>
              @else
                <a class="disableItinerary" data-id="{{ $itinerary->id }}" data-status="{{ $itinerary->status }}" style="cursor: pointer; color: #F9423C;">
                  Inactive
                </a>
              @endif
            </td>
            <td>
              <a href="javascript:void(0);" data-toggle="modal" data-toggle="modal" class="editItinerary" data-id="{{ $itinerary->id }}" data-daynumber="{{ $itinerary->day_number }}" data-dayheading="{{ $itinerary->day_heading }}" data-description="{{ $itinerary->description }}" data-destId="{{ JSON_encode($itinerary->destinations_id) }}" data-destName="{{ JSON_encode($itinerary->destinations_name) }}" title="Edit details" style="cursor: pointer;"><i class="fa fa-edit"></i></a> | 
              <form id="posts-form{{$itinerary->id}}" method="post" action="{{route('itinerary_delete',$itinerary->id)}}" style="display: none;">
                @csrf
                {{method_field('POST')}} <!-- posts query -->
              </form>
                <a href="" onclick="
                if (confirm('Are you sure, You want to delete?'))
                  {
                    event.preventDefault();
                    document.getElementById('posts-form{{$itinerary->id}}').submit();
                  }
                  else
                  {
                    event.preventDefault();
                  }
                " style="cursor: pointer;" title="Delete">
                    <i class="fa fa-trash" style="color: #69204b;cursor: pointer;"></i></a>
            </td>
          </tr>
        @endforeach
      @endif
      </tbody>
    </table>
  </div>
</div>
<div class="box-footer clearfix">
  <ul class="pagination pagination-sm no-margin pull-right">
  </ul>
</div>