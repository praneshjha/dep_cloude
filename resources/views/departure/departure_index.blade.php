@extends('layouts.app')
@section('tagSection')
    <title>My Departures | Departure Cloud</title>
@endsection
@section('headCssSection')
@endsection
@section('content')
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4><span class="page-title">My Departures</span> <span style="margin-left: 25px;color:color: #080808;;">Total:{{$totalDep}}/Open:{{$openDep}}</span></h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Departure</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="alert text-light alert-dismissible fade show " id="alert" role="alert" style="">
                    <div id="success_msg" style="">
                        {{session('success')}}
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12 d-md-flex align-items-center justify-content-between mb-2">
                    @if(request()->route()->uri!="my-departures/table-view")
                    <form class="app-search" method="get" action="{{route('departure')}}">
                    @else
                    <form class="app-search" method="get" action="{{route('departure-table')}}">
                    @endif
                        <div class="app-search-box">
                            <div class="input-group">
                                <select class="form-control" name="status">
                                    <option value="">Status</option>
                                    <option value="5" @if($status == 5) selected @endif>OPEN</option>
                                    <option value="4" @if($status == 4) selected @endif>SOLDOUT</option>
                                    <option value="3" @if($status == 3) selected @endif>DRAFT</option>
                                    <option value="2" @if($status == 2) selected @endif>UNDER REVIEW</option>
                                    <option value="1" @if($status == 1) selected @endif>CLOSE</option>
                                </select>
                                <div class="col p-md-0">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control datepicker" name="from_date" id="from_date" placeholder="From date" autocomplete="off" value="{{$from_date}}">
                                    </div>
                                </div>
                                <div class="col p-md-0">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control  datepicker" name="to_date" id="to_date" placeholder="To date" autocomplete="off" value="{{$to_date}}">
                                    </div>
                                </div>
                                <input type="text" class="form-control" placeholder="Search by Dep ID, title, Ex, dep to.." value="{{$keyword}}" name="keyword" style="margin-left:5px;">
                                <div class="input-group-append" style="margin-right: 5px;">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fe-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="dataView d-flex align-items-center">
                        @can('departure_create', $permission)
                            <div class="dropdown createnewdep">
                                <button class="btn btn-primary dropdown-toggle btn-sm mr-1" type="button" title="Create New Departure" id="createDepartureBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i></button>
                                <div class="dropdown-menu " aria-labelledby="createDepartureBtn">
                                    @foreach($departure_types as $departure_type)
                                        <a class="dropdown-item" href="{{url('departure-basic-detail-create')}}/{{$departure_type->id}}">{{$departure_type->name}}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endcan
                        @if(in_array('departure-all-booking-history',$permission) == 1)
                            <a class="btn btn-info btn-sm" href="{{route('all_departure_booking_history')}}" style="">All Booking</a>
                        @endif
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a href="@if(Route::currentRouteName() == 'departure') javascript:void(0) @else {{route('departure')}} @endif" @if(Route::currentRouteName() == 'departure') class="active" @endif>
                                    <i class="fas fa-th"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="@if(Route::currentRouteName() == 'departure-table') javascript:void(0) @else {{route('departure-table')}} @endif" @if(Route::currentRouteName() == 'departure-table') class="active" @endif>
                                    <i class="fas fa-list"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box1 bg-transparent pb-0">
                        <div class="row">
                            @if(Route::currentRouteName() == 'departure')
                                @include('departure/departure_index_data')
                            @elseif(Route::currentRouteName() == 'departure-table')
                                @include('departure/departure_index_data_table')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footerSection')
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
            @csrf
            <div class="modal-dialog modal-xl" role="document" style="width: 65%">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="inlineFlax"><h5 class="modal-title" id="exampleModalLabel">Update Pricing</h5></span>
                        <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <i class="fa fa-close"></i></button></span>
                    </div>
                    <div class="modal-body">
                        <div class="itinerary-setup m-t-20">
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div id="pricingModule">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <!--  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                            <button type="submit" class="btn btn-primary" id="edit_send_form"><i class="fa fa-save"></i> Update</button>
                            <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 5%; display: none;">
                            <span id="mesegess"></span>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $('.edit-item').click(function () {
            var id = $(this).data("id");
            $('#editModal').modal('show');
        });
    </script>
     <script type="text/javascript">
        $(document).ready(function () {
            $('#from_date').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                dateFormat: 'dd-M-yy',
            });
            $('#to_date').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                dateFormat: 'dd-M-yy',
            });

            $('#countryUrl').select2({
                placeholder:'Select Country',
            });
        });
    </script>

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
                                html += '<div class="row"><div class="col-md-12 col-lg-12 col-xl-12" style="margin-bottom: 20px;"><label class="labelClass">' + data.type + ' (' + data.name + ')</label><span class="validationError days_error" id="error_price_inr_' + data.id + '"></span><div class="form-group"><div class="col-md-1 col-lg-1 col-xl-1"><input type="text" class="form-control" name="symbol_inr[]" value="' + data.symbol_inr + '"><input type="hidden" class="form-control" name="price_type_id[]" value="' + data.id + '"></div><div class="col-md-5 col-lg-5 col-xl-5"><input type="text" class="form-control" name="price_inr[' + data.id + ']" id="price_inr_' + data.id + '" value="' + priceInr + '"></div><div class="col-md-1 col-lg-1 col-xl-1"><input type="text" class="form-control" name="symbol_usd[]" value="' + data.symbol_usd + '"></div><div class="col-md-5 col-lg-5 col-xl-5"><input type="text" class="form-control" name="price_usd[' + data.id + ']" id="price_usd_' + data.id + '" value="' + priceUsd + '"></div></div></div></div>';
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
        $(document).ready(function () {
            $(".disableDepartue").click(function () {
                var id = $(this).data("id");
                var status = $(this).data("status");
                var flag = (status == 2) ? 'inactive' : 'active';
                var token = $("meta[name='csrf-token']").attr("content");
                if (confirm("Are you sure you want to " + flag + " this departure?"))
                    $.ajax(
                        {
                            url: '/departure-disable/' + id,
                            type: 'POST',
                            data: {
                                "id": id,
                                "_token": token,
                            },
                            success: function (data) {
                                window.location.reload();
                            }
                        });
            });
        });
    </script>
    <script>
        $(window).on('hashchange', function () {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    getData(page);
                }
            }
        });

        $(document).ready(function () {
            $(document).on('click', '.pagination a', function (event) {
                $('#departureListData').addClass('loading');
                event.preventDefault();

                $('li').removeClass('active');
                $(this).parent('li').addClass('active');

                var myurl = $(this).attr('href');
                var page = $(this).attr('href').split('page=')[1];

                getData(page);
            });

        });

        function getData(page) {
            $.ajax(
                {
                    url: '',
                    type: "get",
                    datatype: "html"
                }).done(function (data) {
                $('#departureListData').removeClass('loading');
                $("#dataIndex").empty().html(data);
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        }
    </script>

    <style>
        #alert {
            display: flex;
            justify-content: center;
            height: 100%;
            position: fixed;
            z-index: 999;
            bottom: 0px;
            width: 100%;

        }

        #success_msg {
            background: #0a3f8e;
            position: absolute;
            background: #0a3f8e;
            position: absolute;
            bottom: 46px;
            padding: 9px 42px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid white;
        }
    </style>
@endsection