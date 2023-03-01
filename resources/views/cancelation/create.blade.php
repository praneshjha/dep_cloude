@extends('layouts.app')

@section('title', 'Cancelation Schedule - Departure Cloud')

@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<style>
    .ui-datepicker{
        z-index: 9999999 !important;;
    }
</style>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Cancelation charge</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                            <li class="breadcrumb-item active">Cancelation charge</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card-box formCard">
                    <h4 class="mt-0 depnameHeading">{{$departure->title}}</h4>
                    @include('layouts/itinerary_menu')
                      <form action="" method="post" id="paymensechdule">
                           @csrf
                           <div class="row wrappers" >
                                <div class="col-md-6 mt-2">  
                                    <h5>Add Cancelation charge</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="floating-label">
                                      <a href="javascript:void(0);" class="add_button btn btn-outline-primary formlabelmargin" title="Add field"><i class="fas fa-plus" disable></i> Add More</a>
                                    </div>
                                </div> 
                                <div class="row col-md-12 mt-3" id="remove_field">
                                    <div class="col-md-3" style="margin-top:25px">
                                      <div class="form-group">
                                         <label>Minimum Cancelation Charge</label>
                                         <div class="form-group">
                                            <div class="input-group date">
                                                <input type="hidden" class="form-control pull-right start_date fromdate calender" id="date2" name="date[]"   autocomplete="off" value="" required> 
                                                <!-- <div class="input-group-prepend" > 
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    </span>
                                                </div> -->
                                                <span class="text-danger"  id="calender_errors"></span>
                                            </div>
                                        </div>
                                      </div>
                                    </div>   
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Percentage(%)</label>
                                            <input type="text" class="form-control percentage"  id="percentage2" name="percentage[]" value="{{$pay->percentage}}" required>
                                            <span class="text-danger" id="percentage_error2"></span>
                                        </div>
                                    </div>
                                    
                                </div>
                            @if(count($payment) > 0)
                            <?php 
                                $tt = [];
                            ?>
                                @foreach($payment as $key=>$row)
                                
                                    <div class="row col-md-12">
                                        <div class="col-md-3" id="remove_date{{$key}}">
                                          <div class="form-group">
                                             <label>After</label>
                                             <div class="form-group">
                                                <div class="input-group date">
                                                    <input type="text" class="form-control pull-right start_date fromdate calender" id="date{{$key}}" name="date[]"   autocomplete="off" value="{{date('d M, Y', strtotime($row->date))}}" required> 
                                                    <div class="input-group-prepend" id="calendar{{$key}}" > 
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                    <span class="text-danger"  id="calender_errors{{$key}}"></span>
                                                </div>
                                            </div>
                                          </div>
                                        </div>   
                                        <div class="col-md-3" id="remove_percentage{{$key}}">
                                            <div class="form-group">
                                                <label for="">Percentage(%)</label>
                                                @if($key == 0)
                                                <?php 
                                                    array_unshift($tt,$row->percentage+$pay->percentage);
                                                    
                                                ?>
                                                <input type="text" class="form-control percentage"  id="percentage{{$key}}" name="percentage[]" value="{{$row->percentage+$pay->percentage}}"  required>
                                                @else
                                                <?php 
                                                    array_unshift($tt,$row->percentage+$tt[0]);
                                                    
                                                ?>
                                                <input type="text" class="form-control percentage"  id="percentage{{$key}}" name="percentage[]" value="{{$tt[0]}}" required>
                                                @endif
                                                <span class="text-danger" id="percentage_error{{$key}}"></span>
                                            </div>
                                        </div>
                                        <div class="" style="margin-top: 1.4rem!important; margin-left: 5px;" id="remove_buttons{{$key}}">
                                        <div class="form-group">
                                             <a  class="btn btn-outline-danger text-danger" id="remove_input{{$key}}"><i class="fas fa-minus"> Remove</i></a>
                                        </div>
                                        </div>
                                   {{-- @if($key == (count($payment)- 1))
                                        <div class="col-md-3">
                                            <div class="floating-label">
                                              <a href="javascript:void(0);" class="add_button btn btn-outline-primary formlabelmargin" title="Add field"><i class="fas fa-plus" disable></i> Add</a>
                                            </div>
                                        </div>
                                    @endif   --}}
                                    </div>
                                @endforeach
                            @endif
                                
                           </div> 
                           <div class="row">  
                            <div class="col-md-12">
                                    <span class="text-success" id="error" style="margin-left: 10px"></span>
                            </div>
                            <div class="col-md-12 mt-2 text-right">
                                <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                                <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save </button>
                                <button class="btn btn-primary active" type="button" id="store_form_next"><i class="fa fa-save"></i> Save & Next</button>
                            </div>
                          </div>  
                      </form> 
                      
                </div>
            </div>   
        </div>
  </div>           
