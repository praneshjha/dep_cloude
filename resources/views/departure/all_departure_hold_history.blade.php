@extends('layouts.app')
@section('tagSection')
<title>All Hold History | Departure Cloud</title>
@endsection
@section('content')
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Hold History</h4>
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
                                    <li class="nav-item"><a href="{{route('all_departure_booking_history')}}" class="nav-link "> Confirmed</a></li>
                                    <li class="nav-item"><a href="{{route('all_departure_hold_history')}}" class="nav-link active"> Hold </a></li>
                                </ul>
                            </nav>
                        </div>
                           <!--  <div class="col-md-12 d-flex align-items-center justify-content-between">
                                <h3>Hold History
                                    <span class="btn btn-info btn-sm">Total Hold<span style="color:#ffeb00"> {{$total}}</span> </span>
                                </h3>
                            </div> -->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped"> 
                                        <thead>
                                            <tr>
                                                <th>Sl.</th>
                                                <th>Deparute Name</th>
                                                @if(Auth::user()->main_user_type == 2)
                                                <th>Suppliers</th>
                                                @endif
                                                <th>Buyer/Booker</th>
                                                <th>Hold Date</th>
                                                <th>Hold Units</th>
                                                <th>Price</th>
                                    
                                                <th>Action</th>
                                            </tr>
                                         </thead>
                                         <tbody>
                                                @if(count($hold)> 0 )
                                                @foreach($hold as $key=>$row)
                                                    <tr>

                                                        <td>{{$key+1}}</td>
                                                        <td> <a href="{{url('/departures-details/'.$row->departure->id)}}" style="text-decoration:none;">{{$row->departure->title}}</a></td>
                                                        @if(Auth::user()->main_user_type == 2)
                                                        <th>{{$row->departure->company_name}}</th>
                                                        @endif
                                                        <td>{{$row->name}} / {{$row->full_name}}</td>

                                                        <td>{{date('d-M-Y h:i a', strtotime($row->hold_value->created_at."+5 hours +30 minutes"))}}</td>
                                                        <td>{{$row->booked_seat}}</td>
                                                        <td>{{$row->currency->currency_symbol}} {{$row->price}}</td>
                                                        <td><a href="{{url('/departures-hold-history-details/'.$row->hold_value->unique_id)}}" class="" title="More Details"><i class="fa fa-eye"></i></a> || 
                                                        <a href="javascript:void(0);" class="releaseHold" data-id="{{$row->hold_value->unique_id}}" data-status="" title="Release"><i class="fa fa-undo"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                               
                                                @endif
                                         </tbody>
                                     </table>
                                     {{$hold->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(session()->has('msg'))
      <div class="modal fade" id="myModal" role="dialog" style="">
        <div class="modal-dialog modal-sm" >
          <div class="modal-content">
            <div class="modal-body text-center">
              <h5>{{ session()->get('msg') }}</h5>
              <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Ok</button>
            </div>
          </div>
        </div>
      </div>
      @endif
@endsection
@section('footerSection')
<script type="text/javascript">
    $(".disableDepartue").click(function() {
      if(confirm("Are you sure you want to release this?")) var id = $(this).data("id");
      var token = "{{ csrf_token() }}";
      if(id) {
        $.ajax({
          url: '/hold/departure/release/' + id,
          type: 'POST',
          data: {
            "id": id,
            "_token": token,
          },
          success: function(data) {
            window.location.reload();
          }
        });
      }
    });

    $(".releaseHold").click(function () {
        if (confirm("Are You sure, You want to release?"))
        var id = $(this).data("id");
        var status = $(this).data("status");
        var token = "{{ csrf_token() }}";
        if(id){
            $.ajax({
                url: '/hold/departure/release/' + id,
                type: 'POST',
                data: {
                  "id": id,
                  "_token": token,
                },
                success: function (data) {
                    window.location.href = "{{url('/my-holdings')}}";
                }
            });
        }
    });
</script>
@endsection