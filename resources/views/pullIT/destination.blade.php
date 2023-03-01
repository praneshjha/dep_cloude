<div class="d-flex justify-content-between country_name">
  <div class="d-flex align-items-center">
    <picture class="country-img"><img src="{{$destinations['s3_url']}}flag/{{$destinations['country']['flag']}}" alt="flag" class="img-fluid"></picture>
    <h4 class="title_name"><span class="d-block">{{$destinations['dest_name']}}</span><small><a href="javascript:void(0);">{{$destinations['country']['country_name']}}</a></small></h4>
  </div>
</div>

<div class="d-flex flex-wrap box_country">
  @if($destinations['country']['capital'] != '')
  <div class="countryData">
    <h5>State/Province</h5>
    <a href="javascript:void(0);">{{$destinations['country']['capital']}}</a>
  </div>
  @endif
  @if(count($destinations['country']['best_time_to_visits'])>0)
  <div class="countryData">
    <h5>Best Time to Visit</h5>
    <a href="javascript:void(0);">@foreach($destinations['country']['best_time_to_visits'] as $best_time_to_visit) {{$best_time_to_visit['name']}} @endforeach</a>
  </div>
  @endif
  @if($destinations['country']['currency_code'] != '')
  <div class="countryData">
    <h5>Currency</h5>
    <a href="javascript:void(0);">{{$destinations['country']['currency_code']}}({{$destinations['country']['currency_symbol']}})</a>
  </div>
  @endif
  @if(count($destinations['country']['time_zones'])>0)
  <div class="countryData">
    <h5>Time Zone</h5>
    <a href="javascript:void(0);"><i class="mdi mdi-map-clock-outline"></i> @foreach($destinations['country']['time_zones'] as $time_zone) {{$time_zone['time_zone_code']}}, @endforeach</a>
  </div>
  @endif
  @if(count($destinations['country']['climate_types'])>0)
  <div class="countryData">
    <h5>Climate</h5>
    <a href="javascript:void(0);">@foreach($destinations['country']['climate_types'] as $climate_type) {{$climate_type['name']}}, @endforeach</a>
  </div>
  @endif
  @if($destinations['country']['drives_on'] != '')
  <div class="countryData">
    <h5>Driving Side</h5>
    <a href="javascript:void(0);">{{$destinations['country']['drives_on']}}</a>
  </div>
  @endif
  @if(count($destinations['nearBy'])>0)
  <div class="countryData">
    <h5>Near by Destinations</h5>
    <a href="javascript:void(0);">@foreach($destinations['nearBy'] as $nearBys){{$nearBys['dest_name']}}, @endforeach</a>
  </div>
  @endif
</div>
@if($destinations['weathers'] != '')
<?php 
  $temps = $destinations['weathers']['main'];
  $temp = $temps['temp'];
  $celsius = floor($temp - 273);

  $weathers = $destinations['weathers']['weather'];
  $weather = $weathers[0]['description'];
  $icon = "http://openweathermap.org/img/w/".$weathers[0]['icon'].".png";
  $sunriseTimestamp = $destinations['weathers']['sys']['sunrise']+($destinations['weathers']['timezone']);
  $sunrise = date('h:i A',$sunriseTimestamp);
  $sunsetTimestamp = $destinations['weathers']['sys']['sunset']+($destinations['weathers']['timezone']);
  $sunset = date('h:i A',$sunsetTimestamp);

  $tempMin = $temps['temp_min'];
  $celsiusMin = floor($tempMin - 273);
  $tempMax = $temps['temp_min'];
  $celsiusMax = floor($tempMax - 273);

  $wnindSpeed = $destinations['weathers']['wind']['speed'];
?>
<div class="weather_report">
  <div class="temperature_head" style="background-image: url({{asset('images/WeatherImage_cloudy.webp')}});">
    <div>
      <h2>{{$celsius}}<sup>&#176;</sup></h2>
      <p>{{$weather}}</p>
    </div>
    <div class="d-flex align-items-end">
      <div class="day_time d-flex mr-1">
        <h4 class="mr-2"><span class="d-inline-block pressure_arrow top">&#10141;</span> {{$sunrise}}</h4>
        <h4><span class="d-inline-block pressure_arrow bottom">&#10141;</span> {{$sunset}}</h4>
      </div>
      <picture class="temperature_img">
        <img src="{{$icon}}" alt="icon" class="img-fluid">
      </picture>
    </div>
  </div>
  <div class="d-flex justify-content-between flex-wrap temperature_content">
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="{{asset('images/weather1.png')}}" alt="icon" class="img-fluid">
        </picture>
        <p>Temperature</p>
      </div>
      <p>{{$celsiusMin}}<sup>&#176;</sup></p>
    </div>
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="{{asset('images/weather2.png')}}" alt="icon" class="img-fluid">
        </picture>
        <p>Wind</p>
      </div>
      <p>{{$wnindSpeed}} m/s</p>
    </div>
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="{{asset('images/weather3.png')}}" alt="icon" class="img-fluid">
        </picture>
        <p>Humidity</p>
      </div>
      <p>{{$temps['humidity']}}&#37;</p>
    </div>
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="{{asset('images/weather5.png')}}" alt="icon" class="img-fluid">
        </picture>
        <p>Pressure</p>
      </div>
      <p><span class="d-inline-block pressure_arrow bottom">&#10141;</span>{{$temps['pressure']}}</p>
    </div>
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="{{asset('images/weather7.png')}}" alt="icon" class="img-fluid">
        </picture>
        <p>Visibility</p>
      </div>
      <p>{{$destinations['weathers']['visibility']}} meter</p>
    </div>
  </div>
</div>
@endif
@if($destinations['description'] != '')
<div class="country_description">
  <h4 class="title-h">About {{$destinations['dest_name']}}</h4>
  <p>{{$destinations['description']}}</p>
</div>
@endif

<picture class="img_city">
  <img src="{{$destinations['s3_url']}}destination/{{$destinations['image']}}" onerror="this.src='https://departurecloud.com/images/No-Image-Departure-cloud.png';" alt="{{$destinations['dest_name']}}">
</picture>

@if(count($destinations['poi'])>0)
<div class="top_destination" id="poi_major">
  <h4 class="title-h">Major Point Of Interest</h4>
  <ul class="all_img p-0" id="poi" >
    @foreach($destinations['poi'] as $key => $pois)
    <li id="top_poi_{{$key}}">
      <a href="javascript:void(0);" onclick="poiData('{{$pois['id']}}','{{$pois['attraction_name']}}');">
        <picture class="img_container">
          <img src="{{$pois['images_url']}}" onerror="this.src='https://departurecloud.com/images/No-Image-Departure-cloud.png';" alt="{{$pois['attraction_name']}}">
        </picture>
        <p lang="en">{{$pois['attraction_name']}}</p>
    </a>
    </li>
    @endforeach
  </ul>
  <a href="javascript:void(0);" id="topPoiShow" class="show_more">Show more</a>
</div>
@endif
@if(count($destinations['airports'])>0)
<div class="airport_container">
  <h4 class="title-h">Airports</h4>
  <ul class="pl-1 list-unstyled mt-3 mb-0">
    @foreach($destinations['airports'] as $airport)
    <li class="airportsPlane"><i class="fa fa-plane"></i> {{$airport['dest_name']}}</li>
    @endforeach
  </ul>
</div>
@endif

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