@endsection
@section('footerSection')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  $('#remove_input').click(function(){
        //Check maximum number of input fields
        $('#remove_field').remove();
  });
@foreach($payment as $key=>$row)
  $('#remove_input{{$key}}').click(function(){
        //Check maximum number of input fields
        $('#remove_date{{$key}}').remove();
        $('#remove_percentage{{$key}}').remove();
        $('#remove_buttons{{$key}}').remove();
  });
  $('#date{{$key}}').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'dd-M-yy',
    maxDate: new Date('{{$departure->start_date}}'),
    minDate: 0,
  });
   $('.fa-calendar').click(function() {
    $("#date{{$key}}").focus();
  });
@endforeach
$('#percentage2').keyup(function(){
      if($(this).val() > 100){
        $(this).val('');
      }else{
        $('#percentage').text('');
      }
   });

var percentage = document.querySelector('#percentage');
percentage.addEventListener('input', restrictNumber);
function restrictNumber (e) {  
  var percent = this.value.replace(new RegExp(/[^\d]/,'ig'), "");
  this.value = percent;
}
</script>
<script type="text/javascript">
   jQuery(document).ready(function () {
       jQuery('#store_form').click(function (e) {
           e.preventDefault();
           //alert('hello');percentage
            <?php $count=0; ?>
            // @if(count($payment) >0)
            // @foreach($payment as $key=>$row)
            //     var After{{$key}} = $('#date{{$key}}').val();
            //        if (After{{$key}} == "") {
            //            $("span#calender_errors{{$key}}").html('This field is required!');
            //            $("input#date{{$key}}").focus();
            //            return false;
            //        }else{
            //            $("span#date{{$key}}").hide();
            //        }
            //     var percentage{{$key}} = $('#percentage{{$key}}').val();
            //        if (percentage{{$key}} == "") {
            //            $("span#percentage_error{{$key}}").html('This field is required!');
            //            $("select#percentage{{$key}}").focus();
            //            return false;
            //        }else{
            //            $("span#percentage_error{{$key}}").hide();
            //        }
            // @endforeach
            // @else
            //     var After = $('#date_else').val();
            //        if (After == "") {
            //            $("span#calender_errors_else").html('This field is required!');
            //            $("input#date_else").focus();
            //            return false;
            //        }else{
            //            $("span#calender_errors_else").hide();
            //        }
            //     var percentage = $('#percentage_else').val();
            //        if (percentage == "") {
            //            $("span#percentage_error_else").html('This field is required!');
            //            $("select#percentage_else").focus();
            //            return false;
            //        }else{
            //            $("span#percentage_error_else").hide();
            //        }
            // @endif
            // @for( $count = 1; $count < 11; $count++)
            // var calender_{{$count}} = $('.calender_{{$count}}').val();
            //     if (calender_{{$count}} == "") {
            //        $("span#calender_error_{{$count}}").html('This field is required!');
            //        $("select#calender_{{$count}}").focus();
            //        return false;
            //     }else{
            //        $("span#calender_error_{{$count}}").hide();
            //     }
            // var percentage_{{$count}} = $('#percent_{{$count}}').val();
            //     if (percentage_{{$count}} == "") {
            //        $("span#percent_error_{{$count}}").html('This field is required!');
            //        $("select#percent_{{$count}}").focus();
            //        return false;
            //     }else{
            //        $("span#percent_error_{{$count}}").hide();
            //     }
            // @endfor
           jQuery('#gif').show();
         
           jQuery('#gif').css('visibility', 'visible');
           var formDatas = new FormData(document.getElementById('paymensechdule'));
           jQuery.ajax({
               headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               method: 'POST',
               url: "{{route('cancelation_store',request()->route('id'))}}",
               data: formDatas,
               contentType: false,
               processData: false,

               success: function (data) {
                  if(data.url)
                    {    
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        jQuery('#store_form').prop('disabled', true);
                        jQuery('#store_form').html('Please wait...')
                        window.location = data.url;
                    }
                  else{
                    //$('#error').html("<span class='sussecmsg text-danger'>Please make sure total percentage entry 100% !</span>");
                  }
                    $('#gif').hide();
                },
               errors: function () {
                 jQuery('#gif').hide();
                 jQuery('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
               }

           });
       });

        jQuery('#store_form_next').click(function (e) {
           e.preventDefault();
           //alert('hello');percentage
            <?php $count=0; ?>
            // @if(count($payment) >0)
            // @foreach($payment as $key=>$row)
            //     var After{{$key}} = $('#date{{$key}}').val();
            //        if (After{{$key}} == "") {
            //            $("span#calender_errors{{$key}}").html('This field is required!');
            //            $("input#date{{$key}}").focus();
            //            return false;
            //        }else{
            //            $("span#date{{$key}}").hide();
            //        }
            //     var percentage{{$key}} = $('#percentage{{$key}}').val();
            //        if (percentage{{$key}} == "") {
            //            $("span#percentage_error{{$key}}").html('This field is required!');
            //            $("select#percentage{{$key}}").focus();
            //            return false;
            //        }else{
            //            $("span#percentage_error{{$key}}").hide();
            //        }
            // @endforeach
            // @else
            //     var After = $('#date_else').val();
            //        if (After == "") {
            //            $("span#calender_errors_else").html('This field is required!');
            //            $("input#date_else").focus();
            //            return false;
            //        }else{
            //            $("span#calender_errors_else").hide();
            //        }
            //     var percentage = $('#percentage_else').val();
            //        if (percentage == "") {
            //            $("span#percentage_error_else").html('This field is required!');
            //            $("select#percentage_else").focus();
            //            return false;
            //        }else{
            //            $("span#percentage_error_else").hide();
            //        }
            // @endif
            // @for( $count = 1; $count < 11; $count++)
            // var calender_{{$count}} = $('.calender_{{$count}}').val();
            //     if (calender_{{$count}} == "") {
            //        $("span#calender_error_{{$count}}").html('This field is required!');
            //        $("select#calender_{{$count}}").focus();
            //        return false;
            //     }else{
            //        $("span#calender_error_{{$count}}").hide();
            //     }
            // var percentage_{{$count}} = $('#percent_{{$count}}').val();
            //     if (percentage_{{$count}} == "") {
            //        $("span#percent_error_{{$count}}").html('This field is required!');
            //        $("select#percent_{{$count}}").focus();
            //        return false;
            //     }else{
            //        $("span#percent_error_{{$count}}").hide();
            //     }
            // @endfor
           jQuery('#gif').show();
         
           jQuery('#gif').css('visibility', 'visible');
           var formDatas = new FormData(document.getElementById('paymensechdule'));
           jQuery.ajax({
               headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               method: 'POST',
               url: "{{route('cancelation_store',request()->route('id'))}}",
               data: formDatas,
               contentType: false,
               processData: false,

               success: function (data) {
                  if(data.url)
                    {    
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        jQuery('#store_form_next').prop('disabled', true);
                        jQuery('#store_form_next').html('Please wait...')
                        window.location = data.url;
                    }
                  else{
                    //$('#error').html("<span class='sussecmsg text-danger'>Please make sure total percentage entry 100% !</span>");
                  }
                    $('#gif').hide();
                },
               errors: function () {
                 jQuery('#gif').hide();
                 jQuery('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
               }

           });
       });
   });
   $('.start_date').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'dd-M-yy',
    maxDate: new Date('{{$departure->start_date}}'),
    minDate: 0,
  });
   $('.fa-calendar').click(function() {
    $(".start_date").focus();
  });
   
