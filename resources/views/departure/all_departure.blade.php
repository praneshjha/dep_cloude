@extends('layouts.app')
@section('tagSection')
    <title>All Departures | Departure Cloud</title>
@endsection
@section('headCssSection')
<style type="text/css">
    .select2-container .select2-selection--multiple .select2-search__field {
        width: 100% !important;
    }
</style>
@endsection
@section('content')
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">All Departures</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departure Cloud</a></li>
                                <li class="breadcrumb-item active">All Departures</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="widget-rounded-circle card-box1 bg-transparent pb-0 px-0">

            <div class="row m-md-0">
                <div class="col-md-12 p-md-0 d-flex align-items-center justify-content-between">
                    <h4 class="mt-0 text-muted"></h4>
                    <div class="dataView">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item" id="keywords_filter">
                                <!-- <form class="" action="" > -->
                                    <div class="form-group position-relative mb-0">
                                        <!-- <div class="input-group"> -->
                                            @if($search_key)
                                            <input type="text" class="form-control" name="autoSearch" id="autoSearch" placeholder="Search..." autocomplete="off" value="{{$search_key}}">
                                            @else
                                            <input type="text" class="form-control" name="autoSearch" id="autoSearch" placeholder="Search..." autocomplete="off" value="{{$keywords}}">
                                            @endif
                                            <i class="fas fa-search"></i>
                                            <div id="autoSearchData">
                                           <!--  <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search mr-1"></i> Search</button>
                                            </div> -->
                                        <!-- </div> -->
                                    </div>
                                <!-- </form> -->
                            </li> 
                            <li class="list-inline-item"><a href="javascript:void(0);" onclick="toggle_filter()" title="Advance Filter" id="filter_icn"><i class="fas fa-filter"></i></a></li>
                            <li class="list-inline-item"><a href="@if(Route::currentRouteName() == 'all_departure') javascript:void(0) @else {{route('all_departure')}} @endif"  @if(Route::currentRouteName() == 'all_departure') class="active" @endif><i class="fas fa-th"></i></a></li>
                            <li class="list-inline-item"><a href="@if(Route::currentRouteName() == 'all_departure-table') javascript:void(0) @else {{route('all_departure-table')}} @endif"
                                @if(Route::currentRouteName() == 'all_departure-table') class="active" @endif><i class="fas fa-list"></i></a></li>
                        </ul>
                    </div>
                </div>
                <form class="col-md-12" action="{{route('all_departure')}}"  id="advance_filter" style="display:none" >
                    <div class="d-none1 row advnc_filter">
                        <input type="hidden" class="form-control" name="type" id="req_type" value="{{$req_type}}">

                        <div class="col p-md-0 mt-1">
                            <div class="form-group mb-0 position-relative">
                                <select class="form-control " name="departure_from" id="departure_from" data-toggle="select2" style="width:100%">
                                    <option value="{{$from}}">{{$from}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col p-md-0 mt-1">
                            <div class="form-group mb-0 position-relative">
                                <select class="form-control" name="departure_to" id="departure_to" data-toggle="select2"  style="width:100%">
                                    <option value="{{$to}}">{{$to}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col p-md-0 mt-1">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="from" id="from_date" placeholder="From date" autocomplete="off" value="{{$from_date}}">
                            </div>
                        </div>
                        <div class="col p-md-0 mt-1">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control  datepicker" name="to" id="to_date" placeholder="To date" autocomplete="off" value="{{$to_date}}">
                            </div>
                        </div>
                        <div class="col p-md-0 mt-1">
                            <div class="form-group mb-0">
                                <select name="status" class="form-control">
                                    <option select>Status</option>
                                    <option value="1" @if($status_filter == 1) selected @endif>Open</option>
                                    <option value="2" @if($status_filter == 2) selected @endif>Sold Out</option>
                                </select>
                            </div>
                        </div>
                        <div class="col p-md-0 mt-1">
                            <div class="form-group mb-0">
                                <select class="form-control publiser_name" name="publiser_name[]" id="publiser_name" multiple  style="width:100%">
                                    @foreach($publisher_n as $pub)
                                        <option value="{{$pub}}" selected>{{$pub}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col p-md-0 mt-1" style="display:none;">
                            <div class="form-group mb-0">
                                <div class="input-group">
                                    @if($search_key)
                                        <input type="text" class="form-control" name="keywords" id="keywords" placeholder="Search keywords..." autocomplete="off" value="{{$search_key}}">
                                    @else
                                        <input type="text" class="form-control" name="keywords" id="keywords" placeholder="Search keywords..." autocomplete="off" value="{{$keywords}}">
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col p-md-0 mt-1 d-flex">
                            <button type="submit" class="btn btn-primary  float-right"><i class="fas fa-search mr-1"></i> Search</button> 
                            <a href="{{route('all_departure')}}" class="btn btn-danger  float-right mr-1"><i class="fas fa-reset"></i> Reset</a>
                        </div>
                    </div>
                </form>
                <!--                            <div class="col-md-1 col-sm-1 col-lg-1 p-md-0 mt-2">
                    <a href="{{route('all_departure')}}" class="btn btn-danger w-100"><i class="fas fa-reset"></i> Reset</a>
                </div>-->
            </div>

            @if(Route::currentRouteName() == 'all_departure')
                @include('departure/all_departure_data')
            @elseif(Route::currentRouteName() == 'all_departure-table')
                @include('departure/all_departure_data_table')
            @endif
        </div>

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
        </div>
         <script src="{{asset('js/select2.full.min.js')}}"></script>
        <script type="text/javascript">
             function toggle_filter(){
                //$("#e").slideDown(500); Advance Filter
                if($('#advance_filter').css('display')=='none'){
                    $("#advance_filter").slideDown(800);
                    //$('#keywords_filter').css('display','none');
                    $("#filter_icn").prop('title','Keyword Search');
                }else{
                    //$("#keywords_filter").css('display','inline-block');
                    $('#advance_filter').css('display','none');
                    $("#filter_icn").prop('title','Advance Filter');
                } 
             }
            $(document).ready(function () {
                if(searchadvanceUrl('departure_from')=='not found'){
                     
                    $("#advance_filter").css('display','none');
                    //$('#keywords_filter').css('display','inline-block');
                    $("#filter_icn").prop('title','Advance Filter');
                }else{ 
                    $("#advance_filter").css('display','block');
                    //$('#keywords_filter').css('display','none');
                    $("#filter_icn").prop('title','Keyword Search');

                }
                function searchadvanceUrl(sParam) {
                    var sPageURL = window.location.search.substring(1),
                        sURLVariables = sPageURL.split('&'),
                        sParameterName,
                        i;

                    for (i = 0; i < sURLVariables.length; i++) {
                        sParameterName = sURLVariables[i].split('=');

                        if (sParameterName[0] === sParam) {
                            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                        }
                    }
                 return 'not found';
                }
                if($('#publiser_name').length>0){

                    $('#publiser_name').select2({ 
                        placeholder: 'Publisher name',
                        ajax: {
                            url: "/publishers-list",
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return {
                                    results: $.map(data, function (item) {
                                        return {
                                            text: item.departure_ownner,
                                            id: item.departure_ownner
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });

                }
            });
        </script>
        <script>
            $(document).ready(function(){
             $('#autoSearch').keyup(function(){ 
                    $('#autoSearchData').html('');
                    var query = $(this).val();
                    if(query != '')
                    {
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url:"{{ route('autocomplete.fetch') }}",
                            method:"POST",
                            data:{query:query, _token:_token},
                            success:function(data){
                                console.log(data);
                                $('#autoSearchData').fadeIn();  
                                $('#autoSearchData').html(data);
                            }
                        });
                    }
                });
                // $(document).on('click', 'li', function(){  
                //     $('#country_name').val($(this).text());  
                //     $('#autoSearchData').fadeOut();  
                // });  
            });
        </script>


        @endsection
        @section('footerSection')

            <!--  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
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
                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            <!-- <button type="submit" class="btn btn-primary" id="edit_send_form"><i class="fa fa-save"></i> Update</button>
                            <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 5%; display: none;">
                            <span id="mesegess"></span>
                        </div>
                    </div>
                </div>
        </form>
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

       <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p>Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

    </div> -->
            <!--  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('js/select2.full.min.js')}}"></script> -->



            <!--
            // Departure Search From Destinations -->
            <!--  <script>
        $('#destination').select2({
            placeholder: 'Select Destination',
            ajax: {
                url: "/start_from_destination",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.destination,
                                id: item.destination
                            }
                        })
                    };
                },
                cache: true
            }
        });
        var h = $(".destination").select2({
            tags: true,
        });
    </script>

    // Departure Search From Destinations end
    <script type="text/javascript">
        $('.edit-item').click(function () {
            var id = $(this).data("id");
            $('#editModal').modal('show');
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
    </script> -->
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
                            url: '?page=' + page + '&from=' + '<?php echo $from_date;?>' + '&to=' + '<?php echo $to_date;?>',
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

        @endsection
