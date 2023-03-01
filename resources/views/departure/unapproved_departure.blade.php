@extends('layouts.app')
@section('tagSection')
<title>Unapproved Departures | Departure Cloud</title>
@endsection
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Departures Pending for Approval</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Approval</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-rounded-circle card-box bg-transparent px-0">
                <form class="" action="{{route('unapproved_departure')}}">
                    <div class="row m-md-0">
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control " name="departure_name" id="departure_name" data-toggle="select2">
                                    <option value="">Select Departure Name</option>
                                  @foreach( $departures as $value)
                                     <option value="{{$value->title}}" @if($value->title == $title) selected @endif>{{$value->title}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control" name="departure_owner" id="departure_company" data-toggle="select2">
                                    <option value="">Select Departure Owner</option>
                                  @foreach( $departure_ownner as $value)
                                     <option value="{{$value->departure_ownner}}"  @if($value->departure_ownner == $owner) selected @endif>{{$value->departure_ownner}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control" name="departure_from" id="departure_from" data-toggle="select2">
                                  <option value="">Select Departure From</option>
                                  @foreach($from_destination as $value)
                                     <option value="{{$value->from}}" @if($value->from == $from) selected @endif>{{$value->from}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <div class="form-group mb-0">
                               <select class="form-control" name="departure_to" id="departure_to" data-toggle="select2">
                                   <option value="">Select Departure To</option>
                                    @foreach( $end_destination as $value)
                                      <option value="{{$value->ending_at}}"  @if($value->ending_at == $to) selected @endif >{{$value->ending_at}}</option>
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
                            <a href="{{route('unapproved_departure')}}" class="btn btn-secondary w-100" style="border-radius: 0;"><i class="fas fa-times"></i> Clear</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                               <!--  <h3>Pending for Approval
                                   </h3> -->
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Dep. ID</th>
                                            <th >Name of Departure</th>
                                            <th>Destination</th>
                                            <th>Departure From</th>
                                            <th>Departure To</th>
                                            <th>Departure Date</th>
                                            <th>Nights/Days</th>

                                            <th>Total Units</th>
                                            <th>Avalable units</th>
                                            <!-- <th>Update Price</th> -->
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
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
                                                        @foreach($departure->destination as $value)
                                                            {{$value->dest_name}}({{$value->country_name}})
                                                        @endforeach
                                                    </td>
                                                    <td>{{$departure->from}}</td>
                                                    <td>{{$departure->ending_at}}</td>
                                                    <td>
                                                        {{date('d M, Y', strtotime($departure->start_date))}}
                                                    </td>
                                                    <td>{{$departure->no_of_nights}}N/{{$departure->no_of_days}}D</td>

                                                    <td>{{$departure->total_seat}}</td>
                                                    <td>{{$departure->total_seat}}</td>
                                                    <td>
                                                        @if($departure->approve == '1')
                                                            <a class="badge badge-success text-light" data-id="{{ $departure->id }}" data-status="{{ $departure->approve }}" style="cursor: pointer; color: #2f8263;">Approved
                                                            </a>
                                                        @else
                                                            <a class="" data-id="{{ $departure->id }}" data-status="{{ $departure->approve }}" style="cursor: pointer; color: #F9423C;">
                                                                Pending
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{route('all_departure_details',$departure->id)}}" class="" title="View Departure"><i class="fa fa-eye"></i></a>
                                                        @if($departure->approve == '1')
                                                        @else
                                                            <a class=" disableDepartue" data-id="{{ $departure->id }}" data-status="{{ $departure->approve }}" title="Pending Departure">
                                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                            </a>
                                                        @endif
                                                        {{--<a href="{{route('departure_edit',$departure->id)}}" class="" title="Edit Departure"><i class="fa fa-edit"></i></a>--}}
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
            </div>
        </form>
    </div>
@endsection
@section('footerSection')
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <script>
        $('.edit-item').click(function () {
            $("#pricingModule").html('');
            var id = $(this).data("id");
            $('#editModal').modal('show');
            if (id) {
                $('#edit_id').val(id);
                $.ajax({
                    type: "GET",
                    url: "{{url('/get_pricing_ajax')}}?departure_id=" + id,
                    success: function (res) {
                        if (res && res.length > 0) {
                            var html = '';
                            for (data of res) {
                                if (data.pricing && data.pricing.price_inr) {
                                    var priceInr = data.pricing.price_inr ? data.pricing.price_inr : '';
                                    var priceUsd = data.pricing.price_usd ? data.pricing.price_usd : '';
                                } else {
                                    var priceInr = '';
                                    var priceUsd = '';
                                }
                                html += '<div class="row"><div class="col-md-12 col-lg-12 col-xl-12 pl-4" style="marg><label class="labelClass">' + data.type + ' (' + data.name + ')</label><span class="validationError days_error" id="error_price_inr_' + data.id + '"></span><div class="form-group"><div class="row"><div class="col-md-1 col-lg-1 col-xl-1"><input type="text" class="form-control" name="symbol_inr[' + data.id + ']" value="' + data.symbol_inr + '" readonly><input type="hidden" class="form-control" name="price_type_id[]" value="' + data.id + '"></div><div class="col-md-2 col-lg-2 col-xl-2"><input type="text" class="form-control" name="price_inr[' + data.id + ']" id="price_inr_' + data.id + '" value="' + priceInr + '"></div></div><div class="row"><div class="col-md-1 col-lg-1 col-xl-1"><input type="text" class="form-control" name="symbol_usd[' + data.id + ']" value="' + data.symbol_usd + '" readonly></div><div class="col-md-2 col-lg-2 col-xl-2"><input type="text" class="form-control" name="price_usd[' + data.id + ']" id="price_usd_' + data.id + '" value="' + priceUsd + '"></div></div></div></div></div>';
                            }
                            $("#pricingModule").html(html);

                        } else {
                            $("#pricingModule").empty();
                        }
                    }
                });
            } else {
                $("#pricingModule").empty();
            }
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#edit_send_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var price_inr_1 = $('#price_inr_1').val();
                if (price_inr_1 == "") {
                    $("span#error_price_inr_1").html('This field is required!');
                    $("input#price_inr_1").focus();
                    return false;
                }

                $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('price_update') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Price has been updated successfully!</span>");
                        //location.reload();
                    },
                    statusCode: {
                        504: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                        },
                        500: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                        },
                        502: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                        },
                        400: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Bad request please try again later!</span>");
                        },
                        422: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                        },
                        404: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Not Found please try again later!</span>");
                        },
                        401: function () {
                            $('#gif').hide();
                            $('#mesegese').html("<span class='sussecmsg'>Not authorized wrong please try again later!</span>");
                        }
                    },
                    errors: function () {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Something went wrong please try again later!</span>");
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $(".disableDepartue").click(function () {
            if (confirm("Are you sure you want to approve this departure?"))
            var id = $(this).data("id");
            var status = $(this).data("status");
            var flag = (status == 1) ? 'Unapproved' : 'Approved';
            var token = "{{ csrf_token() }}";
            if (id) {
                $.ajax({
                    url: '/departure-approve/' + id,
                    type: 'POST',
                    data: {
                        "id": id,
                        "_token": token,
                    },
                    success: function (data) {
                        window.location.reload();
                        //window.location.href = "{{route('all_departure')}}";
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