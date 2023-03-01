@extends('layouts.app')
@section('tagSection')
<title>Booking History | Departure Cloud</title>
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
                        <h4 class="page-title"> Booking History</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Booking History</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-rounded-circle card-box bg-transparent px-0">
                <form class="" action="{{route('all_departure_booking_history')}}">
                    <div class="row m-md-0">
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control " name="departure_name" id="departure_name" data-toggle="select2">
                                    <option value="">Select Departure Name</option>
                                    @foreach($filter_departure as $departure)
                                    <option value="{{$departure->title}}" >{{$departure->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control" name="departure_owner" id="departure_company" data-toggle="select2">
                                    <option value="">Select Departure Owner</option>
                                  @foreach($filter_departure_company as $company)
                                    <option value="{{$company->id}}" >{{$company->company_name}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-1 col-lg-1 p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control" name="departure_from" id="departure_from" data-toggle="select2">
                                  <option value="">Dep-From</option>
                                  @foreach($filter_departure_from as $from)
                                    <option value="{{$from->from}}" >{{$from->from}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-1 col-lg-1 p-md-0">
                            <div class="form-group mb-0">
                               <select class="form-control" name="departure_to" id="departure_to" data-toggle="select2">
                                   <option value="">Dep-To</option>
                                  @foreach($filter_departure_to as $to)
                                    <option value="{{$to->ending_at}}" >{{$to->ending_at}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <div class="form-group mb-0">
                                <div class="input-group date">
                                    <input type="text" class="form-control pull-right start_date fromdate" value="{{$start}}" name="start_date" id="start_date" autocomplete="off" placeholder="DD/MM/YYYY">
                                    <div class="input-group-prepend start_calendar" id="#start_calendar">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <div class="form-group mb-0">
                                <div class="input-group date">
                                    <input type="text" class="form-control pull-right end_date fromdate" value="{{$end}}" name="end_date" id="end_date" placeholder="DD/MM/YYYY" autocomplete="off">
                                    <div class="input-group-prepend end_calendar" id="end_calendar">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-1 col-lg-1 p-md-0">
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 0;"><i class="fas fa-search"></i> Search</button>
                        </div>
                        <div class="col-md-1 col-sm-1 col-lg-1 p-md-0">
                            <a href="{{route('all_departure_booking_history')}}" class="btn btn-secondary w-100" style="border-radius: 0;"><i class="fas fa-times"></i> Clear</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-12 mb-2">
                        <nav>
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a href="{{route('all_departure_booking_history')}}" class="nav-link active"> Confirmed</a></li>
                                <li class="nav-item"><a href="{{route('all_departure_hold_history')}}" class="nav-link "> Hold </a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table  class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Deparute Name</th>
                                        @if(Auth::user()->main_user_type == 2)
                                        <th>Suppliers</th>
                                        @endif
                                        <th>Buyer/Booker</th>
                                        <th>Booking Date</th>
                                        <th style="width:5%;">Booking Units</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                        @if(count($book)> 0 )
                                        @foreach($book as $key=>$row)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td> <a href="{{url('/departures-details/'.$row->departure->id)}}" style="text-decoration:none;">{{$row->departure->title}}</a></td>
                                        @if(Auth::user()->main_user_type == 2)
                                        <th>{{$row->departure->company_name}}</th>
                                        @endif
                                        <td>
                                            <a href="{{url('profile/'.$row->company_url)}}" style="text-decoration:none">{{$row->name}}</a> / {{$row->full_name}}
                                        </td>
                                        <td>{{date('d-M-Y h:i a', strtotime($row->created_at."+5 hours +30 minutes"))}}</td>
                                        <td>{{$row->booked_seat}}</td>
                                        <td> {{$row->currency}} {{$row->price}}</td>
                                        <td>
                                            @if($row->booked_value->status == 1) Confirm @else Cancel @endif
                                        </td>
                                        <td>
                                            <a href="{{url('/departures-booking-history-details/'.$row->unique_id)}}" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                            @if($row->booked_value->status == 1)
                                            <?php //dd($row->unique_id); ?>
                                                <a  class="cancel text-danger display_loader" title="Cancel Booking" data-unique_id="{{$row->booked_value->unique_id}}" data-departure_id="{{$row->departure->id}}" ><i class="fa fa-window-close"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                        @endforeach
                                        @endif
                                </tbody>
                            </table>
                            {{$book->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footerSection')

<!-- <script src="{{asset('js/select2.full.min.js')}}"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
<style>
    .ui-datepicker-buttonpane.ui-widget-content {display: none !important;}div#ui-datepicker-div {width: 18% !important;}
</style>
<script type="text/javascript">
    $(".cancel").click(function () {
        if (confirm("Are you sure you want to cancel this booking?"))
        var unique_id = $(this).data("unique_id");
        var departure_id = $(this).data("departure_id");
        var token = "{{ csrf_token() }}";
        if (unique_id) {
            $.ajax({
                url: '/booking-cancel-buyer/'+unique_id,
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
@endsection