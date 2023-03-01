<?php
  //$today = date("Y-m-d");
  // $new_time = ($departure_details->hold_duration) + 5;
  $user = auth()->user()->id;
  $login_time = auth()->user()->last_login_at;
  //dd($login_time);
  $fav = DB::table('favourite_supplier')
          ->join('users','favourite_supplier.tenant_id','=','users.tenant_id')
          ->select('users.company_name','users.tenant_id')
          ->where('user_id',$user)
          ->distinct()
          ->get();
  $fav_pkg= DB::table('favourite_package')
          ->join('departures','favourite_package.dep_id','=','departures.id')
          ->where('favourite_package.user_id',$user)
          ->select('departures.id','departures.title','departures.start_date','departures.total_seat','departures.available_seat')
          ->get();
  $date = date("Y-m-d");
  $counted = $fav->count();
  $total_pkg = $fav_pkg->count();
  $i = 0;
?>
@extends('layouts.app')
@section('tagSection')
<title>Dashboard | Departure Cloud</title>
@endsection
@section('content')
 <!-- notification destination popup -->
 <div id="popup">
        Search departures by destinations, tags, and supplier’s  name. Next, select the  departure type and choose the travel date from the drop-down menu.
 </div>

    <div class="wrapper">
  <div class="wrapperOverlay"></div>
  @if(empty($_GET))
  <div class="searchCenter">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8 m-auto">
          <div class="card p-3">
            <form class="searchPage">
              <div class="input-group ">
                <input type="search" name="keyword" class="form-control" id="searchDeparture" placeholder="Search Departures, Destinations and Suppliers... " autocomplete="off" required>
                @if(isset($_GET['type']))
                <input type="hidden" name="type" value="{{$_GET['type']}}">
                @else
                <input type="hidden" name="type" value="{{11}}">
                @endif
                <div class="searchButtonMain"><i class="fas fa-search"></i><span id="autoSearchData"></span></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3 class="text-white"><i class="mdi mdi-heart-outline mr-1"></i>Your Packages</h3>
        </div>
      @foreach($fav_pkg as $pkg_show)
      <?php
        if ($i < 5)
        {  
      ?>
      <div class="col-md-2 gridFav">
        <div class="card p-0 m-0">
          <!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
          <div class="card-body p-1 pl-2">
            <h5 class="card-title">
              <a href="{{route('all_departure_details',$pkg_show->id)}}">{{ $pkg_show->title }}</a>
            </h5>
            <div>Date: {{date('d M, Y', strtotime($pkg_show->start_date))}}</div>
            <div>Total Seat: {{ $pkg_show->total_seat }}</div>
            <div>Avl Units: {{ $pkg_show->available_seat}}</div>
           </div>
           
        </div>       
      </div>
      <?php $i++; } ?>
    @endforeach
    <?php $value_fav = $total_pkg - $i;
      if( $value_fav > 0 )
      { ?>
      <div class="col-md-2">
        <div class="card p-0 m-0">
          <!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
          <div class="card-body p-1 pl-2">
            <h5 class="card-title">
              <a href='#'><i class="mdi mdi-plus-box"></i> Show More</a>
            </h5>
          </div>
        </div>       
      </div>
    <?php  } ?>         
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3 class="text-white"><i class="mdi mdi-heart-outline mr-1"></i>Your favorites</h3>
        </div>
      @foreach($fav as $supplier)
      <?php
        if ($i < 5)
        {  
          $total_departure = DB::table('departures')
            ->where('tenant_id', '=', $supplier->tenant_id)
            ->where('status', '=', 1)
            ->whereDate('start_date', '>=', $date)
            ->count();
          $details = DB::table('departures')
            ->where('tenant_id','=',$supplier->tenant_id)
            ->where('status','=',1)
            ->whereDate('start_date','>=',$date)
            ->select('from')
            ->distinct()->get();
          $new_dep = DB::table('departures')
            ->where('tenant_id', '=', $supplier->tenant_id)
            ->where('status', '=', 1)
            ->whereDate('start_date', '>=', $date)
            ->where('created_at','>=',$login_time)
            ->count();
      ?>
      <div class="col-md-2 gridFav">
        <div class="card p-0 m-0">
          <!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
          <div class="card-body p-1 pl-2">
            <?php if($new_dep > 0) { ?> 
              <div class="ribbon"><span>New</span></div> 
            <?php } ?> 
            <h5 class="card-title">
              <a href='{{ url("dashboard") }}?type=13&keyword={{ $supplier->company_name}}'>{{ $supplier->company_name }}</a>
            </h5>
            <!-- <hr>
            <p class="card-text">Currently active departure are <br> <b>{{ $total_departure }}</b></p> -->
            <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
          </div>
           <div class="HoverFav">
                <div>
                    <p><span>Total Package :</span> <strong>{{ $total_departure }}</strong></p>
                    <p><span>Destination :</span> <strong>@foreach($details as $det) <a href='{{ url("dashboard") }}?type=13&keyword={{ $supplier->company_name}}&destination={{ $det->from }}'>{{ $det->from }}</a> @endforeach</strong></p>
                </div>
            </div>
        </div>       
      </div>
      <?php $i++; } ?>
    @endforeach
    <?php $value = $counted - $i;
      if( $value > 0 )
      { ?>
      <div class="col-md-2">
        <div class="card p-0 m-0">
          <!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
          <div class="card-body p-1 pl-2">
             
            <h5 class="card-title">
              <a href='#'><i class="mdi mdi-plus-box"></i> Show More</a>
            </h5>
            <!-- <hr>
            <p class="card-text">Currently active departure are <br> <b>{{ $total_departure }}</b></p> -->
            <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
          </div>
           
        </div>       
      </div>
    <?php  } ?>         
    </div>
  </div>
  @else
  <div class="searchResult">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
            <h4 class="page-title">Search</h4>
            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                <li class="breadcrumb-item active">Search</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-9">
          <div class="row">
            <div class="col-md-12 m-auto">
              <div class="card p-3">
                <form class="searchPage" method="get">
                  <div class="input-group mb-2">
                    <?php
                    // if(isset($_GET['packageType']))
                    // {
                    //     $pacType = $_GET['packageType'];
                    // }
                    // else { $pacType = "" ; }
                    // if(isset($_GET['dates']))
                    // {
                    //     $dates = $_GET['dates'];
                    // } else { $dates = "" ; }
                    ?>
                    <input type="search" name="keyword" class="form-control" id="searchDeparture" placeholder="Search Departures, Destinations and Suppliers... " autocomplete="off" @if(empty($_GET['keyword'])) @else($_GET['keyword'])
                    value="{{$_GET['keyword']}}"@endif required>
                    <input type="hidden" name="type" @if(empty($_GET['type'])) @else($_GET['type']) value="{{$_GET['type']}}"@endif id="type">
                    <input type="hidden" name="destination" @if(empty($_GET['destination'])) @else($_GET['destination']) value="{{$_GET['destination']}}"@endif id="destination">
                    
                    <div id="autoSearchData">
                    </div>
                    <div class="searchButtonMain"><i class="fas fa-search"></i></div>
                    <div class="input-group-append bg-primary">
                      <select class="input-group-text text-left changeStatus" name="dates" id="day">
                        <option value="0" <?php //echo ($dates == "" ? 'selected="selected"':''); ?>>All Dates</option>
                        <option value="7" <?php //echo ($dates == "7" ? 'selected="selected"':''); ?>>7 Days</option>
                        <option value="15" <?php //echo ($dates == "15" ? 'selected="selected"':''); ?>>15 Days</option>
                        <option value="30" <?php //echo ($dates == "30" ? 'selected="selected"':''); ?>>Within 30 Days</option>
                        <option value="31" <?php //echo ($dates == "30" ? 'selected="selected"':''); ?>>30 Days Beyond</option>
                      </select>
                    </div>
                  </div>
                  <div id="packageType_filter">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="typeFilter0" name="packageType" class="custom-control-input" value="0" <?php //echo ($pacType == "1" ? 'checked="checked"': ''); ?> checked="checked">
                      <label class="custom-control-label" for="typeFilter0">All Package</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="typeFilter1" name="packageType" class="custom-control-input" value="1" <?php //echo ($pacType == "1" ? 'checked="checked"': ''); ?> >
                      <label class="custom-control-label" for="typeFilter1">Land + Flight</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="typeFilter2" name="packageType" class="custom-control-input" value="2" <?php //echo ($pacType == "2" ? 'checked="checked"': ''); ?> >
                      <label class="custom-control-label" for="typeFilter2">Land Only</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="typeFilter3" name="packageType" class="custom-control-input" value="3" <?php //echo ($pacType == "3" ? 'checked="checked"': ''); ?> >
                      <label class="custom-control-label" for="typeFilter3">Hotel + Flight</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="typeFilter4" name="packageType" class="custom-control-input" value="4" <?php //echo ($pacType == "4" ? 'checked="checked"': ''); ?> >
                      <label class="custom-control-label" for="typeFilter4">Hotel Only</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="typeFilter5" name="packageType" class="custom-control-input" value="5" <?php //echo ($pacType == "5" ? 'checked="checked"': ''); ?> >
                      <label class="custom-control-label" for="typeFilter5">Flight Only</label>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="bg-white sticky-top">
            <div class="col-md-12 m-auto">
              <div class="container text-start">
                <div class="row align-items-start navbar">
                  <div class="col text-wrap border-dark">Supplier
                    <span class="float-right text-sm" id="sot_sup" style="cursor:pointer;">
                    <i class="fa fa-arrow-up text-sm" id="sa" onClick="sortSupAsc()"></i>
                    <i class="fa fa-arrow-down text-sm " id="sd" onClick='sortSupDesc()'></i>
                  </span>
                  </div>
                  <div class="col-md-2">Date
                    <span class="float-right text-sm" id="sot_date" style="cursor:pointer;">
                    <i class="fa fa-arrow-up " id="da" onClick="sortDateAsc()"></i>
                    <i class="fa fa-arrow-down " id="dd" onClick="sortDateDesc()"></i>
                  </span>
                  </div>
                  <div class="col">Available Units
                    <span class="float-right text-sm" id="sot_units" style="cursor:pointer;">
                    <i class="fa fa-arrow-up " id="ua" onClick="sortUnitsAsc()"></i>
                    <i class="fa fa-arrow-down" id="ud" onClick="sortUnitsDesc()"></i>
                  </span>
                  </div>
                  <!--<div class="col-md-2">Nights
                    <span class="float-right text-sm" id="sot_night" style="cursor:pointer;">
                      <i class="fa fa-arrow-up " id="na" onClick="sortNightAsc()"></i>
                      <i class="fa fa-arrow-down" id="nd" onClick="sortNightDesc()"></i>
                    </span>
                  </div>-->
                  <div class="col-md-2">Price
                    <span class="float-right text-sm" id="sot_price" style="cursor:pointer;">
                    <i class="fa fa-arrow-up" id="pa" onClick="sortPriceAsc()"></i>
                    <i class="fa fa-arrow-down" id="pd" onClick="sortPriceDesc()"></i>
                  </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          {{--@if(session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif--}}
          <br>
          <div class="row gridviewRow" id="filtered">
            @include('dashboard/card')
          </div>
          {{-- $departures->links() --}}
        </div>
        <div class="col-md-3">
          <aside class="sideFilter sticky-top">
            <h5 class="text-left">Filter</h5>
            <hr>
            <h6 class="text-right"><a href="">Clear</a></h6>
            
              <form method="GET" type="GET" name="filter" id="filter">
              <!-- @csrf -->
                <div class="custom-control custom-radio">
                @if(($_GET['type'] == 13))
                  <input type="radio" id="BestSold" name="filter" value='3' class="custom-control-input">
                  <label class="custom-control-label" for="Bestsold">Best Sold Package</label>
                @else
                  <input type="radio" id="TopSeller" name="filter" value='1' class="custom-control-input">
                  <label class="custom-control-label" for="TopSeller">Best Seller</label>
                @endif
                </div>
                <div class="custom-control custom-radio">
                  <input type="radio" id="NewestSeller" name="filter" value='2' class="custom-control-input">
                  <label class="custom-control-label" for="NewestSeller">Newest Seller</label>
                </div>
              <!-- <div class="custom-control custom-radio">
                <input type="checkbox" id="Trending" name="filter" value='3' class="custom-control-input">
                <label class="custom-control-label" for="Trending">Trending</label>
              </div> -->
            </form>
          </div>
        <hr>
            {{-- <h5 class="text-left">Tags</h5>
            <div class="custom-control custom-radio">
              <?php foreach ($departures as $departure) {
                $tag = explode("@@", $departure->tags);
                $taged = array_unique($tag);
                foreach ($taged as $tags) {
                  //dd($taged);
                  ?>
                  <a href="#" class="badge rounded-pill bg-primary">{{ $tags }}</a>
                <?php }
              } ?>
              <!-- <input type="radio" id="Tags" name="tags" class="custom-control-input"> -->
              <!-- <label class="custom-control-label" for="Tags">Tags</label> -->
            </div>
            <hr>
            --}}
            <!-- <h5 class="text-left">Destination</h5>
            <div class="custom-control custom-radio">
                <input type="radio" id="Destination" name="destination" class="custom-control-input">
                <label class="custom-control-label" for="Destination">Destination</label>
            </div>
            <hr>
            <h5 class="text-left">Seller</h5>
            <div class="custom-control custom-radio">
                <input type="radio" id="Seller" name="seller" class="custom-control-input">
                <label class="custom-control-label" for="Seller">Seller</label>
            </div>
            <hr>
            <h5 class="text-left">Hotel Category</h5>
            <div class="custom-control custom-radio">
                <input type="radio" id="HotelCategory" name="hotelcat" class="custom-control-input">
                <label class="custom-control-label" for="HotelCategory">Hotel Category</label>
            </div> -->
            <?php $i = auth()->user()->id; ?>
          </aside>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
    
<style type="text/css">
    .custom-control-label::before{top:-1.5px;background-color:#ffffff;border:1px solid #681f4a;}
    .custom-control-label::after{top:-1.5px}
    .sticky-top{z-index: 999;}
    .input-group-text{background-color:#2180f6;border:1px solid #2180f6;color:#fff;}
    select.input-group-text option{background-color:#fff;border:1px solid #fff;color:#333;}
    .searchCenter{height: calc(100vh - 242px);display: flex;align-items: center;}
    select.input-group-text:focus-visible {outline:none;}
    .searchButtonMain{padding: 10.5px;line-height: 1;border: 1px solid #ced4da;border-left: 0;cursor: pointer;    color: #681f4a;}
    .sideFilter{background: #fff;padding: 15px;border-radius: 3px;top: 70px;}
    .HoverFav{position:absolute;background: #fff;padding: 10px;bottom: 100%;right: 0;box-shadow: 0px -3px 5px 0 #333;border-radius: 5px;display:none;}
    .HoverFav p:not(:last-child){margin-bottom:3px;}
    .HoverFav p:last-child{margin-bottom:0;}
    .gridFav:hover .HoverFav{display:block;}
    .ribbon-pop{background:linear-gradient(270deg, #4f396a, #576bb0);display: inline-block;padding:2px 12px 2px 6px;color:#fff;position:absolute;font-size:10px;right:0;}
    .ribbon-pop:before{content:"";width:0;height:0;top:0;position:absolute;right:0;border-right:8px solid #fff;border-top:10.2px solid transparent;border-bottom:8px solid transparent;}
    .ribbon-pop:after{height: 0;width: 0;border-top:8px solid #381f57;border-left:8px solid transparent;bottom:-8px;position: absolute;content: "";left: 0;}
    .ribbon{position: absolute;top: -6.1px;right: 10px;}
    .ribbon span{position:relative;display:block;text-align:center;background:#722e56;font-size:12px;line-height:1;padding:5px 8px 3px;border-top-right-radius:8px;width:42px;color:#fff}
    .ribbon span:before, .ribbon span:after{position: absolute;content: "";}
    .ribbon span:before{height: 6px;width: 6px;left: -6px;top: 0;background: #813b64;}
    .ribbon span:after{height: 6px;width: 8px;left: -8px;top: 0;border-radius: 8px 8px 0 0;background: #560e39;}
    .ribbon:after{position: absolute;content: "";width: 0;height: 0;border-left:21px solid transparent;border-right:21px solid transparent;border-top:4px solid #722e56;}
    .grey-fav{color: blue;}
</style>
<!-- Sorting start -->
<script>
    function sortSupAsc(sort){
        //alert('hi');
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('p .userprofileName').text();
            var y = $(b).find('p .userprofileName').text();
            if(x < y) return -1;
            if(x > y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
    function sortSupDesc(sort){
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('p .userprofileName').text();
            var y = $(b).find('p .userprofileName').text();
            if(x > y) return -1;
            if(x < y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
    function sortDateAsc(sort){
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('.dates').text();
            var y = $(b).find('.dates').text();
            if(x < y) return -1;
            if(x > y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
    function sortDateDesc(sort){
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('.dates').text();
            var y = $(b).find('.dates').text();
            if(x > y) return -1;
            if(x < y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
    function sortUnitsAsc(sort){
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('div .unit').text();
            var y = $(b).find('div .unit').text();
            if(x < y) return -1;
            if(x > y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
    function sortUnitsDesc(sort){
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('div .unit').text();
            var y = $(b).find('div .unit').text();
            if(x > y) return -1;
            if(x < y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
    function sortNightAsc(sort){
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('span .text-dark').text();
            var y = $(b).find('span .text-dark').text();
            if(x > y) return -1;
            if(x < y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
    function sortNightDesc(sort){
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('div .pprice').text();
            var y = $(b).find('div .pprice').text();
            if(x > y) return -1;
            if(x < y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
    function sortPriceAsc(sort){
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('div .pprice').text();
            var y = $(b).find('div .pprice').text();
            if(x > y) return -1;
            if(x < y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
    function sortPriceDesc(sort){
        var sortData = $('#filtered');
        var rows = sortData.find('.sortBox').get();
        rows.sort(function(a, b){
            var x = $(a).find('div .pprice').text();
            var y = $(b).find('div .pprice').text();
            if(x < y) return -1;
            if(x > y) return 1;
            return 0;
        });
        $.each(rows, function(index,row) {
            sortData.append(row);
        });
        return false;
    }
</script>
<!-- fav btn -->
<script>
    function getPkgId(e){
        if (confirm("Do you want to make it favourite?"))
        var dep_id = e.parentNode.querySelector('.departureID').innerHTML;
        //alert(dep_id);
        dep_id = dep_id.replace(/[\])}[{(]/g,'');
        // var 
        console.log(dep_id);
        $.ajax({
                  url:"{{ route('favourite')}}",
                  method:"GET",
                  data:{dep_id:dep_id},
                  // beforeSend  :   function () {
                  //     $('.fa-star').addClass('grey-fav');
                  // },        
                  success:function(data){
                  $(this).toggleClass( mdi-heart, addOrRemove )  
                  // if($(".grey-fav")[0])
                  // {
                  //  $('.fa-star').removeClass('grey-fav');
                  // }
                  // else
                  // {
                  //  $('.fa-star').addClass('grey-fav');
                  // }
                  //window.location.reload();
                  console.log(data);
                  //$('#filtered').html(data);
                  }
                });
    }
    function getPkgIds(id){
      //alert(dep_id);
        if (confirm("Do you want to save it?"))
        //  alert(dep_id);
        var id = id;
        //alert(id);
        console.log(id);
        $.ajax({
         url:"{{ route('favouritePkgData')}}",
         method:"GET",
         data:{id:id},
         success:function(data)
         {
           console.log(data);
         }
        });
    }
          
    
</script>

<script>
  var formPackagetype = '';
    $(document).ready(function(){
        $('#searchDeparture').keyup(function(){ 
            $('#autoSearchData').html('');
                var query = $(this).val();
                if(query.length>= 3)
                {
                    if(query != '')
                    {
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url:"{{ route('fetch_search') }}",
                            method:"POST",
                            data:{query:query, _token:_token},
                            success:function(data){
                                console.log(data);
                                $('#autoSearchData').fadeIn();  
                                $('#autoSearchData').html(data);
                            }
                        });
                    }
                }
        });
        $(document).mouseup(function (e) 
        {
            if($(e.target).closest("#autoSearchData").length === 0) 
            {
                $("#autoSearchData").hide();
            }
        });
        $("input[name='packageType']").change(function (){
        //console.log('pop open');
        // alert("Hi");
            $('#filtered').html('');
                var packageType = $(this).val();
                formPackagetype = packageType;
                var searchDestin = $('#searchDeparture').val();
                var type = $('#type').val();
                var destination = $('#destination').val();
                var dates_after= $('#day').val();
                var filters = $("input:radio[name=filter]:checked").val();
                  // $("input:checkbox[name=filter]:checked").each(function() 
                  // {
                  //   filter.push($(this).val());
                  // });
                var spinner ='<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden"><img src="{{asset('images/loader2.gif')}}" style="margin-left: 320px;"></span></div></div>';  
                $('#filtered').html(spinner);
                if(packageType != '')
                {
                    $.ajax({
                    url:"{{ route('ajax_view')}}",
                    method:"GET",
                    data:
                    {
                      packageType:packageType,
                      keyword:searchDestin,
                      dates:dates_after,
                      type:type,
                      destination:destination,
                      filter : filter
                    },
                    success:function(data){
                            console.log(data);  
                            //console.log('pop close');
                            $('#filtered').html(data);
                        }
                    });  
                }
            }); 
        });
</script>
<script>
    $(document).ready(function(){
        $("select.changeStatus").change(function(){
            //alert(formPackagetype);
            $("#filtered").html('');
            var packageType = formPackagetype ;
           // var packageTypeId = $(packageType).attr('id')
            var searchDestin = $('#searchDeparture').val();
            var type = $('#type').val();
            var destination = $('#destination').val();
            var dates_after = $(this).val();
            var filters = $("input:radio[name=filter]:checked").val();
            // $("input:radio[name=filter]:checked").each(function() 
            // {
            //     filter.push($(this).val());
            // });
            var spinner ='<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden"><img src="{{asset('images/loader2.gif')}}" style="margin-left: 320px;"></span></div></div>';
            $('#filtered').html(spinner);
                //alert(dates_after+'/'+type+'/'+searchDestin+'/'+packageType+'/'+filter);
            if(dates_after != '')
            {
                $.ajax({
                    url:"{{ route('ajax_view')}}",
                    method:"GET",
                    data:
                    {
                      packageType:packageType,
                      keyword:searchDestin,
                      dates:dates_after,
                      filter:filters,
                      type:type,
                      destination:destination
                    },
                    success:function(data){
                        console.log(data);
                        $('#filtered').html(data);
                    }
                });
            }

        });
    });
</script>


<script>
    $("input[name=filter]").on("click", function() {
      //var filters = $(this).val();
      var filters = $("input:radio[name=filter]:checked").val();
      //alert(filter);
      //formPackagetype = packageType;
      $('#filtered').html(''); 
        var packageType = formPackagetype ;
        var type = $('#type').val();
        var destination = $('#destination').val();
        var dates_after= $('#day').val();
        var searchDestin = $('#searchDeparture').val();
        // var array = [];
        //     $("input:checkbox[name=filter]:checked").each(function() 
        //     {
        //         array.push($(this).val());
        //     });
            var spinner ='<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden"><img src="{{asset('images/loader2.gif')}}" style="margin-left: 320px;"></span></div></div>';  
            $('#filtered').html(spinner);
                //alert(packageType+'/'+type+'/'+dates_after+'/'+array);    
     //alert(array);
         $.ajax({
            url:"{{ route('ajax_view')}}", // URL should be correct!
            type: "GET",
            async: true,
            cache: false,
            data: 
            {
                // _token: "{{ csrf_token() }}",
                packageType:packageType,
                keyword:searchDestin,
                dates:dates_after,
                type:type,
                filter : filters,
                destination:destination
            },
            //dataType: 'json', 
            success: function(data)
            { 
              console.log(data);
              $('#filtered').html(data);  
              //alert(response_data);
            }
         });
    });
 </script>

@endsection

        