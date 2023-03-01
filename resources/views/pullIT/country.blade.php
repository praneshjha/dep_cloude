<div class="d-flex justify-content-between country_name">
  <div class="d-flex align-items-center">
    <picture class="country-img"><img src="{{$country_data['s3_url']}}flag/{{$country_data['flag']}}" alt="flag" class="img-fluid"></picture>
    <h4 class="title_name"><span class="d-block">{{$country_data['country_name']}}</span>
    @if($country_data['official_name'] != '')
    <small>
      {{$country_data['official_name']}}
    </small>
    @endif
  </h4>
  </div>
</div>

<div class="d-flex flex-wrap box_country">
  @if($country_data['capital'] != '')
  <div class="countryData">
    <h5>Capital</h5>
    <a href="javascript:void(0);">{{$country_data['capital']}} </a>
  </div>
  @endif
  @if($country_data['largest_city'] != '')
  <div class="countryData">
    <h5>Largest City</h5>
    <a href="javascript:void(0);">{{$country_data['largest_city']}}</a>
  </div>
  @endif
  @if(count($country_data['best_time_to_visits'])>0)
  <div class="countryData">
    <h5>Best Time to Visit</h5>
    <a href="javascript:void(0);">@foreach($country_data['best_time_to_visits'] as $key => $best_time) {{$best_time['name']}}, @endforeach</a>
  </div>
  @endif
  {{--<div class="countryData">
    <h5>Temperature</h5>
    <a href="javascript:void(0);">33 <sup>•</sup>C</a>
  </div> --}}
  @if($country_data['currency'] != '')
  <div class="countryData">
    <h5>Currency</h5>
    <a href="javascript:void(0);">{{$country_data['currency']}} {{$country_data['currency_code']}} {{$country_data['currency_symbol']}}</a>
  </div>
  @endif
  @if(count($country_data['time_zones'])>0)
  <div class="countryData">
    <h5>Time Zone</h5>
    <a href="javascript:void(0);"><i class="mdi mdi-map-clock-outline"></i>@foreach($country_data['time_zones'] as $key => $time_zone) {{$time_zone['time_zone_code']}}, @endforeach</a>
  </div>
  @endif
  @if(count($country_data['climate_types'])>0)
  <div class="countryData">
    <h5>Climate</h5>
    <a href="javascript:void(0);">@foreach($country_data['climate_types'] as $key => $climate_type) {{$climate_type['name']}}, @endforeach</a>
  </div>
  @endif
  @if($country_data['drives_on'] != '')
  <div class="countryData">
    <h5>Driving Side</h5>
    <a href="javascript:void(0);">{{$country_data['drives_on']}}</a>
  </div>
  @endif
  <div class="countryData">
    <h5>Official Language(s)</h5>
    <a href="javascript:void(0);"></a>
  </div>
  @if($country_data['population'] != '')
  <div class="countryData">
    <h5>Population</h5>
    <a href="javascript:void(0);">{{$country_data['population']}}</a>
  </div>
  @endif
  @if($country_data['area'] != '')
  <div class="countryData">
    <h5>Area</h5>
    <a href="#">{{$country_data['area']}} {{$country_data['area_unit']}}</a>
  </div>
  @endif
</div>

<div class="country_description">
  <h4 class="title-h">About {{$country_data['country_name']}}</h4>
  <p>{{$country_data['description']}}</p>
</div>
@if(count($country_data['destinations'])>0)
<div class="top_destination" id="top_destination">
  <h4 class="title-h">Top Destination</h4>
  
  <div class="d-sm-flex flex-wrap mt-3" id="top_dest_grid" style="margin: 0 -4px;">
    @foreach($country_data['destinations'] as $key => $destination)
    <?php
      $iPath = $country_data['s3_url'].'destination/'.$destination['image'];
      // $image = @getimagesize($iPath); 
    ?>
    <div class="top_dest_container" id='top_dest_{{$key}}'>
      <a href="javascript:void(0);" onclick="destinationData('{{$destination['id']}}','{{$destination['dest_name']}}');">
        <div class="img_container">
          <img src="{{$iPath}}" onerror="this.src='https://departurecloud.com/images/No-Image-Departure-cloud.png';" alt="img">
        </div>
        <p> {{$destination['dest_name']}}</p>
      </a>
    </div>
    @endforeach
  </div>

  <a href="javascript:void(0);" id="topDestinationShow" class="show_more">Show more</a>
