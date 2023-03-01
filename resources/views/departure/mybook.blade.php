@extends('layouts.app')
@section('tagSection')
<title>My Bookings | Departure Cloud</title>
@endsection
@section('content')
<style>
     .select2-dropdown{
      z-index: 999;
    }
</style>
<link href="{{('assets/css/components/custom-modal.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">
                            Confirmed Booking
                        </h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Booking</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-rounded-circle card-box bg-transparent px-0">
                <form class="phoneForm" action="{{route('my_booking')}}">
                    <div class="row m-md-0">
                        <div class="col p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control " name="departure_name" id="departure_name" data-toggle="select2" style="width:100%">
                                    <option value="">Select Departure Name</option>
                                    @foreach($filter_departure as $departure)
                                    <option value="{{$departure->title}}" @if($departure_name == $departure->title) selected @endif>{{$departure->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control" name="departure_owner" id="departure_company" data-toggle="select2" style="width:100%">
                                    <option value="">Select Departure Owner</option>
                                  @foreach($filter_departure_company as $company)
                                    <option value="{{$company->departure_ownner}}" @if($departure_owner == $departure->departure_ownner) selected @endif>{{$company->departure_ownner}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control" name="departure_from" id="departure_from" data-toggle="select2" style="width:100%">
                                  <option value="">Select Departure From</option>
                                  @foreach($filter_departure_from as $from)
                                    <option value="{{$from->from}}" @if($departure_from == $from->from) selected @endif>{{$from->from}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col p-md-0">
                            <div class="form-group mb-0">
                               <select class="form-control" name="departure_to" id="departure_to" data-toggle="select2" style="width:100%">
                                   <option value="">Select Departure To</option>
                                  @foreach($filter_departure_to as $to)
                                    <option value="{{$to->ending_at}}" @if($departure_to == $to->ending_at) selected @endif>{{$to->ending_at}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col p-md-0">
                            <div class="form-group mb-0">
                                <div class="input-group date">
                                    <input type="text" class="form-control pull-right start_date fromdate" value="{{$start}}" name="start_date" id="start_date" autocomplete="off" placeholder="DD/MM/YYYY">
                                    <div class="input-group-prepend start_calendar" id="#start_calendar">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col p-md-0">
                            <div class="form-group mb-0">
                                <div class="input-group date">
                                    <input type="text" class="form-control pull-right end_date fromdate" value="{{$end}}" name="end_date" id="end_date" placeholder="DD/MM/YYYY" autocomplete="off">
                                    <div class="input-group-prepend end_calendar" id="end_calendar">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col p-md-0">
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 0;"><i class="fas fa-search"></i> Search</button>
                        </div>
                        <div class="col p-md-0">
                            <a href="{{route('my_booking')}}" class="btn btn-secondary w-100" style="border-radius: 0;"><i class="fas fa-times"></i> Clear</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <nav>
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="{{route('my_booking')}}" class="nav-link active @if(Route::currentRouteName() == 'my_booking') active @endif"> Confirmed</a></li>
                                        <li class="nav-item"><a href="{{route('my_holding')}}" class="nav-link @if(Route::currentRouteName() == 'my_holding') active @endif"> Hold </a></li>
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
                                           <th>Dep. Owner</th>
                                           <th>Dep. From</td>
                                           <th>Dep. To</td>
                                           <th>No. of D/N</th>
                                           <th>Booking Date & Time</th>
                                           <th>Booked Unit</th>
                                           <th width="100">Total Price</th>
                                           <th>Action</th>
                                        </tr>
                                        </thead>
                                            <tbody>
                                             
                                              <?php
                                                 $today = date("Y-m-d");
                                                 $date1=date_create($today);
                                                 $date2=date_create();
                                                 $diff=date_diff($date1,$date2);
                                                 $date = $diff->format("%R%a");
                                              ?>
                                             @foreach($mybook as $key => $row)
                                             <tr>
                                                <td>{{ $row->departure->dep_id }}</td>
                                                
                                                <td><a href="{{route('all_departure_details',$row->departure->id)}}" style="text-decoration:none">{{$row->departure->title}}</a></td>
                                                <td><a href="{{url('profile/'.$row->company_url)}}" style="text-decoration:none">{{$row->departure->company_name}}</a></td>
                                                <td>{{$row->departure->from}}</td>
                                                <td>{{$row->departure->ending_at}}</td>
                                                <td>{{$row->departure->no_of_days}}/{{$row->departure->no_of_nights}}</td>
                                                <td>{{$row->bookingDate}}</td>
                                                <td>{{$row->booked_seat}}</td>
                                                <td>{{$row->currency->currency_symbol}} {{number_format($row->price)}}</td>
                                                <td>
                                                @if($row->book_value->status == 1)
                                                <a href="{{url('/departures-booking-history-details/'.$row->book_value->unique_id)}}" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                                @if($row->departure->start_date > date("Y-m-d"))
                                                <a  class="cancel text-danger display_loader" title="Cancel Booking" data-unique_id="{{$row->book_value->unique_id}}" data-departure_id="{{$row->departure->id}}" ><i class="fa fa-window-close"></i></a>
                                                @endif
                                                @else
                                                  <strong class="text-danger">Canceled</strong>
                                                @endif
                                                </td>
                                             </tr>
                                             @endforeach
                                             
                                            </tbody>
                                      </table>
                                      {{$mybook->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container-loader">
    <div style="margin:auto;">
        <div class="loader" >
            <div class="circle" id="a"></div>
            <div class="circle" id="b"></div>
            <div class="circle" id="c"></div>
        </div>
        <div class="caption">We are almost there...</div>
    </div>
</div>
@endsection
@section('footerSection')
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    $(".cancel").click(function () {
        if (confirm("Are you sure you want to cancel this booking?"))
        var unique_id = $(this).data("unique_id");
        var departure_id = $(this).data("departure_id");
        var token = "{{ csrf_token() }}";
        if (unique_id) {
            $.ajax({
                url: '/booking-cancel/'+unique_id,
                type: 'POST',
                data: {
                    "unique_id": unique_id,
                    "departure_id":departure_id,
                    "_token": token,
                },
                success: function (data) {
                    window.location.reload();

                   // window.location.href = "{{route('all_departure')}}";
                }
            });
        }
    });
</script>
<script >
$( document ).ready(function() {
    if($("#departure_name").length){
         $("#departure_name").select2({
            tags: true,
          });
    }
 $("#departure_company").select2({
    tags: true,
  });
 $("#departure_from").select2({
    tags: true,
  });
 $("#departure_to").select2({
    tags: true,
  });
 });
$( document ).ready(function() {
    $('.start_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'dd-M-yy',
        //minDate: 0,
    });
$('.start_calendar').click(function() {
     $(".start_date").focus();
    });
});
$( document ).ready(function() {
    $('.end_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'dd-M-yy',
        //minDate: 0,
    });
$('.end_calendar').click(function() {
     $(".end_date").focus();
    });
});
</script>
<script>
$(document).ready(function(){
    $('.display_loader').click(function(){
        // $('.container-loader').toggle();
        // $('.container-loader').show();
        $('.container-loader').css('display', 'flex');
    });
});
</script>
<style>
    .ui-datepicker-buttonpane.ui-widget-content {display: none !important;}div#ui-datepicker-div {width: 18% !important;}
</style>
<style>
.container-loader {
    display: none;
    flex-direction: column;
    margin: auto;
    position: fixed;
    top: 0;
    width: 100%;
    background-color: rgb(181 181 181 / 70%);
    height: 100%;
    z-index: 9999;
}
.loader {
  width: 200px;
  height: 80px;
  padding-top: 40vh;
  margin: auto;
  display: flex;
}
.circle {
  width: 30px;
  height: 30px;
  background: white;
  border-radius: 50%;
  animation: jump 1s linear infinite;
  margin: 0 15px;
}
.caption {
  margin: auto;
  font-family: arial;
  font-size: 20px;
  color: white;
  margin-top: 36px;
}
#b {
  animation-delay: 0.2s;
}
#c {
  animation-delay: 0.4s;
}
@keyframes jump {
  0% {
    margin-top: 0;
  }
  35% {
    margin-top: -75px;
  }
  70% {
    margin-top: 0px;
  }
}
</style>
@endsection