@extends('layouts.app')
@section('tagSection')
    <title>Add Pricing | Departure Cloud</title>
@endsection
<style type="text/css">
    .modal-dialog{max-width:800px;width:600px;margin:0 auto}.modal{left:unset;padding-right:0!important}.modal.fade .modal-dialog{-webkit-transform:translate(25%,0);transform:translate(25%,0)}.modal.show .modal-dialog{-webkit-transform:translate(0,0);transform:translate(0,0)}.modal-backdrop.show{opacity:.7}.dep-model-action-btn a{padding:1px 2px;display:inline-block;position:relative}.dep-model-action-btn a:hover:after{display:-webkit-flex;display:flex;-webkit-justify-content:center;justify-content:center;background:#444;border-radius:4px;color:#fff;content:attr(title);font-size:13px;padding:4px 6px;position:absolute;bottom:28px;top:auto;z-index:99}.dep-model-action-btn a:first-child:hover:after{width:108px;left:-42px}.dep-model-action-btn a:hover:after{width:85px;left:-40px}.dep-model-action-btn a:hover:before{border:solid;border-color:#444 transparent;border-width:8px 4px 0 4px;content:"";left:6px;top:-6px;position:absolute;z-index:99}.form-control:disabled,.form-control[readonly]{background-color:#fff}.hold,form,.form-group,label{line-height:1.2} 
        #notificationDropdownAlert{
            padding: 28 15px !important;
        }   