</div>
@endif
@if(count($country_data['poi'])>0)
<div class="top_destination" id="poi_major">
  <h4 class="title-h">Major Point Of Interest</h4>
  <ul class="all_img p-0" id="poi" >
    @foreach($country_data['poi'] as $key => $pois)
    <li id="top_poi_{{$key}}">
      <a href="javascript:void(0);" onclick="poiData('{{$pois['id']}}','{{$pois['attraction_name']}}');">
        <picture class="img_container">
          <img src="{{$pois['images_path']}}" onerror="this.src='https://departurecloud.com/images/No-Image-Departure-cloud.png';" alt="img">
        </picture>
        <p lang="en">{{$pois['attraction_name']}}</p>
        <div class="see_more">See Details</div>
      </a>
    </li>
    @endforeach
  </ul>
  <a href="javascript:void(0);" id="topPoiShow" class="show_more">Show more</a>
</div>
@endif

@if(count($country_data['airports'])>0)
<div class="airport_container1">
  <h4 class="title-h">Airports (International)</h4>
  <ul class="pl-1 list-unstyled mt-3 mb-0">
    @foreach($country_data['airports'] as $key => $airport)
    <li class="airportsPlane" id="top_airport_{{$key}}"><i class="fa fa-plane"></i> {{$airport['airport_name']}}</li>
    @endforeach
  </ul>
  <a href="javascript:void(0);" id="topAirportShow" class="show_more">Show more</a>
</div>
@endif
@if(count($country_data['electrical_sockets'])>0)
<div class="socket_container">
  <h4 class="title-h">Socket Type</h4>
  <div class="d-flex mt-3" style="margin: 0 -4px;">
    @foreach($country_data['electrical_sockets'] as $key => $electrical_socket)
    <div class="socket_content">
      <div class="socket_img">
        <img src="{{$country_data['s3_url']}}sockets/{{$electrical_socket['image']}}" alt="icon" class="img-fluid">
      </div>
      <p>{{$electrical_socket['name']}}</p>
    </div>
    @endforeach
  </div>
</div>
@endif

<script type="text/javascript">
  function topDestinationCheckLimit(){
    let setlimitHide = $('#top_dest_grid').children('.top_dest_container');
    if(setlimitHide.length < 9){
      $("#topDestinationShow").hide();
    }
  }
  topDestinationCheckLimit();
  $(".top_dest_container").slice(0, 8).show();
  $("#topDestinationShow").click(()=>{
   
    $(".top_dest_container:hidden").slice(0, 8).show();
    if($("#topDestinationShow").text() == 'Show less'){
      $("#topDestinationShow").text('Show more');
      let setlimitHide = $('#top_dest_grid').children('.top_dest_container');
      for (let i = 0; i < setlimitHide.length; i++) {
        $('.top_dest_container').hide();
      }
      for (let i = 0; i < setlimitHide.length; i++) {
        if(i < 8){
          //console.log(i);
          $('#top_dest_'+i).show();
        }
      }
    }
    if ($(".top_dest_container:hidden").length == 0) { 
      $("#topDestinationShow").text('Show less');
    }
  });
</script>

<script type="text/javascript">
  function topPoiCheckLimit(){
    let setlimitHide = $('#poi').children('li');
    if(setlimitHide.length < 16){
      $("#topPoiShow").hide();
    }
  }
  topPoiCheckLimit();
  $("#poi li").slice(0, 16).show();
  $("#topPoiShow").click(()=>{
   
    $("#poi li:hidden").slice(0, 16).show();
    if($("#topPoiShow").text() == 'Show less'){
      $("#topPoiShow").text('Show more');
      let setlimitHide = $('#poi').children('li');
      for (let i = 0; i < setlimitHide.length; i++) {
        $('#poi li').hide();
      }
      for (let i = 0; i < setlimitHide.length; i++) {
        if(i < 16){
          //console.log(i);
          $('#top_poi_'+i).show();
        }
      }
    }
    if ($("#poi li:hidden").length == 0) { 
      $("#topPoiShow").text('Show less');
    }
  });
</script>

<script type="text/javascript">

  if($('.airport_container1 ul li.airportsPlane').length < 26){
    //alert($('.airport_container1 ul li.airportsPlane').length);
    $('.airport_container1 ul li.airportsPlane').css('display','flex');
    $("#topAirportShow").hide();
  } else {
    $(".airport_container1 ul li.airportsPlane:hidden").slice(0, 20).css('display','flex');
  }

  $("#topAirportShow").click(()=>{
    console.log($('.airport_container1 ul li').length);
    $(".airport_container1 ul li.airportsPlane:hidden").slice(0, 20).css('display','flex');
    if($("#topAirportShow").text() == 'Show less'){
      $("#topAirportShow").text('Show more');
      let setlimitHide = $('.airport_container1 ul li');
      for (let i = 0; i < setlimitHide.length; i++) {
        $('.airport_container1 ul li').css('display','none');
      }
      for (let i = 0; i < setlimitHide.length; i++) {
        if(i < 20){
          $('#top_airport_'+i).css('display','flex');
        }
      }
    }
    if ($(".airport_container1 ul li.airportsPlane:hidden").length == 0) { 
      $("#topAirportShow").text('Show less');
    }
  });
</script>