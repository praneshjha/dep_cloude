@extends('layouts.app')
@section('tagSection')
<title>Departure Cloud | Series</title>
@endsection
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
@section('content')
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Create Departure Series</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Series</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <form action="" method="post" id="mySeriesForm">
                            @csrf
                            <div class="row wrappers">
                                <div class="col-md-12 mt-2">
                                    <div class="col-md-12" id="rowes">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Departure Name</label>
                                                <div class="form-group">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control pull-right dep_name" value="{{$departure->title}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Departure Date</label>
                                                <div class="form-group">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control pull-right start_date" value="{{date('d M Y', strtotime($departure->start_date))}}" disabled> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>End Date</label>
                                                <div class="form-group">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control pull-right end_date" value="{{date('d M Y', strtotime($departure->end_date))}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Total Units</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control total_unit" value="{{$departure->total_seat}}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="departure_id" value="{{request()->route('id')}}">
                                <div class="col-md-2 mt-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">No. of Departures<span class="validationError" id="totalClones"></span></label>
                                        <select name="no_of_series" class="form-control" id="noOfClones">
                                            <?php $a = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,67,68,69,70,71,72,73,74,75,76,77,78,79,80]; ?>
                                            @foreach($a as $ab)
                                                <option value="{{$ab}}">{{$ab}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check mb-0 ml-2 form-check-success">
                                        <input class="form-check-input" value="random" type="radio" name="random" id="random" onclick="cloneType('random')" checked>
                                        <label class="form-check-label" for="random">Random</label>
                                    </div>
                                    <div class="form-check mb-0 ml-2 form-check-success">
                                        <input class="form-check-input" value="daily" type="radio" name="random" id="daily" onclick="cloneType('daily')">
                                        <label class="form-check-label" for="daily">Daily</label>
                                    </div>
                                    <div class="form-check mb-0 ml-2 form-check-success">
                                        <input class="form-check-input" value="weekly" type="radio" name="random" id="weekly" onclick="cloneType('weekly')">
                                        <label class="form-check-label" for="weekly">Weekly</label>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="row" id="departureSeries">

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="text-success" id="error" style="margin-left: 10px"></span>
                                </div>
                                <div class="col-md-12 text-right mt-2">
                                    <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 4%; visibility: hidden;">
                                    <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                                    <button class="btn btn-primary active" type="button" id="seriesDepartureStore"><i class="fa fa-save"></i> Create Series</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        .form-check.mb-0.ml-2.form-check-success {
            display: inline-block;
        }
        .ui-datepicker-buttonpane.ui-widget-content {
            display: none;
        }
        .ui-datepicker {
            z-index: 9999999 !important;;
        }

    </style>
@endsection

@section('footerSection')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

    //Loop Div append

    var clone_number=1;
    $(document).ready(function() {
        $( window ).on("load", function() {
            var onLoadValue=$("#noOfClones").val();
            var start_date=$(".start_date").val();
            var end_date=$(".end_date").val();
            var total_unit=$(".total_unit").val();

            var count=0;
            for(var i = 1; i <= onLoadValue; i++){

                $('#departureSeries').append('<div class="col-md-12 mt-4"><div class="col-md-12" id="rowes"><div class="row"><div class="col-md-4"><label>Departure Name</label><div class="form-group"><input type="text" class="form-control dep_name_'+i+'" name="title[]"></div></div><div class="col-md-3"><label>Departure Date</label><div class="form-group"><div class="input-group date"><input type="text" class="form-control pull-right start_date1 fromdate_'+i+'" id="fromdate_'+i+'" name="start_date[]" autocomplete="off"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span></div><span class="text-danger" id=""></span></div></div></div><div class="col-md-3"><label>End Date</label><div class="form-group"><div class="input-group date"><input type="text" class="form-control pull-right end_date_'+i+'" id="end_date_'+i+'" name="end_date[]" autocomplete="off"><div class="input-group-prepend" ><span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar" aria-hidden="true"></i></span></div><span class="text-danger"  id=""></span></div></div></div><div class="col-md-2"><label>Total Units</label><div class="form-group"><input type="text" name="total_unit[]" class="form-control totalUnit_'+i+'" id="totalUnit_'+i+'"><span class="text-danger" id=""></span></div></div></div></div></div>');
                $('.fromdate_'+i).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'dd M yy',
                });
                // End date
                $('.end_date_'+i).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'dd M yy',
                });
             count=i;
            }
        });

        $("#noOfClones").on('change', function() {
            var thisOptionValue=$(this).val();
            clone_number=thisOptionValue;
            $('#departureSeries').html('');
            var count=0;
            for(var i = 1; i <= thisOptionValue; i++){
                $('#departureSeries').append('<div class="col-md-12 mt-4"><div class="col-md-12" id="rowes"><div class="row"><div class="col-md-4"><label>Departure Name</label><div class="form-group"><input type="text" class="form-control dep_name_'+i+'" name="title[]"></div></div><div class="col-md-3"><label>Departure Date</label><div class="form-group"><div class="input-group date"><input type="text" class="form-control pull-right start_date1 fromdate_'+i+'" id="fromdate_'+i+'" name="start_date[]" autocomplete="off"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span></div><span class="text-danger" id=""></span></div></div></div><div class="col-md-3"><label>End Date</label><div class="form-group"><div class="input-group date"><input type="text" class="form-control pull-right end_date_'+i+'" id="end_date_'+i+'" name="end_date[]" autocomplete="off"><div class="input-group-prepend" ><span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar" aria-hidden="true"></i></span></div><span class="text-danger"  id=""></span></div></div></div><div class="col-md-2"><label>Total Units</label><div class="form-group"><input type="text" name="total_unit[]" class="form-control totalUnit_'+i+'" id="totalUnit_'+i+'"><span class="text-danger" id=""></span></div></div></div></div></div>');
                $('.fromdate_'+i).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'dd M yy',
                });
                // End date
                $('.end_date_'+i).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'dd M yy',
                });
               count=i;
            }
            cloneType(clone_type);
        });
    });
    var clone_type='random';
    function cloneType(type){
        clone_type=type;
        var start_date = $('.start_date').val();
        var end_date = $('.end_date').val();

        if(type=='daily'){
             for(let i=1;i<=clone_number;i++){
                 var first_st_date=addDays(new Date(start_date),1);
                 var first_end_date=addDays(new Date(end_date),1);

                 if(i==1){
                    setTimeout(()=>{
                        $('.fromdate_'+i).datepicker('setDate', first_st_date);
                        $('.end_date_'+i).datepicker('setDate', first_end_date);

                      }, 100);
                    getUnitRange(i);
                    getDepName(i)
                 }else{
                  setTimeout(()=>{
                        var id_d=parseInt(i)-1;
                        getDateRange(1,id_d,i);
                      }, 200);
                 }
             }
        }

        if(type=='weekly'){
             for(let i=1;i<=clone_number;i++){
                 var first_st_date=addDays(new Date(start_date),7);
                 var first_end_date=addDays(new Date(end_date),7);
                 if(i==1){
                     // $('.fromdate_'+i).val(first_st_date);
                     // $('.end_date_'+i).val(first_end_date);
                    $('.fromdate_'+i).datepicker('setDate', first_st_date);
                    $('.end_date_'+i).datepicker('setDate', first_end_date);
                    getUnitRange(i);
                    getDepName(i)
                 }else{
                  setTimeout(()=>{
                        var id_d=parseInt(i)-1;
                        getDateRange(7,id_d,i);
                    }, 200);
                 }
             }
        }
        if(type=='random'){
            for(let i=1;i<=clone_number;i++){

                 $('.fromdate_'+i).val('');
                 $('.end_date_'+i).val('');
            }
        }
    }

    function addDays(date, days) {
          var result = new Date(date);
          result.setDate(result.getDate() + days);
          return result;
    }
    function getDateRange(days,id_d,i){
        var start_date = $('.fromdate_'+id_d).val();
        var end_date = $('.end_date_'+id_d).val();
        var first_st_date=addDays(new Date(start_date),days);
        var first_end_date=addDays(new Date(end_date),days);
        $('.fromdate_'+i).datepicker('setDate', first_st_date);
        $('.end_date_'+i).datepicker('setDate', first_end_date);
        getUnitRange(i);
        getDepName(i)
    }

    function getUnitRange(i){
        var unit = $('.total_unit').val();
        $('.totalUnit_'+i).val(unit);
    }
    function getDepName(i){
        var dep_name = $('.dep_name').val();
        $('.dep_name_'+i).val(dep_name);
    }
</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#seriesDepartureStore').click(function (e) {
            e.preventDefault();
            jQuery('#gif').show();
            jQuery('#gif').css('visibility', 'visible');
            jQuery('#seriesDepartureStore').html('Please wait...');
            jQuery('#seriesDepartureStore').prop('disabled', true);
            var formDatas = new FormData(document.getElementById('mySeriesForm'));
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{route('series_departure')}}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#gif').hide();
                    console.log(data);
                    $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                    window.location = "{{route('departure')}}";
                    //window.location.reload();
                },
                statusCode: {
                    500: function (status) {
                        console.log(status);
                        jQuery('#gif').hide();
                        jQuery('#mesegese}}').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                    },

                    400: function () {
                        jQuery('#gif').hide();
                        jQuery('#mesegese}}').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                    },
                    419: function () {
                        jQuery('#gif').hide();
                        jQuery('#mesegese}}').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                    },
                    401: function () {
                        jQuery('#gif').hide();
                        jQuery('#mesegese}}').html("<span class='sussecmsg text-danger'>Something went wrong!</span>");
                    }
                }

            });
        });
    });
</script>
@endsection
