<?php
  $user = auth()->user()->id;
  $login_time = auth()->user()->last_login_at;
  $date = date("Y-m-d");
  $i = 0;
?>

<?php $__env->startSection('tagSection'); ?>
<title>Dashboard | Departure Cloud</title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
  <div class="wrapper">
    <div class="wrapperOverlay"></div>
    <?php if(empty($_GET)): ?>
    <div class="searchCenter">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-8 m-auto">
            <div class="card p-3">
              <form class="searchPage">
                <div class="input-group ">
                  <input type="search" name="keyword" class="form-control" id="searchDeparture" placeholder="Search Departures, Destinations and Suppliers... " autocomplete="off" required>
                  <?php if(isset($_GET['type'])): ?>
                  <input type="hidden" name="type" value="<?php echo e($_GET['type']); ?>">
                  <?php else: ?>
                  <input type="hidden" name="type" value="<?php echo e(11); ?>">
                  <?php endif; ?>
                  <div class="searchButtonMain"><i class="fas fa-search"></i><span id="autoSearchData"></span></div>
                </div>
              </form>
              <!-- <hr>
             <h5><a href="#">Goa</a></h5> -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if($favSupCount != "" || count($fav_pkg_select) > 0): ?>
  <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3 class="text-white"><i class="mdi mdi-heart-outline mr-1"></i>Your favorites</h3>
        </div>
      <?php $__currentLoopData = $fav_sup_select; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
      <div class="col-md-2 gridFav d-flex" id="gridFav_pop_<?php echo e($i); ?>">
        <div class="card p-0 m-0 w-100">
          <!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
          <div class="card-body p-1 pl-2">
            <?php if($new_dep > 0) { ?> 
              <div class="ribbon"><span>New</span></div> 
            <?php } ?> 
            <h5 class="card-title">
              <a href='<?php echo e(url("dashboard")); ?>?type=13&keyword=<?php echo e($supplier->company_name); ?>'><?php echo e($supplier->company_name); ?></a>
            </h5>
            <p><span>Total Package :</span> <strong><?php echo e($total_departure); ?></strong></p>
            <!-- <hr>
            <p class="card-text">Currently active departure are <br> <b><?php echo e($total_departure); ?></b></p> -->
            <a href="javascript:void(0);" class="showFavPopclick" id="pop_<?php echo e($i); ?>"><i class="mdi mdi-comment-plus-outline"></i></a>
          </div>
           <div class="HoverFav">
                <div class="d-flex"> 
                  <picture>
                    <?php if($supplier->logo != ""): ?>
                    <img src="<?php echo e(asset('companyLogo/' . $supplier->logo)); ?>" width="40" class="mr-2">
                    <?php else: ?>
                    <img src="<?php echo e(asset('assets1/images/supplier_icon.png')); ?>" width="40" class="mr-2">
                    <?php endif; ?>
                  </picture>                   
                    <div> <?php echo e($supplier->company_name); ?> is giving best services <strong><?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $det): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <a href='<?php echo e(url("dashboard")); ?>?type=13&keyword=<?php echo e($supplier->company_name); ?>&destination=<?php echo e($det->from); ?>' class="supplier-dest"><i class="mdi mdi-map-marker-radius"></i> <?php echo e($det->from); ?></a> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></strong> in these destinations.</div>
                </div>
            </div>
        </div>       
      </div>
      <?php $i++; } ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = $fav_pkg_select; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg_show): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-2 gridFav d-flex">
        <div class="card p-0 m-0 w-100">
          <!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
          <div class="card-body p-1 pl-2">
            <h5 class="card-title">
              <a href="<?php echo e(route('all_departure_details',$pkg_show->id)); ?>"><?php echo e($pkg_show->title); ?></a>
            </h5>
            <div><span>Date:</span> <?php echo e(date('d M, Y', strtotime($pkg_show->start_date))); ?></div>
            <div>Seats (A/T) : <strong ><?php if($pkg_show->available_seat == "" ): ?> NA <?php else: ?><?php echo e($pkg_show->available_seat); ?><?php endif; ?></strong> / <span><?php echo e($pkg_show->total_seat); ?></span></div>
        </div>       
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php if($total_counted >= 5): ?>
    <div class="col-md-2 gridFav d-flex" style="align-self:center;">
      <div class="card p-0 m-0 ">
        <div class="card-body p-1 pl-2">
          <h5 class="card-title">
            <a href="<?php echo e(route('profile')); ?>#favSupplier"><i class="mdi mdi-plus-box"></i> Show More</a>

          </h5>
        </div>
      </div>       
    </div>
    <?php endif; ?>         
  </div>