</style>
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Pricing (Per PAX)</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Pricing</li>
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
                        
                        @if(!empty($first_data->currency_symbol) && !empty($first_data->currency_code))
                            <form id="changePricingAll">
                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Change Currency<span class="text-danger">*</span></label>
                                        <select class="form-control input-group-text" name="currency_change" id="currency_change" style="background-color: #e9ecef;" required>
                                            @foreach($currency as $row)
                                                <option value="{{$row->currency_code}},{{$row->currency_symbol}}" @if($row->currency_symbol == $first_data->currency_symbol) selected @endif>{{$row->currency_symbol}} ({{$row->currency_code}})</option>
                                            @endforeach
                                        </select>
                                        <p id="currency_change_error" class="text-danger"></p>
                                    </div>
                                </div>
                            </div>
                            </form>
                        @endif
                        <form id="InclusionForm">
                            @csrf
                            @if(empty($first_data->currency_symbol) && empty($first_data->currency_code))
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Currency<span class="text-danger">*</span></label>
                                            <select class="form-control input-group-text" name="currency" id="currency" style="background-color: #e9ecef;" required>
                                                <option value="">Select</option>
                                                @foreach($currency as $row)
                                                    <option value="{{$row->currency_code}},{{$row->currency_symbol}}" @if($row->currency_symbol == Auth::user()->currency_symbol) selected @endif>{{$row->currency_symbol}} ({{$row->currency_code}})</option>
                                                @endforeach
                                            </select>
                                            <p id="currency_error" class="text-danger"></p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" id="currency" name="currency" value="{{$first_data->currency_code}},{{$first_data->currency_symbol}}">
                            @endif

                            <div class="row wrappers">
                                <input type="hidden" name="departure_type" value="{{$departure->departure_type}}">
                                @if(in_array(32, json_decode($columns)))
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Room Sharing<!-- <span class="text-danger">*</span> --></label>
                                            <select class="form-control" name="sharing" id="sairing_validateshhj">
                                                <option value="">Room Sharing</option>
                                                @foreach($sairing as $row)
                                                    <option value="{{$row->sairing}}" @if($row->sairing == 'Double') selected @endif>{{$row->sairing}}</option>
                                                @endforeach
                                            </select>
                                            <span id="sairing_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                @endif
                                @if(in_array(33, json_decode($columns)))
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Flight Class<!-- <span class="text-danger">*</span> --></label>
                                            <select class="form-control" name="flight_class" id="flight_class">
                                                <option value="">Select Flight Class</option>
                                                @foreach($flight_classes as $flight_classe)
                                                    <option value="{{$flight_classe->name}}" @if($flight_classe->name == 'Economy Class') selected @endif>{{$flight_classe->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Age Bracket</label>
                                            <select class="form-control" name="passenger" id="passenger">
                                                <!-- <option value="">Age Bracket</option> -->
                                                @foreach($passenger_types as $passenger_type)
                                                    <option value="{{$passenger_type->name}}">{{$passenger_type->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if(in_array(35, json_decode($columns)))
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Hotel Name</label>
                                            <select class="form-control  hotel" name="hotel_name" id="hotel_name">
                                                @if(count($dep_hotel_name)<=0)
                                                    <option value="">Hotel Name</option>
                                                @else
                                                @foreach($dep_hotel_name as $hName)
                                                <option value="{{$hName->name}}">
                                                    {{$hName->name}} </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Hotel Category</label>
                                            <select class="form-control  hotel" name="hotel" id="hotel">
                                                @if($hotel_categories == "")
                                                    <option value="">Hotel Category</option>
                                                @else
                                                    <option value="{{$hotel_categories}}">
                                                    {{$hotel_categories}} </option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    
                                @endif
                                @if(in_array(36, json_decode($columns)))
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Transport Type</label>
                                            <select class="form-control transport_type" name="transport_type" id="transport_type">
                                                <option value="">Select Transport</option>
                                                @foreach($transport_types as $transport_type)
                                                    <option value="{{$transport_type->code}}" @if(($departure->departure_type == 1 && $transport_type->code == 'SIC') || ($departure->departure_type == 2 && $transport_type->code == 'SIC')) selected @endif>{{$transport_type->name}} @if($transport_type->code != 'Private')
                                                            ({{$transport_type->code}})
                                                        @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Airport Transfers</label>
                                        <select class="form-control" name="airport_transfers" id="airport_transfers">
                                            @foreach($airport_transfers as $airport_transfer)
                                                <option value="{{$airport_transfer->code}}" @if($departure->departure_type == 5 && $airport_transfer->code == 'Not Included') selected @elseif($airport_transfer->code == 'SIC') selected
                                                        @elseif($departure->departure_type == 3 && $airport_transfer->code == 'Not Included')  selected @endif>{{$airport_transfer->name}} @if($airport_transfer->code != 'Private' && $airport_transfer->code != 'Not Included')
                                                        ({{$airport_transfer->code}})
                                                    @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(in_array(38, json_decode($columns)))
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Meal Plan</label>
                                            <select class="form-control meal_plan" name="meal_type" id="meal_type">
                                                <option value="">Select Meal</option>
                                                @foreach($meal_plans as $meal_plan)
                                                    <option value="{{$meal_plan->code}}" @if($meal_plan->code == $mealplan) selected @endif>{{$meal_plan->name}} ({{$meal_plan->code}})</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                @endif


                                <div class="col-md-2">
                                    <label for="">Minimum Pax</label>
                                    <input type="text" class="form-control" id="group_size_error" name="group_size" value="1">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Note</label>
                                        <input type="text" class="form-control" id="other" name="other">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Price<span class="text-danger">*</span></label>
                                        <div class="d-flex">
                                            <div class="input-group-prepend ">

                                            </div>
                                            <input type="text" class="form-control" id="price_validates" name="price">
                                        </div>
                                        <span class="text-danger" id="price_error_msg"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end align-items-start">
                                    <div>
                                        <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 36px; visibility: hidden;">
                                        <span class="text-success d-block" id="mesegese"></span>
                                    </div>
                                    <button class="btn btn-primary active mx-2" type="button" id="store_form"><i class="fa fa-save"></i> Add</button>
                                    <a href="{{route('terms_payment_create',request()->route('id'))}}" class="btn btn-primary active" type="button"><i class="fa fa-save"></i> Next</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if(count($data)> 0 )
                        <div class="card-box">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        @if(in_array(32, json_decode($columns)))
                                            <th>Room Sharing</th>
                                        @endif
                                        @if(in_array(33, json_decode($columns)))
                                            <th>Flight Class</th>
                                            <th>Passenger Type</th>
                                        @endif
                                        @if(in_array(35, json_decode($columns)))
                                            <th>Hotel Name</th>
                                            <th>Hotel Category</th>
                                        @endif
                                        @if(in_array(36, json_decode($columns)))
                                            <th>Transport Type</th>
                                        @endif
                                        <th>Airport Transfers</th>
                                        @if(in_array(38, json_decode($columns)))
                                            <th>Meal Plan</th>
                                        @endif
                                        <th>Minimum Pax</th>
                                        <th>Note</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $key=>$row)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            @if(in_array(32, json_decode($columns)))
                                                <td>{{$row->sharing}}</td>
                                            @endif
                                            @if(in_array(33, json_decode($columns)))
                                                <td>{{$row->flight_class}}</td>

                                                <td>{{$row->passenger}}</td>
                                            @endif
                                            @if(in_array(35, json_decode($columns)))
                                                <td>{{$row->hotel_name}}</td>
                                                <td>{{$row->hotel_type}}</td>
                                            @endif
                                            @if(in_array(36, json_decode($columns)))
                                                <td>{{$row->transport_type}}</td>
                                            @endif
                                            <td>{{$row->airport_transfers}}</td>
                                            @if(in_array(38, json_decode($columns)))
                                                <td>{{$row->meal_type}}</td>
                                            @endif
                                            <td>{{$row->group_size}}</td>
                                            <td>{{$row->other}}</td>
                                            <td>{{$row->currency_symbol}} {{$row->price}}</td>
                                            <td>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target=".bd-example-modal-sm{{$row->id}}" title="Edit"><i class="fa fa-edit"></i></a> |
                                                <form id="posts-form-{{ $row->id }}" method="post" action="{{route('penable_disable',$row->id)}}" style="display: none;">
                                                    @csrf

                                                    {{method_field('POST')}} <!-- posts query -->
                                                </form>
                                                @if($row->status == 0)
                                                    <a href="" onclick="
                                                if (confirm('Are you sure, You want to enable?'))
                                                  {
                                                    event.preventDefault();
                                                    document.getElementById('posts-form-{{ $row->id }}').submit();
                                                  }
                                                  else
                                                  {
                                                    event.preventDefault();
                                                  }
                                                " style="cursor: pointer;" title="Enable">
                                                        <i class="fas fa-trash"></i></a>
                                                @else
                                                    <a href="" onclick="
                                                if (confirm('Are you sure, You want to disable?'))
                                                  {
                                                    event.preventDefault();
                                                    document.getElementById('posts-form-{{ $row->id }}').submit();
                                                  }
                                                  else
                                                  {
                                                    event.preventDefault();
                                                  }
                                                " style="cursor: pointer;" title="Disable">
                                                        <i class="fas fa-upload"></i></a>
                                                @endif
                                                <form id="default-posts-form-{{ $row->id }}" method="post" action="{{route('default_price',$row->id)}}" style="display: none;">
                                                    @csrf

                                                    {{method_field('POST')}} <!-- posts query -->
                                                </form>
                                                @if($row->default_price == 0)
                                                <a href="" onclick="
                                                if (confirm('Are you sure, You want to select price default?'))
                                                  {
                                                    event.preventDefault();
                                                    document.getElementById('default-posts-form-{{ $row->id }}').submit();
                                                  }
                                                  else
                                                  {
                                                    event.preventDefault();
                                                  }
                                                " style="cursor: pointer;" title="Enable">
                                                    <i class="fas fa-square"></i></a>
                                                @else
                                                    <a href="" onclick="
                                                if (confirm('Are you sure, You want to unselect price default?'))
                                                  {
                                                    event.preventDefault();
                                                    document.getElementById('default-posts-form-{{ $row->id }}').submit();
                                                  }
                                                  else
                                                  {
                                                    event.preventDefault();
                                                  }
                                                " style="cursor: pointer;" title="Disable">
                                                    <i class="fas fa-check-square"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footerSection')

    <!-- <script type="text/javascript">
    $(document).ready(function(){
    var maxFields = 20; //Input fields increment limitation
    var addButtons = $('.add_button'); //Add button selector
    var wrappers = $('.wrappers'); //Input field wrapper
    var fieldHTMLs = '<div class="col-md-12" id="rowes"><div class="row"><div class="col-md-3" style=""><label>Sharing <label for="">Sharing<span class="text-danger">*</span><span id="msg" class="text-danger"></span></label><div class="form-group"><input name="sharing[]" id="sharing" class="form-control" type="text"></div></div><div class="col-md-3"><div class="form-group"><label for="">Transport Type</label><select class="form-control transport_type" name="transport_type[]" id="transport_type"><option value="">Select Transport Type</option><option value="SIC (Seat In Coach)">SIC (Seat In Coach)</option><option value="Private">Private</option></select></div></div><div class="col-md-3"><div class="form-group"><label for="">Hotel Type</label><select class="form-control  hotel" name="hotel[]" id="hotel"><option value="">Select Hotel Type</option>@foreach($hotel as $row)
        <option value="{{$row->hotel_category}}">{{$row->hotel_category}}</option>
    @endforeach</select></div></div><div class="col-md-3"><div class="form-group"><label for="">Meal Type</label><select class="form-control meal_plan" name="meal_type[]" id="meal_type" ><option value="">Select Meal Type</option><option value="American Meal Plan">American Meal Plan (AMP)</option><option value="Modified American Meal Plan">Modified American Meal Plan (MAMP)</option><option value="Continent Meal Plan">Continent Meal Plan (CMP)</option><option value="European Plan">European Plan (EP)</option></select></div></div><div class="col-md-3"><label>Group Size</label><div class="form-group"><input type="text" name="group_size[]" id="group_size" class="form-control group"></div></div><div class="col-md-3"><label>Other</label><div class="form-group"><input type="text" name="other[]" id="other" class="form-control"></div></div><div class="col-md-3 mr-5"><div class="form-group"><label for="">Price<span class="text-danger">*</span><span class="text-danger" id="price_error"></span></label><div class="d-flex"><div class="input-group-prepend "></div><input type="text" class="form-control price"  id="price" name="price[]" ></div></div></div><div class="col-md-2" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="btn btn-outline-danger remove_button">- Remove</a></div></div></div></div>';
    var x = 1;
    
    $(addButtons).click(function(){
        if(x < maxFields){ 
            x++;
            $(wrappers).append(fieldHTMLs);
            var price = document.querySelector('.price');

              price.addEventListener('input', restrictNumber);
              function restrictNumber (e) {  
                  var pricies = this.value.replace(new RegExp(/[^\d]/,'ig'), "");
                  this.value = pricies;
              }
              var group_size = document.querySelector('.group');
              group_size.addEventListener('input', restrictNumber);
              function restrictNumber (e) {  
                  var group = this.value.replace(new RegExp(/[^\d]/,'ig'), "");
                  this.value = group;
              }
        }
    });
    $(wrappers).on('click', '.remove_button', function(e){
        e.preventDefault();
         $("#rowes").last().remove();
        
        x--;
    });
  });
</script> -->

    <script>

        $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                // var currency = $('#currency').val();
                //    if (currency == "") {
                //        $("span#currency_error").html('This field is required!');
                //        $("select#currency").focus();
                //        return false;
                //    }else{
                //        $("span#currency_error").hide();
                //    }
                // var title = $('#sairing_validates').val();
                //    if (title == "") {
                //        $("span#sairing_error").html('This field is required!');
                //        $("select#sairing_validates").focus();
                //        return false;
                //    }else{
                //        $("span#sairing_error").hide();
                //    }
                var price = $('#price_validates').val();
                if (price == "") {
                    $("span#price_error_msg").html('This field is required!');
                    $("input#price_validate").focus();
                    return false;
                } else {
                    $("span#price_error_msg").hide();
                }

                $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('InclusionForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('pricing_store',request()->route('id')) }}",
                    data: $('#InclusionForm').serialize(),
                    success: function (data) {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        //window.location = data.url;
                        location.reload();
                        // window.location.href = "{{ route('pdf_itinerary',request()->route('id')) }}";
                    },
                    errors: function () {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                    }

                });
            });
        });
        var price = document.querySelector('#price_validates');
        price.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var pricies = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = pricies;
        }

        var group_size = document.querySelector('#group_size_error');
        group_size.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var group = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = group;
        }
    </script>

    @foreach($data as $key=>$value)
        <div class="modal fade bd-example-modal-sm{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content hold">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="mySmallModalLabel">Edit Pricing</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <form role="form" id="PricingForm_{{$key}}" style="background-color: #fdfdfd;" class="p-1">
                        @csrf
                        <input type="hidden" name="price_id" value="{{$value->id}}">
                        <input type="hidden" name="departure_type" value="{{$value->departure_type}}">
                        <div class="modal-body">
                            <div class="row">
                                @if(in_array(32, json_decode($columns)))
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Room Sharing<span id="sairing_error" class="text-danger">*</span></label>
                                            <select class="form-control" name="sharing" id="saring_validate_{{$key}}">
                                                <option value="">Select Sairing</option>
                                                @foreach($sairing as $row)
                                                    <option value="{{$row->sairing}}" @if($row->sairing == $value->sharing) selected @endif>{{$row->sairing}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="text-danger" id="sairing_error_{{$key}}"></span>
                                    </div>
                                @endif
                                @if(in_array(33, json_decode($columns)))
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Flight Class<!-- <span class="text-danger">*</span> --></label>
                                            <select class="form-control" name="flight_class" id="flight_class">
                                                <option value="">Flight Class</option>
                                                @foreach($flight_classes as $flight_class)
                                                    <option value="{{$flight_class->name}}" @if($flight_class->name == $value->flight_class) selected @endif>{{$flight_class->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Age Bracket</label>
                                            <select class="form-control" name="passenger" id="passenger">
                                                @foreach($passenger_types as $passenger_type)
                                                    <option value="{{$passenger_type->name}}" @if($passenger_type->name == $value->passenger) selected @endif>{{$passenger_type->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                @endif
                                @if(in_array(35, json_decode($columns)))
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Hotel Name</label>
                                            <select class="form-control  hotel_name" name="hotel_name" id="hotel_name">
                                                <option value="">Hotel Name</option>
                                                @foreach($dep_hotel_name as $dh_name)
                                                    <option value="{{$dh_name->name}} @if($value->hotel_name == $dh_name->name) selected @endif" selected>
                                                    {{$dh_name->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Hotel Category</label>
                                            <select class="form-control  hotel" name="hotel" id="hotel">
                                                <option value="">Hotel Category</option>
                                                <option value="{{$hotel_categories}}" selected>
                                                    {{$hotel_categories}}</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if(in_array(36, json_decode($columns)))
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Transport Type</label>
                                            <select class="form-control transport_type" name="transport_type" id="transport_type">
                                                <option value="">Select Transport</option>
                                                @foreach($transport_types as $transport_type)
                                                    <option value="{{$transport_type->code}}" @if($transport_type->code == $value->transport_type) selected @endif>{{$transport_type->name}} @if($transport_type->code != 'Private')
                                                            ({{$transport_type->code}})
                                                        @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Airport Transfers</label>
                                        <select class="form-control" name="airport_transfers" id="airport_transfer">
                                            @foreach($airport_transfers as $airport_transfer)
                                                <option value="{{$airport_transfer->code}}" @if($airport_transfer->code == $value->airport_transfers) selected @endif>{{$airport_transfer->name}} @if($airport_transfer->code != 'Private' && $airport_transfer->code != 'Not Included')
                                                        ({{$airport_transfer->code}})
                                                    @endif</option>
                                            @endforeach
                                        </select>
                                        <span id="sairing_error" class="text-danger"></span>
                                    </div>
                                </div>
                                @if(in_array(38, json_decode($columns)))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Meal Plan</label>
                                        <select class="form-control meal_plan" name="meal_type" id="meal_type">
                                            <option value="">Select Meal</option>
                                            @foreach($meal_plans as $meal_plan)
                                                <option value="{{$meal_plan->code}}" @if($meal_plan->code == $value->meal_type) selected @endif>{{$meal_plan->name}} @if($meal_plan->code != '')
                                                        ({{$meal_plan->code}})
                                                    @endif</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-4">
                                    <label for="">Minimum Pax</label>
                                    <input type="text" class="form-control" id="group_size_{{$key}}" name="group_size" value="{{$value->group_size}}">
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">Note</label>
                                        <input type="text" class="form-control" id="other" name="other" value="{{$value->other}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Price ({{$value->currency_symbol}})<span class="text-danger">*</span></label>
                                        <div class="d-flex">
                                            <div class="input-group-prepend ">
                                            </div>
                                            <input type="text" class="form-control" id="price_validate_{{$key}}" name="price" value="{{$value->price}}">
                                        </div>
                                    </div>
                                    <span class="text-danger" id="price_error_msg_{{$key}}"></span>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button class="btn btn-primary active" type="button" id="store_form_{{$key}}" style="margin-bottom:16px;"><i class="fa fa-save"></i> Update</button>
                                    <img src="{{ asset('images/loader.gif') }}" id="gif_{{$key}}" style="width: 30px; visibility: hidden;margin-bottom:16px;">
                                    <span class="text-success" id="mesegese_{{$key}}" style="margin-left: 10px;margin-bottom:16px;"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $('#store_form_{{$key}}').click(function (e) {
                    e.preventDefault();
                    $('#gif_{{$key}}').show();
                    // var title_{{$key}} = $('#saring_validate_{{$key}}').val();
                    //    if (title_{{$key}} == "") {
                    //        $("span#sairing_error_{{$key}}").html('This field is required!');
                    //        $("select#saring_validate_{{$key}}").focus();
                    //        return false;
                    //    }else{
                    //        $("span#sairing_error_{{$key}}").hide();
                    //    }
                    var price_{{$key}} = $('#price_validate_{{$key}}').val();
                    if (price_{{$key}} == "") {
                        $("span#price_error_msg_{{$key}}").html('This field is required!');
                        $("input#price_validate_{{$key}}").focus();
                        return false;
                    } else {
                        $("span#price_error_msg_{{$key}}").hide();
                    }

                    $('#gif_{{$key}}').css('visibility', 'visible');
                    var formData = new FormData(document.getElementById('PricingForm_{{$key}}'));
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: "{{ route('pricing_updating',request()->route('id')) }}",
                        data: $('#PricingForm_{{$key}}').serialize(),
                        success: function (data) {
                            $('#gif_{{$key}}').hide();
                            $('#mesegese_{{$key}}').html("<span class='sussecmsg'>Success!</span>");
                            //window.location = data.url;
                            location.reload();
                            // window.location.href = "{{ route('pdf_itinerary',request()->route('id')) }}";
                        },
                        errors: function () {
                            $('#gif_{{$key}}').hide();
                            $('#mesegese_{{$key}}').html("<span class='sussecmsg'>Something went wrong!</span>");
                        }

                    });
                });
            });
            var price = document.querySelector('#price_validate_{{$key}}');
            price.addEventListener('input', restrictNumber);

            function restrictNumber(e) {
                var pricies = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                this.value = pricies;
            }

            var group_size = document.querySelector('#group_size_{{$key}}');
            group_size.addEventListener('input', restrictNumber);

            function restrictNumber(e) {
                var group = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                this.value = group;
            }
        </script>
    @endforeach
    <script type="text/javascript">
        $("#currency_change").on('change', function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{ route('change_pricing_all',request()->route('id')) }}",
                data: $('#changePricingAll').serialize(),
                success: function (data) {
                    window.location.reload();
                },
                errors: function () {
                    $('#mesegese_all').html("<span class='sussecmsg'>Something went wrong!</span>");
                }

            });
        });
    </script>
    
@endsection