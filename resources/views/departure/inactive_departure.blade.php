@extends('layouts.app')
@section('tagSection')
<title>Inactive Departures | Departure Cloud</title>
@endsection
@section('content')
<div class="wrapper">
    <div class="wrapperOverlay"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Departed</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                            <li class="breadcrumb-item active">Departed</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="widget-rounded-circle card-box bg-transparent px-0">
            <form class="" action="{{route('inactive_depature')}}">
                <div class="row m-md-0">
                    <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                        <div class="form-group mb-0">
                            <select class="form-control " name="departure_name" id="departure_name" data-toggle="select2" required>
                                <option value="">Select Departure Name</option>
                                @foreach($departure_title as $value)
                                   <option value="{{$value->title}}" @if($value->title == $title) selected @endif >{{$value->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                        <div class="form-group mb-0">
                            <select class="form-control" name="departure_owner" id="departure_company" data-toggle="select2">
                                <option value="">Select Departure Owner</option>
                                @foreach($departure_owner as $value)
                                   <option value="{{$value->departure_ownner}}" @if($value->departure_ownner == $owner) selected @endif>{{$value->departure_ownner}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                        <div class="form-group mb-0">
                            <select class="form-control" name="departure_from" id="departure_from" data-toggle="select2">
                              <option value="">Select Departure From</option>
                               @foreach($departure_from as $value)
                                   <option value="{{$value->departure_from}}" @if($value->departure_from == $from) selected @endif >{{$value->from}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                        <div class="form-group mb-0">
                           <select class="form-control" name="departure_to" id="departure_to" data-toggle="select2">
                               <option value="">Select Departure To</option>
                                 @foreach($departure_to as $value)
                                   <option value="{{$value->ending_at}}" @if($value->ending_at == $to) selected @endif >{{$value->ending_at}}</option>
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
                        <a href="{{route('inactive_depature')}}" class="btn btn-secondary w-100" style="border-radius: 0;"><i class="fas fa-times"></i> Clear</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="widget-rounded-circle card-box">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Dep. Id</th>
                                        <th>Name of Departure</th>
                                        <!-- <th>Destination</th> -->
                                        <th>Departure Date</th>
                                        <th>Nights/Days</th>
                                        <th>Departure From</th>
                                        <th>Departure To</th>
                                        <th>Total Units</th>
                                        <th>Avl. units</th>
                                        <!-- <th>Update Price</th> -->
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    </tbody>
                                    @if(count($departures)> 0 )
                                        @foreach( $departures as $key => $departure )
                                            <tr>
                                                <td>{{ $departure->dep_id }}</td>
                                                <td>
                                                    <a href="{{route('all_departure_details',$departure->id)}}" class="" title="View Departure">
                                                    {{$departure->title}}
                                                    </a>
                                                </td>

                                                <td>
                                                    {{date('d M, Y', strtotime($departure->start_date))}}
                                                </td>
                                                <td>{{$departure->no_of_nights}}N/{{$departure->no_of_days}}D</td>
                                                <td>{{$departure->from}}</td>
                                                <td>{{$departure->ending_at}}</td>
                                                <td>{{$departure->total_seat}}</td>
                                                <td>{{$departure->total_seat}}</td>
                                                <td>
                                                    <a class="badge badge-danger text-light" data-id="{{ $departure->id }}" data-status="{{ $departure->approve }}" style="cursor: pointer; color: #2f8263;">Close
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{route('all_departure_details',$departure->id)}}" class="" title="View Departure"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            </tbody>
                                </table>
                                {{$departures->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <form method="post" name="myEditForm" enctype="multipart/form-data" class="form-inline" id="myEditForm">
        @csrf
        <div class="modal-dialog modal-sm" role="document" style="width: 65%">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="inlineFlax"><h5 class="modal-title" id="exampleModalLabel">Update Pricing</h5></span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="itinerary-setup m-t-20">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div id="pricingModule">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <!--  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" style="background:#dc3545">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="edit_send_form"><i class="fa fa-save"></i> Update</button>
                        <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 5%; display: none;">
                        <span id="mesegess"></span>
                    </div>
                </div>
            </div>
    </form>
</div>
@endsection
@section('footerSection')
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script >
$( document ).ready(function() {
    if($("#departure_name").length){
 $("#departure_name").select2({
    tags: true,/////
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
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<style>
    .ui-datepicker-buttonpane.ui-widget-content {
        display: none !important;
    }
    div#ui-datepicker-div {
        width: 18% !important;
    }
</style>
@endsection