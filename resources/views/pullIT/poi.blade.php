<div class="d-flex justify-content-between country_name">
  <div class="d-flex align-items-center">
    <h4 class="title_name"><span class="d-block">{{$pois['attraction_name']}}</span><small><a href="javascript:void(0);" onclick="destinationData('{{$pois['destination']['id']}}','{{$pois['destination']['dest_name']}}');">{{$pois['destination']['dest_name']}} <i class="fa fa-link" aria-hidden="true"></i></a></small></h4>
  </div>
</div>

<div class="d-flex flex-wrap box_country">
  @if(count($pois['country']['best_time_to_visits'])>0)
  <div class="countryData">
    <h5>Best Time to Visit</h5>
    <a href="javascript:void(0);">@foreach($pois['country']['best_time_to_visits'] as $key => $best_time) {{$best_time['name']}}, @endforeach</a>
  </div>
  @endif
  @if($pois['country']['currency'] != '')
  <div class="countryData">
    <h5>Currency</h5>
    <a href="javascript:void(0);">{{$pois['country']['currency']}}({{$pois['country']['currency_symbol']}}) {{$pois['country']['currency_code']}}</a>
  </div>
  @endif
  @if(count($pois['country']['time_zones'])>0)
  <div class="countryData">
    <h5>Time Zone</h5>
    <a href="javascript:void(0);"><i class="mdi mdi-map-clock-outline"></i>@foreach($pois['country']['time_zones'] as $key => $time_zone) {{$time_zone['time_zone_code']}}, @endforeach</a>
  </div>
  @endif
  @if(count($pois['country']['climate_types'])>0)
  <div class="countryData">
    <h5>Climate</h5>
    <a href="javascript:void(0);">@foreach($pois['country']['climate_types'] as $key => $climate_type) {{$climate_type['name']}}, @endforeach</a>
  </div>
  @endif
  @if($pois['attraction_type'] != '')
  <div class="countryData">
    <h5>Type</h5>
    <a href="javascript:void(0);">{{$pois['attraction_type']}}</a>
  </div>
  @endif
  @if($pois['country']['drives_on'] != '')
  <div class="countryData">
    <h5>Driving Side</h5>
    <a href="javascript:void(0);">{{$pois['country']['drives_on']}}</a>
  </div>
  @endif
  @if($pois['website'] != '')
  <div class="countryData">
    <h5>Website</h5>
    <a href="javascript:void(0);">{{$pois['website']}}</a>
  </div>
  @endif
  
  @if($pois['address'] != '')
  <div class="countryData">
    <h5>Address</h5>
    <a href="javascript:void(0);">{{$pois['address']}}</a>
  </div>
  @endif
</div>

@if($pois['weathers'] != '')
<?php 
  $temps = $pois['weathers']['main'];
  $temp = $temps['temp'];
  $celsius = floor($temp - 273);

  $weathers = $pois['weathers']['weather'];
  $weather = $weathers[0]['description'];
  $icon = "http://openweathermap.org/img/w/".$weathers[0]['icon'].".png";
  $sunriseTimestamp = $pois['weathers']['sys']['sunrise']+($pois['weathers']['timezone']);
  $sunrise = date('h:i A',$sunriseTimestamp);
  $sunsetTimestamp = $pois['weathers']['sys']['sunset']+($pois['weathers']['timezone']);
  $sunset = date('h:i A',$sunsetTimestamp);

  $tempMin = $temps['temp_min'];
  $celsiusMin = floor($tempMin - 273);
  $tempMax = $temps['temp_min'];
  $celsiusMax = floor($tempMax - 273);

  $wnindSpeed = $pois['weathers']['wind']['speed'];
?>
<div class="weather_report">
  <h4 class="title-h" style="color: #fad706"><span class="blinks">Weather Today in <span style="color: #fd3000fc">{{$pois['attraction_name']}}</span></span></h4>
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
      <p>{{$celsiusMin}}<sup>&#176;</sup> {{$weather}}</p>
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
      <p>{{$pois['weathers']['visibility']}} meter</p>
    </div>
  </div>
</div>
@endif

@if($pois['description'] != '')
<div class="country_description">
  <p>{{$pois['description']}}</p>
</div>
@endif
<div class="attraction_container">
  <h4 class="title-h">Attraction Images</h4>
  @if(count($pois['images'])>0)
  <div class="d-sm-flex attraction_img">
    @foreach($pois['images'] as $image)
    <picture class="img_content">
      <img src="{{$image['image_path']}}" onerror="this.src='https://departurecloud.com/images/No-Image-Departure-cloud.png';" alt="{{$image['id']}}">
    </picture>
    @endforeach
  </div>
  @endif
</div>

@if(count($pois['airports'])>0)
<div class="airport_container">
  <h4 class="title-h">Airports</h4>
  <ul class="pl-1 list-unstyled mt-3 mb-0">
    @foreach($pois['airports'] as $airport)
    <li class="airportsPlane"><i class="fa fa-plane"></i> {{$airport['dest_name']}}</li>
    @endforeach
  </ul>
</div>
@endif
@if(count($pois['country']['electrical_sockets'])>0)
<div class="socket_container">
  <h4 class="title-h">Socket Type</h4>
  <div class="d-flex mt-3" style="margin: 0 -4px;">
    @foreach($pois['country']['electrical_sockets'] as $electrical_socket)
    <div class="socket_content">
      <div class="socket_img">
        <img src="{{$pois['s3_url']}}sockets/{{$electrical_socket['image']}}" alt="icon" class="img-fluid">
      </div>
      <p>{{$electrical_socket['name']}}</p>
    </div>
    @endforeach
  </div>
</div>
@endif
