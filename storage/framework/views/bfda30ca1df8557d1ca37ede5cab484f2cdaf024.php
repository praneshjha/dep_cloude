<div class="d-flex justify-content-between country_name">
  <div class="d-flex align-items-center">
    <picture class="country-img"><img src="<?php echo e($destinations['s3_url']); ?>flag/<?php echo e($destinations['country']['flag']); ?>" alt="flag" class="img-fluid"></picture>
    <h4 class="title_name"><span class="d-block"><?php echo e($destinations['dest_name']); ?></span><small><a href="javascript:void(0);"><?php echo e($destinations['country']['country_name']); ?></a></small></h4>
  </div>
</div>

<div class="d-flex flex-wrap box_country">
  <?php if($destinations['country']['capital'] != ''): ?>
  <div class="countryData">
    <h5>State/Province</h5>
    <a href="javascript:void(0);"><?php echo e($destinations['country']['capital']); ?></a>
  </div>
  <?php endif; ?>
  <?php if(count($destinations['country']['best_time_to_visits'])>0): ?>
  <div class="countryData">
    <h5>Best Time to Visit</h5>
    <a href="javascript:void(0);"><?php $__currentLoopData = $destinations['country']['best_time_to_visits']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $best_time_to_visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($best_time_to_visit['name']); ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></a>
  </div>
  <?php endif; ?>
  <?php if($destinations['country']['currency_code'] != ''): ?>
  <div class="countryData">
    <h5>Currency</h5>
    <a href="javascript:void(0);"><?php echo e($destinations['country']['currency_code']); ?>(<?php echo e($destinations['country']['currency_symbol']); ?>)</a>
  </div>
  <?php endif; ?>
  <?php if(count($destinations['country']['time_zones'])>0): ?>
  <div class="countryData">
    <h5>Time Zone</h5>
    <a href="javascript:void(0);"><i class="mdi mdi-map-clock-outline"></i> <?php $__currentLoopData = $destinations['country']['time_zones']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time_zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($time_zone['time_zone_code']); ?>, <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></a>
  </div>
  <?php endif; ?>
  <?php if(count($destinations['country']['climate_types'])>0): ?>
  <div class="countryData">
    <h5>Climate</h5>
    <a href="javascript:void(0);"><?php $__currentLoopData = $destinations['country']['climate_types']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $climate_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($climate_type['name']); ?>, <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></a>
  </div>
  <?php endif; ?>
  <?php if($destinations['country']['drives_on'] != ''): ?>
  <div class="countryData">
    <h5>Driving Side</h5>
    <a href="javascript:void(0);"><?php echo e($destinations['country']['drives_on']); ?></a>
  </div>
  <?php endif; ?>
  <?php if(count($destinations['nearBy'])>0): ?>
  <div class="countryData">
    <h5>Near by Destinations</h5>
    <a href="javascript:void(0);"><?php $__currentLoopData = $destinations['nearBy']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nearBys): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($nearBys['dest_name']); ?>, <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></a>
  </div>
  <?php endif; ?>
</div>
<?php if($destinations['weathers'] != ''): ?>
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
  <div class="temperature_head" style="background-image: url(<?php echo e(asset('images/WeatherImage_cloudy.webp')); ?>);">
    <div>
      <h2><?php echo e($celsius); ?><sup>&#176;</sup></h2>
      <p><?php echo e($weather); ?></p>
    </div>
    <div class="d-flex align-items-end">
      <div class="day_time d-flex mr-1">
        <h4 class="mr-2"><span class="d-inline-block pressure_arrow top">&#10141;</span> <?php echo e($sunrise); ?></h4>
        <h4><span class="d-inline-block pressure_arrow bottom">&#10141;</span> <?php echo e($sunset); ?></h4>
      </div>
      <picture class="temperature_img">
        <img src="<?php echo e($icon); ?>" alt="icon" class="img-fluid">
      </picture>
    </div>
  </div>
  <div class="d-flex justify-content-between flex-wrap temperature_content">
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="<?php echo e(asset('images/weather1.png')); ?>" alt="icon" class="img-fluid">
        </picture>
        <p>Temperature</p>
      </div>
      <p><?php echo e($celsiusMin); ?><sup>&#176;</sup></p>
    </div>
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="<?php echo e(asset('images/weather2.png')); ?>" alt="icon" class="img-fluid">
        </picture>
        <p>Wind</p>
      </div>
      <p><?php echo e($wnindSpeed); ?> m/s</p>
    </div>
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="<?php echo e(asset('images/weather3.png')); ?>" alt="icon" class="img-fluid">
        </picture>
        <p>Humidity</p>
      </div>
      <p><?php echo e($temps['humidity']); ?>&#37;</p>
    </div>
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="<?php echo e(asset('images/weather5.png')); ?>" alt="icon" class="img-fluid">
        </picture>
        <p>Pressure</p>
      </div>
      <p><span class="d-inline-block pressure_arrow bottom">&#10141;</span><?php echo e($temps['pressure']); ?></p>
    </div>
    <div class="list_item">
      <div class="d-flex align-items-center">
        <picture>
          <img src="<?php echo e(asset('images/weather7.png')); ?>" alt="icon" class="img-fluid">
        </picture>
        <p>Visibility</p>
      </div>
      <p><?php echo e($destinations['weathers']['visibility']); ?> meter</p>
    </div>
  </div>
</div>
<?php endif; ?>
<?php if($destinations['description'] != ''): ?>
<div class="country_description">
  <h4 class="title-h">About <?php echo e($destinations['dest_name']); ?></h4>
  <p><?php echo e($destinations['description']); ?></p>
</div>
<?php endif; ?>

<picture class="img_city">
  <img src="<?php echo e($destinations['s3_url']); ?>destination/<?php echo e($destinations['image']); ?>" onerror="this.src='https://departurecloud.com/images/No-Image-Departure-cloud.png';" alt="<?php echo e($destinations['dest_name']); ?>">
</picture>

<?php if(count($destinations['poi'])>0): ?>
<div class="top_destination" id="poi_major">
  <h4 class="title-h">Major Point Of Interest</h4>
  <ul class="all_img p-0" id="poi" >
    <?php $__currentLoopData = $destinations['poi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pois): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li id="top_poi_<?php echo e($key); ?>">
      <a href="javascript:void(0);" onclick="poiData('<?php echo e($pois['id']); ?>','<?php echo e($pois['attraction_name']); ?>');">
        <picture class="img_container">
          <img src="<?php echo e($pois['images_url']); ?>" onerror="this.src='https://departurecloud.com/images/No-Image-Departure-cloud.png';" alt="<?php echo e($pois['attraction_name']); ?>">
        </picture>
        <p lang="en"><?php echo e($pois['attraction_name']); ?></p>
    </a>
    </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
  <a href="javascript:void(0);" id="topPoiShow" class="show_more">Show more</a>
</div>
<?php endif; ?>
<?php if(count($destinations['airports'])>0): ?>
<div class="airport_container">
  <h4 class="title-h">Airports</h4>
  <ul class="pl-1 list-unstyled mt-3 mb-0">
    <?php $__currentLoopData = $destinations['airports']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="airportsPlane"><i class="fa fa-plane"></i> <?php echo e($airport['dest_name']); ?></li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
</div>
<?php endif; ?>

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
</script><?php /**PATH /var/www/html/departurecloud/resources/views/pullIT/destination.blade.php ENDPATH**/ ?>