<!-- Footer Start -->
<footer class="footer">
     <div class="loading_body" id="loading_body" style="display:none;">Loading&#8230;</div>
     <div id="pageMessages">

</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <script type="application/javascript">document.write(new Date().getFullYear())</script> © www.departurecloud.com
            </div>
        </div>
    </div>
</footer>
<!-- <div id="dc_change_theme">Change Theme</div> -->
<!-- end Footer -->


<!-- Vendor js -->


<script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" ></script>

<script  src="<?php echo e(asset('js/app.js')); ?>"></script>
 
<script src="<?php echo e(asset('assets1/libs/multiselect/jquery.multi-select.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/select2/select2.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/jquery-mockjax/jquery.mockjax.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/autocomplete/jquery.autocomplete.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/bootstrap-select/bootstrap-select.min.js')); ?>"></script>

<script src="<?php echo e(asset('assets1/libs/datatables/jquery.dataTables.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/dataTables.bootstrap4.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/responsive.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/buttons.flash.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/dataTables.keyTable.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/datatables/dataTables.select.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/pdfmake/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/libs/pdfmake/vfs_fonts.js')); ?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo e(asset('assets1/libs/flatpickr/flatpickr.min.js')); ?>"></script>

<!-- <script src="<?php echo e(asset('assets1/js/pages/datatables.init.js')); ?>"></script> -->

<!-- App js-->
<script src="<?php echo e(asset('assets1/js/app.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets1/js/dc_script.js')); ?>"></script>

  <script src="<?php echo e(asset('js/select2.full.min.js')); ?>"></script>
<script src="https://media.twiliocdn.com/sdk/js/chat/v3.3/twilio-chat.min.js" ></script>
      <!--   <script src="https://media.twiliocdn.com/sdk/js/chat/v3.3/twilio-chat.min.js"  async defer></script> -->
<script>

    // $('#departure_from').select2({
    //     placeholder: 'Dep. From',
    //     ajax: {
    //         url: "/departure_from",
    //         dataType: 'json',
    //         delay: 250,
    //         processResults: function (data) {
    //             return {
    //                 results: $.map(data, function (item) {
    //                     return {
    //                         text: item.from,
    //                         id: item.from
    //                     }
    //                 })
    //             };
    //         },
    //         cache: true
    //     }
    // });
    // $('#departure_to').select2({
    //     placeholder: 'Dep. To',
    //     ajax: {
    //         url: "/departure_to",
    //         dataType: 'json',
    //         delay: 250,
    //         processResults: function (data) {
    //             return {
    //                 results: $.map(data, function (item) {
    //                     return {
    //                         text: item.ending_at,
    //                         id: item.ending_at
    //                     }
    //                 })
    //             };
    //         },
    //         cache: true
    //     }
    // });
</script>
<script>
    $(document).ready(function () {

      if($('#departureTable').length>0){
        $('#departureTable').DataTable({
            "oLanguage": {
                "sSearch": '',
                "sSearchPlaceholder": " Search...",
                "sLengthMenu": "",
            },
            "stripeClasses": [],

            "pageLength": 10,
            "language": {paginate: {previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"}},
            drawCallback: function () {$(".dataTables_paginate > .pagination").addClass("pagination-rounded")},
            drawCallback: function () {$("#departureTable_filter > label").addClass("d-block")},
            "bLengthChange": false,
            drawCallback: function () {$("#departureTable_filter > label> .form-control").removeClass("form-control-sm")},
            "bInfo": false,
        });
      }
        if($('.bootstrap-datetimepicker-widget').length>0){
        $('.bootstrap-datetimepicker-widget').addClass('d-block');
      }



    });


$(document).ready(function(){
 setInterval(fetchdata,5000);
});

function fetchdata(){
  console.log('<?php echo request()->route()->getName() ?>');
  <?php if(request()->route()->getName()!='chat_room' && request()->route()->getName()!='chat_room_details'){ ?>

    if(localStorage.getItem('cht_pn_id')==null){
    var token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
          url: '/get_cht_cloud_set',
          type: 'post',
          data: { 
              "_token": token,
          },
          success: function(response){ 
           //console.log(response.data.from_user_name);
           if(response && response.new_message==true){
            var chat_data=response.data;
            var a_msg = '<a href="<?php echo url('chat_room') ?>/'+chat_data.id+'" target="_blank">You have new chat message from '+chat_data.from_user_name+'</a>';
            var message= '<div class="alert animated flipInX alert-success alert-dismissible"><strong><i class="far fa-comment-dots mr-1"></i>New Message Alert!  </strong><p>'+a_msg+'</p><span class="close" data-dismiss="alert"><i class="fa fa-times-circle"></i></span></div>';
            console.log(message); 
            $("#pageMessages").html(message);
           }else{
             $("#pageMessages").html('');
           }
          }
    });
  }
  <?php } ?>
    
}

