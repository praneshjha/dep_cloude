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
                                            <input type="text" class="form-control" name="autoSearch" id="autoSearch" placeholder="Search..." autocomplete="off" value="{{$search_key}}">
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

                             

                            <li class="list-inline-item box_head_dep"><a href=" javascript:void(0)"  onclick="changeView('box')" class="active" ><i class="fas fa-th"></i></a></li>

                            <li class="list-inline-item table_head_dep"><a href="javascript:void(0)" onclick="changeView('table')"><i class="fas fa-list"></i></a>
                            </li>
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
                                        <input type="text" class="form-control" name="keywords" id="keywords" placeholder="Search keywords..." autocomplete="off" value="{{$search_key}}">
                                   
                                    
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
 
         
            <div class="DepartureBoxView" id="DepartureBoxView">
                @include('ajax_paginate/all_departure_data_paginate')
            </div>
            <div class="DepartureTableView" id="DepartureTableView" style="display:none" >
                @include('ajax_paginate/all_departure_data_table')
            </div>
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

<div class="modal fade" id="booking_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-mb " role="document">
     <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-white" id="mySmallModalLabel">Book Units</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
<div id="booking_modal_form"></div>
        </div>
        </div>                
</div>

<div class="modal fade" id="hold_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
    <div class="modal-content hold">
        <div class="modal-header">
            <h5 class="modal-title text-white1" id="mySmallModalLabel">Hold Units</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-x">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
<div id="hold_modal_form"></div>
        </div>
        </div>                   
</div>

    </div>
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script type="text/javascript">

  

     function toggle_filter(){
        //$("#e").slideDown(500); Advance Filter
        if($('#advance_filter').css('display')=='none'){
            $("#advance_filter").slideDown(1500);
            //$('#keywords_filter').css('display','none');
            $("#filter_icn").prop('title','Keyword Search');
        }else{
           // $("#keywords_filter").css('display','inline-block');
            $('#advance_filter').css('display','none');
            $("#filter_icn").prop('title','Advance Filter');
        } 
     }
     function fetch_data(pg_no){
        $(".paginate_loader").css('display','flex');
        const queryString = window.location.search;
         console.log(queryString);
        
         var total_page='<?php echo $total_page;?>';
         if(pg_no >total_page){ 
            $(".paginate_loader").css('display','none'); 
            return false;
         } 

         if(queryString){
            var query_all=queryString+'&page='+pg_no+'&view_type='+view_type;
         }else{
            var query_all="?page=" + pg_no+'&view_type='+view_type;
         }
         $.ajax({
                    type: "GET",
                    url: "{{url('/all_dep_ajx')}}" + query_all,
                    success: function (res) {
                       $(".paginate_loader").css('display','none'); 
                        if (res) {                            
                            if(view_type=='box'){
                                 $("#all_dep_appends").append(res);
                            }else{
                                 $("#all_dep_appends_table").append(res);
                            } 
                        } else {
                            //$("#all_dep_appends").empty();
                        }
                    }
                });
     }
     function openBookingModal(departure) {
        console.log(departure,'book');

           $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{url('/get_booking_modal')}}",
                    data:departure,
                    success: function (res) { 
                        $("#booking_modal_form").html(res);
                    }
                });
     }
     function openHoldModal(departure) {
        console.log(departure,'hold');

           $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{url('/get_hold_modal')}}",
                    data:departure,
                    success: function (res) { 
                        $("#hold_modal_form").html(res);
                    }
                });
     }

 function changeView(type){
          $(".paginate_remove").remove();
        if(type=='box'){
            view_type='box';
            page_no=1;

          $("#DepartureBoxView").css('display','block');
          $("#DepartureTableView").css('display','none');
          $(".box_head_dep a").addClass('active');
          $(".table_head_dep a").removeClass('active');
        }
        if(type=='table'){
            view_type='table';
            page_no=1;
          $("#DepartureBoxView").css('display','none');
          $("#DepartureTableView").css('display','block');
          $(".box_head_dep a").removeClass('active');
          $(".table_head_dep a").addClass('active');


        }

     }
     var view_type='box';
        var page_no=1;
    $(document).ready(function () {
        $("#DepartureTableView").css('display','none');
       //checking scroll to bottom pagination
        checkWindowSize();
 
        function checkWindowSize(){ 
           if($(window).height() >= $(document).height()){
              
              //console.log('scroll down');
           }
        }
        $(window).scroll(function(){

           var position = $(window).scrollTop();
           var bottom = $(document).height() - $(window).height();
           //console.log(position+'-sccc-'+bottom)
           //if( position == bottom ){
            if( bottom - position < 30 ){
            //console.log(position+'-equal-'+bottom)
            page_no++;
              fetch_data(page_no);
           }

        });

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
                            url:"{{ route('autocomplete.fetch2') }}",
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
            });
        </script>
    
@endsection
@section('footerSection')
    <script>
        //ajax pagination
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
