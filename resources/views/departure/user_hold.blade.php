@extends('layouts.app')
@section('tagSection')
<title>Users Hold | Departure Cloud</title>
@endsection
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Hold</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Hold</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <nav>
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="{{route('user_booking',$id)}}" class="nav-link ">Booked</a></li>
                                        <li class="nav-item"><a href="{{route('user_holding',$id)}}" class="nav-link active"> Hold </a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                   <th>Dep. ID</th>
                                                   <th>Dep. Name</th>
                                                   <th>Dep. From</td>
                                                   <th>Dep. To</td>
                                                   <th>No. of D/N</th>
                                                   <th>Hold Duration</th>
                                                   <th>Hold Date</th>
                                                   <th>Hold Unit</th>
                                                   <th>Total Price</th>
                                                   <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               @foreach($hold as $key => $row)
                                             <tr>
                                                <td>{{ $row->departure->dep_id }}</td>
                                                
                                                <td><a href="{{route('all_departure_details',$row->departure->id)}}" style="text-decoration:none">{{$row->departure->title}}</a></td>
                                                <td>{{$row->departure->from}}</td>
                                                <td>{{$row->departure->ending_at}}</td>
                                                <td>{{$row->departure->no_of_days}}/{{$row->departure->no_of_nights}}</td>
                                                <td>{{$row->bookings->hold_duration}}</td>
                                                <td>{{date('d M, Y', strtotime($row->bookings->created_at))}}</td>
                                                <td>{{$row->booked_seat}}</td>
                                                <td>{{$row->bookings->currency_symbol}} {{$row->price * $row->booked_seat}}</td>
                                                <td><a href="{{route('departure_hold_history_details',$row->unique_id)}}"><i class="fa fa-eye" title="Booking Details"></i></a></td>
                                             </tr>
                                             @endforeach
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerSection')

<script type="text/javascript">
  $(".user-change").click(function () {
    if (confirm("Are you sure you provide buyer this user?"))
    var id = $(this).data("id");
    var status = $(this).data("status");
    var flag = (status == 0)?'Buyer':'Buyer & Supplier';
    var token = "{{ csrf_token() }}";
    if(id){
    $.ajax({
      url: '/user-type-change/' + id,
      type: 'POST',
      data: {
          "id": id,
          "_token": token,
      },
      success: function (data) {
        window.location.reload();
      }
    });
    }
  });
</script>
<script type="text/javascript">
  $(".status").click(function () {
    if (confirm("Are you sure you want active this user?"))
    var id = $(this).data("id");
    var status = $(this).data("status");
    
    var flag = (status == 0)?'Varified':'Unvarified';
    
    var token = "{{ csrf_token() }}";
    if(id){
    $.ajax({
      url: '/user-status-change/' + id,
      type: 'POST',
      data: {
          "id": id,
          "_token": token,
      },
     
      success: function (data) {
        window.location.reload();
      }
    });
    }
  });
</script>
<script type="text/javascript">
  $(".status1").click(function () {
    if (confirm("Are you sure you want inactive this user?"))
    var id = $(this).data("id");
    var status = $(this).data("status");
    
    var flag = (status == 0)?'Varified':'Unvarified';
    
    var token = "{{ csrf_token() }}";
    if(id){
    $.ajax({
      url: '/user-status-change/' + id,
      type: 'POST',
      data: {
          "id": id,
          "_token": token,
      },
     
      success: function (data) {
        window.location.reload();
      }
    });
    }
  });
</script>

@endsection