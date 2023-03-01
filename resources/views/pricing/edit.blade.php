@extends('layouts.app')
@section('tagSection')
    <title>Edit Pricing | Departure Cloud</title>
@endsection
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css"> -->
<link rel="stylesheet" href="{{asset('css/timepicker.css')}}">
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Pricing</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Edit Pricing (Per PAX)</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box formCard">
                        <h4 class="mt-0 depnameHeading">{{$departure->title}}</h4>
                        @include('layouts/itinerary_menu')
                        <form id="InclusionForm">
                            <div class="mr-5" style="position: absolute; top: 0px;right: 0;">
                                <div class="floating-label">
                                    <a href="javascript:void(0);" class="add_button btn btn-outline-primary formlabelmargin float-right" title="Add field"><i class="fas fa-plus"></i> Add More</a>
                                </div>
                            </div>
                            @csrf
                            <div class="col-md-1">
                                <div class="form-group">
                                    <div class="d-flex">
                                        <label for="">Currency<span class="text-danger">*</span><span class="text-danger" id="price_error"></span></label>
                                        <div class="input-group-prepend ">
                                            <select class="form-control input-group-text" name="currency" id="" style="background-color: #e9ecef;" required>
                                                <option value="">Select Currency</option>
                                                @foreach($currency as $row)
                                                    <option value="{{$row->currency_code}},{{$row->currency_symbol}}" @if($row->currency_symbol == Auth::user()->currency_symbol) selected @endif>{{$row->currency_symbol}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row wrappers">
                                @foreach($price as $value)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Sharing<span class="text-danger">*</span>
                                                <span id="msg" class="text-danger"></span></label>
                                            <input type="text" class="form-control" name="sharing[]" id="sharing" value="{{$value->sharing}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Transport Type</label>
                                            <select class="form-control transport_type" name="transport_type[]" id="transport_type">
                                                <option value="">Select Transport Type</option>
                                                <option value="SIC (Seat In Coach)" @if($value->transport_type =='SIC (Seat In Coach)') selected @endif>SIC (Seat In Coach)</option>
                                                <option value="Private" @if($value->transport_type =='Private') selected @endif>Private</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Hotel Type</label>
                                            <select class="form-control  hotel" name="hotel[]" id="hotel">
                                                <option value="">Select Hotel Type</option>
                                                @foreach($hotel as $row)
                                                    <option value="{{$row->hotel_category}}" @if($row->id == $value->hotel_type) selected @endif>{{$row->hotel_category}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Meal Type</label>
                                            <select class="form-control meal_plan" name="meal_type[]" id="meal_type">
                                                <option value="">Select Meal Type</option>
                                                <option value="American Meal Plan" @if($value->meal_type =='American Plan') selected @endif>American Plan (AP)</option>
                                                <option value="Modified American Plan" @if($value->meal_type =='Modified American Plan') selected @endif>Modified American Plan (MAP)</option>
                                                <option value="Continent Plan" @if($value->meal_type =='Continent Plan') selected @endif>Continent Plan (CP)</option>
                                                <option value="European Plan" @if($value->meal_type =='European Plan') selected @endif>European Plan (EP)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Group Size</label>
                                        <input type="text" class="form-control" id="group_size" name="group_size[]" value="{{$value->group_size}}">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Note</label>
                                            <input type="text" class="form-control" id="other" name="other[]" value="{{$value->other}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mr-5">
                                        <div class="form-group">
                                            <label for="">Price<span class="text-danger">*</span><span class="text-danger" id="price_error"></span></label>
                                            <div class="d-flex">
                                                <div class="input-group-prepend ">

                                                </div>
                                                <input type="text" class="form-control" id="price" name="price[]" value="{{$value->price}}">
                                            </div>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                                <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style type="text/css">
    #notificationDropdownAlert{
            padding: 28 15px !important;
        } 
</style>
@endsection
@section('footerSection')
    <script type="text/javascript">
        $(document).ready(function () {
            var maxFields = 20; //Input fields increment limitation
            var addButtons = $('.add_button'); //Add button selector
            var wrappers = $('.wrappers'); //Input field wrapper
            var fieldHTMLs = '<div class="col-md-12" id="rowes"><div class="row "><div class="col-md-3" style=""><label>Sharing <label for="">Sharing<span class="text-danger">*</span><span id="msg" class="text-danger"></span></label><div class="form-group"><input name="sharing[]" id="sharing" class="form-control" type="text"></div></div><div class="col-md-3"><div class="form-group"><label for="">Transport Type</label><select class="form-control transport_type" name="transport_type[]" id="transport_type"><option value="">Select Transport Type</option><option value="SIC (Seat In Coach)">SIC (Seat In Coach)</option><option value="Private">Private</option></select></div></div><div class="col-md-3"><div class="form-group"><label for="">Hotel Type</label><select class="form-control  hotel" name="hotel[]" id="hotel"><option value="">Select Hotel Type</option>@foreach($hotel as $row)<option value="{{$row->hotel_category}}">{{$row->hotel_category}}</option>@endforeach</select></div></div><div class="col-md-3"><div class="form-group"><label for="">Meal Type</label><select class="form-control meal_plan" name="meal_type[]" id="meal_type" ><option value="">Select Meal Type</option><option value="American Plan">American Plan (AP)</option><option value="Modified American Plan">Modified American Plan (MAP)</option><option value="Continent Plan">Continent Plan (CP)</option><option value="European Plan">European Plan (EP)</option></select></div></div><div class="col-md-3"><label>Group Size</label><div class="form-group"><input type="text" name="group_size[]" id="group_size" class="form-control group"></div></div><div class="col-md-3"><label>Other</label><div class="form-group"><input type="text" name="other[]" id="other" class="form-control"></div></div><div class="col-md-3 mr-5"><div class="form-group"><label for="">Price<span class="text-danger">*</span><span class="text-danger" id="price_error"></span></label><div class="d-flex"><div class="input-group-prepend "></div><input type="text" class="form-control price"  id="price" name="price[]"></div></div></div><div class="col-md-2" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="btn btn-outline-danger remove_button">- Remove</a></div></div></div></div>';
            var x = 1;

            $(addButtons).click(function () {
                if (x < maxFields) {
                    x++;
                    $(wrappers).append(fieldHTMLs);
                    price.addEventListener('input', restrictNumber);

                    function restrictNumber(e) {
                        var pricies = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                        this.value = pricies;
                    }

                    var group_size = document.querySelector('.group');
                    group_size.addEventListener('input', restrictNumber);

                    function restrictNumber(e) {
                        var group = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
                        this.value = group;
                    }
                }
            });
            $(wrappers).on('click', '.remove_button', function (e) {
                e.preventDefault();
                $("#rowes").last().remove();

                x--;
            });
        });
    </script>
    <script>
        $('#currency').select2({
            placeholder: 'Select Currency',
            ajax: {
                url: "/select_currency",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.country_name,
                                id: item.country_name
                            }
                        })
                    };
                },
                cache: true
            }
        });

        var price = document.querySelector('#price');
        price.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var pricies = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = pricies;
        }

        var group_size = document.querySelector('#group_size');
        group_size.addEventListener('input', restrictNumber);

        function restrictNumber(e) {
            var group = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
            this.value = group;
        }
    </script>
    <script>

        $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var title = $('#sharing').val();
                if (title == "") {
                    $("span#msg").html('This field is required!');
                    $("input#sharing").focus();
                    return false;
                } else {
                    $("span#msg").hide();
                }
                var price = $('#price').val();
                if (price == "") {
                    $("span#price_error").html('This field is required!');
                    $("input#price").focus();
                    return false;
                } else {
                    $("span#price_error").hide();
                }

                $('#gif').css('visibility', 'visible');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('pricing_updating',request()->route('id')) }}",
                    data: $('#InclusionForm').serialize(),
                    success: function (data) {
                        console.log(data.url);
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        window.location = data.url;
                        //location.reload();
                        //window.location.href = "{{ route('pdf_itinerary',request()->route('id')) }}";
                    },
                    errors: function () {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                    }

                });
            });
        });

    </script>
@endsection