// pullit Js

  const searchCountry = document.getElementById('search_country');
  const searchDestination = document.getElementById('search_destination');
  const searchPoi = document.getElementById('search_poi');
  const hideResultArea = document.querySelectorAll('.search_result_area');
  const pullitSearch = document.getElementById('pullit-search');
  const searchKey = document.getElementById('searchKey');
  const resultSearchKey = document.getElementById('resultSearchKey');
  const resultSearch = document.querySelector('#resultSearchKey ul');
  const resultSearchValue = document.querySelectorAll('#resultSearchKey ul li');
  function pullitShow(){
    pullitSearch.classList.add('show');
    document.querySelector('body').classList.add('open_dc_model');
    $('#pullItResponse').html('');
  }
  function pullitHide(){
    pullitSearch.classList.remove('show');
    document.querySelector('body').classList.remove('open_dc_model');
    $('#pullItResponse').html('');
    $('#searchKey').val('');
  }
  var pulItUrl = env('pullIt_BaseUrl')."/api/";
  var pullItImageUrl="https://pullit-bucket.s3.us-west-2.amazonaws.com/flag/"
  function findKey(){
    var text = searchKey.value.toLowerCase();
      if(text.length > 2){
        $('.loaderPullIt').css("display","block");
        $.ajax({
          type:"get",
          url: pulItUrl+'search-pullit?keyword='+text,
          success: function(data) {
            console.log(data); 
            var data_found=false;
            var pullItList = '<ul class="list-unstyled m-0">';

            if(data && data.country.length>0){
              data_found=true;
              for(let count of data.country){
                pullItList+='<li><a href="javascript:void(0);" onclick="countryData('+count.id+','+`'${count.country_name}'`+');"><picture class="country-img"><img src="'+pullItImageUrl+count.flag+'" alt="flag" class="img-fluid"></picture><span style="color: #80254a;">'+count.country_name+'</span></a></li>';
              }
            } 

            if(data && data.destination.length>0){
              data_found=true;
              for(let dest of data.destination){
                pullItList+='<li><a href="javascript:void(0);" onclick="destinationData('+dest.id+','+`'${dest.dest_name}'`+');"><picture class="country-img"><i class="fa fa-map"></i></picture><span style="color: #80254a;">'+dest.dest_name+', '+dest.region+', '+dest.country_name+'</span></a></li>';
              }
            } 

            if(data && data.poi.length>0){
              data_found=true;
              for(let pois of data.poi){
                pullItList+='<li><a href="javascript:void(0);" onclick="poiData('+pois.id+','+`'${pois.attraction_name}'`+');"><picture class="country-img"><i class="fa fa-map-pin"></i></picture><span style="color: #80254a;">'+pois.attraction_name+'</span></a></li>';
              }
            }
            pullItList+='</ul>';
            if(data_found){
              $('.loaderPullIt').css("display","none");
               resultSearchKey.style.display = 'block';
               $("#resultSearchKey").html(pullItList);
            }else{
              $('.loaderPullIt').css("display","none");
               resultSearchKey.style.display = 'none';
               $("#resultSearchKey").html('');
            }
          }
        });
     }else{
          resultSearchKey.style.display = 'none';
          $("#resultSearchKey").html('');
     }  

   
    // if( (new RegExp( '\\b' + myData.join('\\b|\\b').toLowerCase() + '\\b') ).test(searchKey.value.toLowerCase()) ) {
    //   resultSearch.style.display = 'block';
    // } else {
    //   resultSearch.style.display = 'none';
    // }
  }
  const myData = [ 'india', 'uzbekistan', 'New delhi', 'Mumbai', 'Red Fort', 'india gate' ];
  function searchArea(e,event,type){
    resultSearchValue.forEach(el =>{
      el.classList.remove('selected');
    });
    for (i=0; i<myData.length; i++) {
      
      if(e.toLowerCase() == myData[i].toLocaleLowerCase()){
        event.parentNode.classList.add('selected');
        resultSearch.style.display = 'none';
      }
    }
    if(type == 'country'){
      hideAll();
      searchCountry.style.display = 'block';
    }
    if(type == 'destination'){
      hideAll();
      searchDestination.style.display = 'block';
    }
    if(type == 'poi'){
      hideAll();
      searchPoi.style.display = 'block';
    }
  }

  const hideAll = () => {
    searchCountry.style.display = 'none';
    searchDestination.style.display = 'none';
    searchPoi.style.display = 'none';
  }

  function countryData(id,name){
    if(id){
      $('.loaderPullIt').css("display","block");
      resultSearchKey.style.display = 'none';
      $('#searchKey').val(name);
      $.ajax({
        type:"get",
        url: pulItUrl+'pullcountry?country_id='+id,
        success: function(data) {
          var res = JSON.stringify(data);
          //console.log(res);
          $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"post",
            url: "<?php echo e(route('pullIt_country_data')); ?>",
            data: {'countries':res},
            success: function(datas) {
              console.log(datas);
              $('.loaderPullIt').css("display","none");
              $('#pullItResponse').html(datas);
              //console.log(datas);
            }
          }) 
        }
      })
    }
  }
  // Destinations
  function destinationData(id,name){
    if(id){
      $('.loaderPullIt').css("display","block");
      resultSearchKey.style.display = 'none';
      $('#searchKey').val(name);
      $.ajax({
        type:"get",
        url: pulItUrl+'pulldestination?dest_id='+id,
        success: function(data) {
          var res = JSON.stringify(data);
          console.log(res);
          $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"post",
            url: "<?php echo e(route('pullIt_destination_data')); ?>",
            data: {'destinations':res},
            success: function(datas) {
              console.log(datas);
              $('.loaderPullIt').css("display","none");
              $('#pullItResponse').html(datas);
              //console.log(datas);
            }
          }) 
        }
      })
    }
  }

  // Pois
  function poiData(id,name){
    if(id){
      $('.loaderPullIt').css("display","block");
      resultSearchKey.style.display = 'none';
      $('#searchKey').val(name);
      $.ajax({
        type:"get",
        url: pulItUrl+'pullpoi?poi_id='+id,
        success: function(data) {
          var res = JSON.stringify(data);
          console.log(res);
          $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"post",
            url: "<?php echo e(route('pullIt_poi_data')); ?>",
            data: {'pois':res},
            success: function(datas) {
              console.log(datas);
              $('.loaderPullIt').css("display","none");
              $('#pullItResponse').html(datas);
              //console.log(datas);
            }
          }) 
        }
      })
    }
  }
