@extends('layouts.app')
@section('tagSection')
    <title>Create Inclusion | Departure Cloud</title>
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
                        <h4 class="page-title">Add Inclusions</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Inclusion</li>
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
                        <form id="InclusionForm">
                            @csrf
                            <div class="row mt-2">

                                <div class="col-md-12 mb-2 inclusionSelectAll">
                                    <div class="checkbox">
                                        <input type="checkbox" id="ckbCheckAll" class="">
                                        <label class="" for="ckbCheckAll">Select All</label>
                                    </div>
                                </div>
                                @foreach($inclusion_masters as $key=> $inclusion_master)
                                    <div class="col-md-4 editInclusionicon mb-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="checkbox checkbox-primary">

                                                <input type="hidden" name="icons[]" value="{{$inclusion_master->icon}}">
                                                <input id="checkbox{{$key}}" class="checkBoxClass" type="checkbox" name="names[]" value="{{$inclusion_master->name}}{{$loop->index}}" @if(($departure->departure_type == 5 && $inclusion_master->name == 'Flights') || ($departure->departure_type == 4 && $inclusion_master->name == 'Accommodation') || ($departure->departure_type == 3 && ($inclusion_master->name == 'Accommodation' || $inclusion_master->name == 'Flights'))) checked @endif>
                                                <label for="checkbox{{$key}}">
                                                    <img src="{{asset('inclusion-images/'.$inclusion_master->icon)}}" alt="icon" width="12">
                                                    {{$inclusion_master->name}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="collapse show" id="collapseExample{{$key}}">
                                            <input type="text" name="descriptions[]" class="form-control" value="{{$inclusion_master->description}}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row wrappers">
                                <div class="col-md-12">
                                    <h5>Not in list? Add More</h5>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <div class="row position-relative">
                                        <div class="col-md-2 position-static" id="icon_sel">
                                            <div class="form-group">
                                                <label for="">Inclusion Icon</label>
                                                <div class="inclusionSelectIcon" onclick="selectIcon('icon',this,1)" id="clickIcon_1">
                                                    Select Inclusion Icon
                                                </div>
                                                <div class="inclusionSelect_Icon">
                                                    @foreach($inclusion_icon as $key => $row)
                                                        <div class="selectedIcon">
                                                            <img src="{{asset('inclusion-images/'.$row->icon)}}" alt="icon" onclick="selectedIcon({{str_replace(' ', '', $row->name)}})" id="clickID{{$key}}">
                                                            <input type="radio" name="inclusion-icon" id="{{str_replace(' ', '', $row->name)}}" value="{{$row->icon}}">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                {{-- <select class="form-control icons" name="icon[]" id="icons">
                                                    @foreach($inclusion_icon as $row)
                                                        <option value="{{$row->icon}}" data-image="{{asset('inclusion-images/'.$row->icon)}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Inclusion name</label>
                                                <input type="text" class="form-control" name="name[]" id="name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <input type="text" class="form-control" id="description" name="description[]">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="floating-label">
                                                <a href="javascript:void(0);" class="add_button btn btn-outline-primary formlabelmargin" title="Add field"><i class="fas fa-plus"></i> Add More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-right mt-4">
                                <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                                <button class="btn btn-primary active" type="button" id="store_form_next"><i class="fa fa-save"></i> Save & Next</button>
                                <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                            </div>

                        </form>
                    </div>

                </div>
            </div>


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

                .button-submit {
                    margin-top: 20px;
                    margin-bottom: 20px
                }

                .steps.clearfix.text-center {
                    margin-top: 20px;
                    padding-bottom: 20px;
                }
    .navbar-custom .topnav-menu .nav-link{
                padding: 28 15px;
            }
            </style>
            @endsection
            @section('footerSection')
                <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
                <!-- <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script> -->
                <script src="{{asset('js/select2.full.min.js')}}"></script>
                <script src="{{asset('js/customJS/basic-details.js')}}"></script>
                <script>
                    $(".icons").select2({
                        templateResult: formatIcon,
                        templateSelection: formatIcon,
                    });

                    function formatIcon(opt1) {
                        if (!opt1.id) {
                            return opt1.text.toUpperCase();
                        }

                        var optimage = $(opt1.element).attr('data-image');
                        console.log(optimage)
                        if (!optimage) {
                            return opt1.text.toUpperCase();
                        } else {
                            var $opt1 = $(
                                '<span><img src="' + optimage + '" width="20px" /> ' + opt1.text.toUpperCase() + '</span>'
                            );
                            return $opt1;
                        }
                    };
                    $(document).ready(function () {


                        $('input[type="checkbox"]').click(function () {
                            if ($(this).prop("checked") == true) {
                            } else if ($(this).prop("checked") == false) {
                            }
                        });
                    });
                    $(document).ready(function () {
                        var maxFields = 20; //Input fields increment limitation
                        var addButtons = $('.add_button'); //Add button selector
                        var wrappers = $('.wrappers'); //Input field wrapper
                        //var fieldHTMLs = '';
                        var x = 1;

                        $(addButtons).click(function () {
                            if (x < maxFields) {
                                x++;
                                $(wrappers).append(`<div class="rowes col-md-12"><div class="row position-relative"><div class="col-md-2 position-static"><label for="">Inclusion Icon</label><div class="inclusionSelectIcon" onclick="selectIcon('icon',this,${x})" id="clickIcon_${x}")>Select Inclusion Icon</div></div><div class="col-md-4"><label>Name</label><div class="form-group"><input name="name[]" id="name" class="form-control" type="text"></div></div><div class="col-md-4"><label>Description</label><div class="form-group"><input type="text" name="description[]" id="description" class="form-control"></div></div><div class="col-md-2" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button"><img class="ImgWidth" src="{{ asset("images/remove-icon.png")}}" /></a></div></div></div></div>`);

                                $(".icons_add").select2({
                                    templateResult: formatState,
                                    templateSelection: formatState
                                });

                                function formatState(opt) {
                                    if (!opt.id) {
                                        return opt.text.toUpperCase();
                                    }

                                    var optimage = $(opt.element).attr('data-image');
                                    console.log(optimage)
                                    if (!optimage) {
                                        return opt.text.toUpperCase();
                                    } else {
                                        var $opt = $(
                                            '<span><img src="' + optimage + '" width="20px" /> ' + opt.text.toUpperCase() + '</span>'
                                        );
                                        return $opt;
                                    }
                                };
                            }
                        });
                        $(wrappers).on('click', '.remove_button', function (e) {
                            e.preventDefault();
                            $(".rowes").last().remove();
                            x--;
                        });
                    });

                    // Form Submit

                    $(document).ready(function () {
                        $('#store_form').click(function (e) {
                            e.preventDefault();
                            $('#gif').show();
                            var checkbox = $("input[type='checkbox']").val();
                            //alert(checkbox);
                            if (checkbox == "") {
                                $("span#checkbox_error").html('Please select atleast 1 checkbox');
                                $(".inclus").focus();
                                return false;
                            }


                            $('#gif').css('visibility', 'visible');
                            $('#store_form').html('Please wait...')
                            $('#store_form').prop('disabled', true);
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                method: 'POST',
                                url: "{{ route('inclusion_store',request()->route('id')) }}",
                                data: $('#InclusionForm').serialize(),
                                success: function (data) {
                                    $('#gif').hide();
                                    $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                    //window.location = data.url;
                                    window.location.reload();
                                    //window.location.href = "{{ route('flight_create',request()->route('id')) }}";
                                },
                                errors: function () {
                                    $('#gif').hide();
                                    $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                                }

                            });
                        });
                    });
                    $(document).ready(function () {
                        $('#store_form_next').click(function (e) {
                            e.preventDefault();
                            $('#gif').show();
                            var checkbox = $("input[type='checkbox']").val();
                            //alert(checkbox);
                            if (checkbox == "") {
                                $("span#checkbox_error").html('Please select atleast 1 checkbox');
                                $(".inclus").focus();
                                return false;
                            }


                            $('#gif').css('visibility', 'visible');
                            $('#store_form_next').html('Please wait...')
                            $('#store_form_next').prop('disabled', true);
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                method: 'POST',
                                url: "{{ route('inclusion_store',request()->route('id')) }}",
                                data: $('#InclusionForm').serialize(),
                                success: function (data) {
                                    $('#gif').hide();
                                    $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                                    window.location = data.url;
                                    //location.reload();
                                    //window.location.href = "{{ route('flight_create',request()->route('id')) }}";
                                },
                                errors: function () {
                                    $('#gif').hide();
                                    $('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                                }

                            });
                        });
                    });
                </script>
                <script>

                    $("li a").each(function () {
                        //alert(this.href);
                        if (this.href == window.location.href) {
                            $(this).addClass("active");
                        }
                    })
                </script>
                <script>
                    $(document).ready(function () {
                        $("#ckbCheckAll").click(function () {
                            $(".checkBoxClass").attr('checked', this.checked);
                        });
                    });
                </script>
                <!-- <script type="text/javascript">
                   var all_data=[];
                  $('.icons').select2({
                      placeholder: 'Select Icon',
                      ajax: {
                          url: "/inclusion-icon-ajax",
                          dataType: 'json',
                          delay: 250,
                          processResults: function (data) {
                              all_data=data;
                              return {
                                  results: $.map(data, function (item) {
                                      return {
                                          text: item.name,
                                          id: item.icon
                                      }
                                  }),
                              };
                          },
                          cache: true
                      }
                  });
                </script>
                <script type="text/javascript">
                  $('#icon_sel').click(function(){
                      setTimeout(function() {
                                      set_icons(all_data);
                                  }, 200);
                   });
                  function set_icons(dta){
                          $('.select2-results__options').each(function(){
                              var phrase = '';
                              $(this).find('li').each(function(){
                                  var htmll=$(this).html();
                                  for(let i=0;i<dta.length;i++){
                                      if(dta[i].name==htmll){
                                  var image_ht="<img class='mr-1' src='http://localhost:8000/inclusion-images/"+dta[i].icon+"' width='22'>"+htmll;
                                  $(this).html(image_ht);

                                      }

                  }
                              });
                      });

              }
              </script> -->

@endsection