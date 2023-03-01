@extends('layouts.app')
@section('tagSection')
<title>Departure Cloud</title>
@endsection
@section('headSection')

@endsection
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css')}}">
@section('content')
@section('headnav') 
<li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page"><span> All Departure</span></li>
@endsection
<div id="content" class="main-content">
  <div class="layout-px-spacing">
  <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      <div class="widget-content widget-content-area br-6">
          <div class="table-responsive mb-4 mt-4">
             <div class="content-wrapper">
    <section class="content-header">
      <h1>Departures List    <span class="btn btn-success btn-sm pull-right">Total Active<span style="color:#ffeb00"> </span></span>
     
      </h1>
      <form class="form-inline" action="{{route('all_departure')}}">
        
        <div class="form-group">
        <input type="date" class="form-control pull-right" name="from" id="from_date" placeholder="From date" autocomplete="off" value="">
        </div>
        <div class="form-group">
        <input type="date" class="form-control pull-right" name="to" id="to_date" placeholder="To date" autocomplete="off" value="">
        </div>
        <div class="form-group" >
        
          <select class="form-control m-4 destination" name="starting_from" id="destination" style="widht:200px">
               
         </select>
        </div>
          <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
      </form>
     
     
    </div>
    </section>
   
    @if(session('success'))
    <div class="modal fade" id="myModal" role="dialog" style="">
         <div class="modal-dialog modal-sm" >
          <div class="modal-content">
            <div class="modal-body text-center">
            <h3>{{session('success')}}</h3>
            <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Ok</button>
             </div>
           </div>
         </div>
       </div>
    @endif
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              
            </div>
            <!-- /.box-header -->
           
          </div>
          
        </div>
      </div>
    </section>

  </div>
  </div>
</div>
</div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}.box-header.with-border{border-bottom:none}a.dropdown-item.edit {padding-left: 10px !important;display: inline-block;padding: 5px;}.btn-group-sm>.btn, .btn-sm {padding: 1px 3px !important;}.inlineFlax{display: inline-flex;}.display {display: inline-flex;}.ui-datepicker-buttonpane.ui-widget-content {display: none !important;}div#ui-datepicker-div {width: 18% !important;}.edit_dest span#select2-destinations-container {padding: 0px 0px 0px 0px;line-height: 1.6;}input.select2-search__field {padding-left: 10px !important;}.validationError {color: #ff0c0c;margin-left: 16px;}.button-submit{margin-top: 20px;margin-bottom: 20px}.autocomplete-items {z-index: 999;position: absolute;background: #fff;width:94%;}.steps.clearfix.text-center{margin-top: 20px;padding-bottom: 20px;}span#select2-pull-container {padding: 0px;margin-top: -8px;}.labelClass{margin-bottom: 12px;}
  </style>
  @endsection
  @section('footerSection')

 <script src="{{asset('js/select2.full.min.js')}}"></script>
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   
// Departure Search From Destinations
<script>
    $('#destination').select2({
      placeholder: 'Search by Destination',
      ajax: {
          url: "/departure_destination_search",
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


  @endsection