</script>

<style type="text/css">
    #pageMessages {
  position: fixed;
  bottom: 15px;
  right: 15px;
  width: 30%;
  z-index: 1;
}
 /* Absolute Center Spinner */
.loading_body {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: visible;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading_body:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.3);
}

/* :not(:required) hides these rules from IE9 and below */
.loading_body:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading_body:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 1500ms infinite linear;
  -moz-animation: spinner 1500ms infinite linear;
  -ms-animation: spinner 1500ms infinite linear;
  -o-animation: spinner 1500ms infinite linear;
  animation: spinner 1500ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
  box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes  spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>

    <script>
        // Your web app's Firebase configuration
        var firebaseConfig = {
            apiKey: "AIzaSyDbtoFdptDcoCAnl_olegwYVWhMG18306Y",
            authDomain: "departure-cloud.firebaseapp.com",
            projectId: "departure-cloud",
            storageBucket: "departure-cloud.appspot.com",
            messagingSenderId: "756784928660",
            appId: "1:756784928660:web:57832797304a80a26be181",
            measurementId: "G-EDRWXNESG6"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            
            messaging.requestPermission().then(function () {
                console.log(messaging.getToken());
                return messaging.getToken()
            }).then(function(token) {
                
                axios.post("<?php echo e(route('fcmUpdate')); ?>",{
                    _method:"PATCH",
                    token
                }).then(({data})=>{
                    console.log(data)
                }).catch(({response:{data}})=>{
                    console.error(data)
                })

            }).catch(function (err) {
                console.log(`Token Error :: ${err}`);
            });
        }

        initFirebaseMessagingRegistration();
      
        messaging.onMessage(function({data:{body,title,icon,click_action}}){
          const noteTitle = title;
          const noteOptions = {
              body: body,
              icon: icon,
          };
          const notif = new Notification(noteTitle, noteOptions);

          //const notif =  new Notification(title, {body,icon}, {click_action});
            notif.onclick = function() {
               // window.location=click_action;
                window.open(click_action,'_blank');
            };
        });
    </script>
<?php $__env->startSection('footerSection'); ?>
<?php echo $__env->yieldSection(); ?><?php /**PATH /var/www/departure/resources/views/public_layouts/footer.blade.php ENDPATH**/ ?>