@extends('layouts.app')
@section('tagSection')
<title>Itinerary PDF | Departure Cloud</title>
@endsection
<style type="text/css">
        .LastUpdated {margin: 5px 0;font-weight: 500;font-family: "Cerebri Sans,sans-serif";color: #681f4a;}.someThing p{position: relative;font-size: 16px;font-weight: 700;color: #898989;background-color: #fbfdff;box-shadow: 0 3px 4px 0 rgb(22 70 147 / 70%);padding: 4px 12px;margin-bottom: 22px;}.someThing p:not(:last-child):before{content: '';position: absolute;width: 1px;height: 16px;background-color: #681f4a;left: 50%;transform: translateX(-50%);top: 100%;}.someThing p:not(:last-child):after{content: '';position: absolute;display: block;width: 8px;height: 8px;border: 1px solid #681f4a;border-left: 0;border-top: 0;left: 50%;transform: translateX(-50%) rotate(45deg);top: calc(100% + 9px);}
    </style>
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                       {{-- @if($departure->departure_type == 4 || $departure->departure_type == 5 || $departure->departure_type == 3)
                        <h4 class="page-title">Itinerary</h4>
                        @else --}}
                        <h4 class="page-title">Add Itinerary</h4>
                        {{-- @endif --}}
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Itinerary</li>
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

                        {{-- @if($departure->departure_type == 4 || $departure->departure_type == 5 || $departure->departure_type == 3)
                        <div class="row autoItineraryGenerate mt-4">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="someThing text-center">
                                    @foreach($default_iti0 as $odefault_iti)
                                        <p>{{$odefault_iti->departure_itinerary}}</p>
                                        <p>{{$odefault_iti->arrival_itinerary}}</p>
                                    @endforeach
                                    @foreach($default_iti1 as $rdefault_iti)
                                        <p>{{$rdefault_iti->departure_itinerary}}</p>
                                        <p>{{$rdefault_iti->arrival_itinerary}}</p>
                                    @endforeach
                                    @foreach($default_iti2 as $hotel_def)
                                        <p>{{$hotel_def->checkin}}</p>
                                        <p>{{$hotel_def->checkout}}</p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        @else --}}
                        <form role="form" id="AgentItineraryForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row d-flex align-items-center">
                                <div class="col-md-5 m-2">
                                   <!--  <div class="from-group choosebannerselect mr-3"> -->
                                        <label for="img_file">Choose itinerary PDF, JPG, PNG</label><span class="validationError" id="image_error"><br></span>
                                        <input type="file" class="banner-img" id="pdf_name" name="pdf_name" accept="application/pdf, image/jpeg,image/png, image/jpg">
                                        <p>
                                        <span class="validationError" id="size_error"></span></p>
                                        <input type="hidden" id="fileSize">
                                    <!-- </div> -->
                                    <h4 class="mt-2">or</h4>
                                    <div class="form-group">
                                        <label for="">Itinerary Link</label>
                                        <input type="url" class="form-control" name="title" id="title">
                                    </div>
                                    <span class="validationError" id="title_error"></span>
                                </div>
                                <!-- <div class="col-md-6 ml-auto border-left">
                                    <div class="itineraryPreview">
                                        <div class="d-flex align-items-center">
                                            <p>No Preview</p>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-12 m-2">
                                    <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                                    <button class="btn btn-primary active" type="button" id="store_form_next"><i class="fa fa-save"></i> Save & Next</button>
                                    <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                                    <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                                </div>
                            </div>

                        </form>
                        {{-- @endif --}}
                        </div> 
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Itinearay Modal-->
        <style type="text/css">
            .steps.clearfix {
                margin-top: 10px
            }

            span.step-icon {
                padding-top: 10px
            }

            .steps.clearfix > ul > li {
                display: inline-flex;
                margin-right: 20px
            }

            .box.box-primary {
                border-top-color: #3c8dbc;
                background: 0 0
            }

            .radio {
                display: inline
            }

            .radio > label {
                margin-right: 30px
            }

            .validationError {
                color: #ff0c0c;
            }

            .button-submit {
                margin-top: 20px;
                margin-bottom: 20px
            }

            .ck.ck-content.ck-editor__editable {
                height: 150px;
            }

            span.ck-file-dialog-button {
                display: none;
            }

            .steps.clearfix.text-center {
                margin-top: 20px;
                padding-bottom: 20px;
            }

            a.dropdown-item.edit {
                padding-left: 10px !important;
                display: inline-block;
                padding: 5px;
            }

            .modal-content {
                position: relative;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
                width: 100%;
                pointer-events: auto;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ebedf2;
                border-radius: .3rem;
                outline: 0
            }

            .modal-footer {
                padding: 10px;
                text-align: right;
                border-top: 1px solid #e5e5e5;
                background-color: #fff
            }

            .sss {
                padding: 8
            }

            .pwa-editor-bar-panel {
                display: none !important;
            }
        </style>
        @endsection
        @section('footerSection')

            <script type="text/javascript">
                $(document).ready(function() {
                    $('#pdf_name').on('change', function(evt) {
                        var size = this.files[0].size;
                        var a = Math.ceil((size/1024)/1024);
                        $("#fileSize").val(a);
                    });
                });
                $(document).ready(function () {
                    $('#store_form').click(function (e) {
                        e.preventDefault();
                        $('#gif').show();
                        var title = $('#title').val();
                        var pdf_name = $('#pdf_name').val();
                        if (title == "" && pdf_name == "") {
                            $("span#title_error").html('Attachment or Itinerary Finder Link is required!');
                            $("input#title").focus();
                            return false;
                        } else {
                            $("span#title_error").hide();
                        }

                        var file_size = $('#fileSize').val();
                        if (file_size > 48) {
                            $("span#size_error").html('File size should be less then <b>48 MB </b>!');
                            $("input#pdf_name").focus();
                            return false;
                        } else {
                            $("span#size_error").hide();
                        }
                        $('#gif').css('visibility', 'visible');
                        $('#store_form').html('Please wait...')
                        $('#store_form').prop('disabled', true);
                        var formDatas = new FormData(document.getElementById('AgentItineraryForm'));
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{ route('pdf_itinerary_store',request()->route('id')) }}",
                            data: formDatas,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                $('#gif').hide();
                                $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                window.location.reload();
                            },
                            errors: function () {

                            }
                        });
                    });
                });

                $(document).ready(function () {
                    $('#store_form_next').click(function (e) {
                        e.preventDefault();
                        $('#gif').show();
                        var title = $('#title').val();
                        var pdf_name = $('#pdf_name').val();
                        if (title == "" && pdf_name == "") {
                            $("span#title_error").html('Attachment or Itinerary Finder Link is required!');
                            $("input#title").focus();
                            return false;
                        } else {
                            $("span#title_error").hide();
                        }

                        $('#gif').css('visibility', 'visible');
                        $('#store_form_next').html('Please wait...')
                        $('#store_form_next').prop('disabled', true);
                        var formDatas = new FormData(document.getElementById('AgentItineraryForm'));
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            url: "{{ route('pdf_itinerary_store',request()->route('id')) }}",
                            data: formDatas,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                $('#gif').hide();
                                $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                window.location = data.url;
                            },
                            errors: function () {

                            }
                        });
                    });
                });
            </script>

@endsection