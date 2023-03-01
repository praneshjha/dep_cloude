@extends('layouts.app')
@section('tagSection')
<title>Departure Cloud | Mailer Send</title>
@endsection
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('css/timepicker.css')}}">
@section('content')
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Mailer Upload</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Mailer</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <form class="app-search" method="get" action="{{route('index_mailer')}}">
                        <div class="app-search-box">
                            <div class="input-group">
                                <select class="form-control" name="month">
                                    <option value="">Select Month</option>
                                    @foreach($months as $month)
                                        <option value="{{$month->month}}" @if($monthN == $month->month) selected @endif>{{$month->month}}</option>
                                    @endforeach
                                </select>
                                <select class="form-control" name="year" style="margin-left: 5px;">
                                    <option value="">Select Year</option>
                                    @foreach($years as $year)
                                        <option value="{{$year->year}}" @if($yearN == $year->year) selected @endif>{{$year->year}}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control" placeholder="Search..." value="{{$keyword}}" name="keyword" style="margin-left: 5px;">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fe-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box formCard">
                        <form role="form" id="mailerForm">
                            @csrf
                            <div class="row mt-2">
                                <div class="col-md-5 col-lg-5 col-sm-5 col-xs-12">
                                    <div class="form-group mb-3">
                                        <label for="example-fileinput">Select Html File</label><br>
                                        <input type="file" id="html_file" name="html_file" class="form-control-file">
                                    </div>
                                </div>
                                <div class="col-md-5 col-lg-5 col-sm-5 col-xs-12">
                                    <div class="form-group mb-3">
                                        <label for="example-fileinput">Select Image</label><br>
                                        <input type="file" id="image_file" name="image_file[]" class="form-control-file" multiple>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex justify-content-end">
                                    <div class="mr-2">
                                        <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Upload</button>
                                        <span class="text-success d-block" id="mesegese" style="margin-left: 10px"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 style="color: #681f4a">Mailers List</h4>
                    @include('mailer/mailer_list')
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        .navbar-custom .topnav-menu .nav-link {
            padding: 30px 15px;
        }
    </style>
@endsection
@section('footerSection')  
    <script type="text/javascript">

        jQuery('#store_form').click(function (e) {
            e.preventDefault();
            jQuery('#store_form').html('Please wait...')
            jQuery('#store_form').prop('disabled', true);
            var formDatas = new FormData(document.getElementById('mailerForm'));
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{route('mailer_store')}}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function (data) {
                   $('#store_form_save').prop('disabled', true);
                    if(data.msg){
                        $('#mesegese').html("<span class='sussecmsg'>"+data.msg+"</span>");
                    }else{
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        window.location.reload();
                    }
                },
                errors: function () {
                    jQuery('.gif1').hide();
                    jQuery('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                }

            });
        });
    </script>           
@endsection