<?php endif; ?>
  <?php else: ?>


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
                    // if(isset($_GET['packageType'])) { $pacType = $_GET['packageType']; }
                    // else { $pacType = "" ; }
                    // if(isset($_GET['dates'])) { $dates = $_GET['dates']; } else { $dates = "" ; }
                    ?>
                    <input type="search" name="keyword" class="form-control" id="searchDeparture" placeholder="Search Departures, Destinations and Suppliers... " autocomplete="off" <?php if(empty($_GET['keyword'])): ?> <?php else: ?>
                    value="<?php echo e($_GET['keyword']); ?>"<?php endif; ?> required>
                    <input type="hidden" name="type" <?php if(empty($_GET['type'])): ?> <?php else: ?> value="<?php echo e($_GET['type']); ?>"<?php endif; ?> id="type">
                    <input type="hidden" name="destination" <?php if(empty($_GET['destination'])): ?> <?php else: ?> value="<?php echo e($_GET['destination']); ?>"<?php endif; ?> id="destination">
                    
                    <div id="autoSearchData" style="z-index:1090;">
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
          
          <br>
          <div class="row gridviewRow" id="filtered">
            <?php echo $__env->make('dashboard/card', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          </div>
          <!--  -->
          
          <div class="col-md-12 pagiNate" style="text-align:right;"><?php echo e($departures->withQueryString()->links()); ?></div>
        </div>
        <div class="col-md-3">
          <aside class="sideFilter ">
            <a href="#" onclick="window.location.reload(true);">Clear</a>
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0 mt-0">
                  <a type="button" class="btn btn-link collapsed text-left w-100" id="collapseShow" href="#filter1" role="button" aria-expanded="true" aria-controls="filter1">Filter</a>
                </h5>
              </div>
              <div  class="collapse show" id="filter1">
                <div class="card-body">
                  <form method="GET" type="GET" name="filter" id="filter">
                    <!-- <?php echo csrf_field(); ?> -->
                      <div class="custom-control custom-radio">
                      <?php if(($_GET['type'] == 13)): ?>
                        <input type="radio" id="BestSold" name="filter" value='3' class="custom-control-input">
                        <label class="custom-control-label" for="Bestsold">Best Seller</label>
                      <?php else: ?>
                        <input type="radio" id="TopSeller" name="filter" value='1' class="custom-control-input">
                        <label class="custom-control-label" for="TopSeller">Best Seller</label>
                      <?php endif; ?>
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
              </div>
            </div>
            <div id="accordion">
              

            </div>
            <!-- <h5 class="text-left">Filter</h5>
            <hr>
            <h6 class="text-right"></h6> -->
            
              
          </div>
        <hr>
            
            <?php $i = auth()->user()->id; ?>
          </aside>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
    
<style type="text/css">
    .custom-control-label::before{top:-1.5px;background-color:#ffffff;border:1px solid #681f4a;}
    .custom-control-label::after{top:-1.5px}
    .sticky-top{z-index: 999;}
    .input-group-text{background-color:#2180f6;border:1px solid #2180f6;color:#fff;}
    select.input-group-text option{background-color:#fff;border:1px solid #fff;color:#333;}
    .searchCenter{height: calc(100vh - 287px);display: flex;align-items: center;}
    select.input-group-text:focus-visible {outline:none;}
    .searchButtonMain{padding: 10.5px;line-height: 1;border: 1px solid #ced4da;border-left: 0;cursor: pointer;    color: #681f4a;}
    .sideFilter{background: #fff;padding: 15px;border-radius: 3px;top: 70px;}
    .HoverFav{position: absolute;background: #fff;padding: 20px;bottom:115%;left: 0;box-shadow: 0px -3px 5px 0 #333;border-radius: 5px;display: none;width: 360px;border-width: 5px;border-style: double;border-image-source:linear-gradient(to left, #2884f6, #681f4a);border-image-slice: 1;}
    .HoverFav:after{content:"";width: 0;height: 0;border-left:10px solid transparent;border-right:10px solid transparent;border-top:15px solid #fff;position: absolute;top: 100%;}
    .HoverFav p:last-child{margin-bottom:0;}
    .gridFav:hover .HoverFav{/*display:block;*/}
    .gridFav.showFavPop .HoverFav{display:block;}
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
    .supplier-dest:not(:last-child):after{content:",";position:relative;}
    .showFavPopclick{position: absolute;bottom: 5px;right: 10px;font-size: 16px;color: #681f4a;}
    .sideFilter .card-header{padding:0;}
    
</style>

<!-- Sorting start -->
<script>
  $(document).ready(function(){
    $('.showFavPopclick').click(function(){
     /* $('.gridFav').removeClass('showFavPop')*/
      let gridid = $(this).attr('id')
      let sameGrid=false;
      if($('#gridFav_'+gridid).hasClass('showFavPop')){
        sameGrid=true;
         /*$('#gridFav_'+gridid).toggleClass('showFavPop')*/
      }else{
        sameGrid=false;
        /*$('.gridFav').removeClass('showFavPop');*/
      }

      if(sameGrid){
        $('#gridFav_'+gridid).toggleClass('showFavPop')
      }else{
       $('.gridFav').removeClass('showFavPop');
       console.log('remove')
      $('#gridFav_'+gridid).addClass('showFavPop')
     }

     
    })
  })
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

  function getSupId(id){
    if (confirm("Do you want to make it favourite?"))
    var dep_id = id;
    console.log(dep_id);
    $.ajax({
      url:"<?php echo e(route('favourite')); ?>",
      method:"GET",
      data:{dep_id:dep_id},
      success:function(data){
        $('#fav_icon').css({
          'color': '#ad1707'
        });
        console.log(data);
      }
    });
  }
  function getSupId_Del(id){
    if (confirm("Do you want to remove it as favourite?"))
    var dep_id = id;
    console.log(dep_id);
    $.ajax({
      url:"<?php echo e(route('favourite')); ?>",
      method:"GET",
      data:{dep_id:dep_id},
      success:function(data){
        $('#fav_icon').css({
          'color': '#6c757d'
        });
        console.log(data);
      }
    });
  }

  function getPkgIDs(id){
    //alert(dep_id);
    if (confirm("Do you want to save it?"))
    //  alert(dep_id);
    var id = id;
    //alert(id);
    console.log(id);
    $.ajax({
      url:"<?php echo e(route('favouritePkgData')); ?>",
      method:"GET",
      data:{id:id},
      success:function(data)
      {
        $('favPkgIn'+id).css({
          'color':'blue'
        });
      console.log(data);
      }
    });
  }
  function getPkgIDs_Del(id){
  //alert(dep_id);
    if (confirm("Do you want to remove it as favourite?"))
    //  alert(dep_id);
    var id = id;
      //alert(id);
    console.log(id);
    $.ajax({
      url:"<?php echo e(route('favouritePkgData')); ?>",
      method:"GET",
      data:{id:id},
      success:function(data)
      {
        $('favPkgDel'+id).css({
          'color':'grey'
        });
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
                            url:"<?php echo e(route('fetch_search')); ?>",
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
                var spinner ='<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden"><img src="<?php echo e(asset('images/loader2.gif')); ?>" style="margin-left: 320px;"></span></div></div>';  
                $('#filtered').html(spinner);
                if(packageType != '')
                {
                    $.ajax({
                    url:"<?php echo e(route('ajax_view')); ?>",
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
            var spinner ='<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden"><img src="<?php echo e(asset('images/loader2.gif')); ?>" style="margin-left: 320px;"></span></div></div>';
            $('#filtered').html(spinner);
              //alert(dates_after+'/'+type+'/'+searchDestin+'/'+packageType+'/'+filter);
            if(dates_after != '')
            {
                $.ajax({
                    url:"<?php echo e(route('ajax_view')); ?>",
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
 
 $(document).ready(function(){
  $("#collapseShow").click(function(){
    $("#filter1").toggle();
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
        var spinner ='<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden"><img src="<?php echo e(asset('images/loader2.gif')); ?>" style="margin-left: 320px;"></span></div></div>';  
        $('#filtered').html(spinner);
        $.ajax({
            url:"<?php echo e(route('ajax_view')); ?>", // URL should be correct!
            type: "GET",
            async: true,
            cache: false,
            data: 
            {
              // _token: "<?php echo e(csrf_token()); ?>",
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

<?php $__env->stopSection(); ?>

        
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/departure/resources/views/dashboard/dashboard.blade.php ENDPATH**/ ?>