</script>
 <script type="text/javascript">
  
  $(document).ready(function(){
        var k=0;
        var maxFields = 20; //Input fields increment limitation
        var addButtons = $('.add_button'); //Add button selector
        var wrappers = $('.wrappers'); //Input field wrapper
        //var fieldHTMLs = '';  
        var x = 1;
        var total=0;
        $('#percentage').keyup(function(){ 
            add_percentage();
        });
        $(addButtons).click(function(){
            
            var calender='"calender_'+x+'"';
            var calender_error='"calender_error_'+x+'"';

            var percentage_id='percent_'+x;
            var percentage__error='percent_error_'+x;
            var fieldHTMLs = '<div class="col-md-12" id=#rowes"><div class="row"><div class="col-md-3" style=""><label>After</label><div class="form-group"><div class="input-group date"><input type="text" class="form-control pull-right start_date1 fromdate '+calender+'" name="date[]"   autocomplete="off" required> <div class="input-group-prepend" > <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span></div><span class="text-danger"  id="'+calender_error+'"></span></div></div></div><div class="col-md-3"><label>Percentage(%)</label><div class="form-group"><input type="text" name="percentage[]"  class="form-control percentage2" id="'+percentage_id+'"><span class="text-danger" id="'+percentage__error+'"></span></div></div><div class="col-md-3" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button btn btn-outline-danger"><i class="fas fa-minus"></i> Remove</a></div></div></div></div>';
            if(x < maxFields){ 

                x++;
                $(wrappers).append(fieldHTMLs);
             
                $('.start_date1').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'dd-M-yy',
                maxDate: new Date('{{$departure->start_date}}'),
                minDate: 0,
              });
                var percentage2 = document.querySelector('.percentage2');
                percentage2.addEventListener('input', restrictNumber);
                function restrictNumber (e) {  
                  var percent2 = this.value.replace(new RegExp(/[^\d]/,'ig'), "");
                  this.value = percent2;
                }
                  $('.fa-calendar').click(function() {
                    $(".start_date1").focus();
                  });
                   $('.percentage2').keyup(function(){
                    add_percentage();
                    var total_check = $('#total').val();
                          if(total_check > 10){
                            $(this).val(0);
                          }else{
                            $('.percentage2').text('');
                          }
                    });
                $('.percentage2').keyup(function(){
                  if($(this).val() > 100){
                    $(this).val('');
                  }else{
                    $('#percentage').text('');
                  }
               });
                
            }
        });
        function add_percentage(){

                     var p_total=$('#percentage').val();

                     if(p_total){
                        p_total=parseInt(p_total);
                     }else{
                        p_total=0;
                     } 
                 for(let i=1;i<x;i++){
                     v_perc=$('#percent_'+i).val();
                     if(v_perc){
                        v_perc=parseInt(v_perc);
                     }else{
                        v_perc=0;
                     }
                     p_total=p_total + v_perc;
                     console.log(p_total,'jhgj');
                 }
                
               var dis = $('#total').val(p_total); 
               if(dis == 100){
                $('.formlabelmargin').prop('disabled', true);
               }
        }
         
        
        $(wrappers).on('click', '.remove_button', function(e){
            e.preventDefault();
             $(".col-md-12").last().remove();
            
            x--;
        });
  });
</script>

@endsection