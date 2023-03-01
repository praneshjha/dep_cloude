@extends('layouts.app')
@section('tagSection')
<title>Departure Cloud | Create Day Schedule</title>
@endsection
@section('headCssSection')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="wrapper">
  <div class="wrapperOverlay"></div>
  <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                <h4 class="page-title">Add Day Schedule</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                        <li class="breadcrumb-item active">Day Schedule</li>
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
            @if($day <= $departure->no_of_days)
            <form role="form" id="ItineraryForm" class="mt-4">
              @csrf
                <div class="row">
                  <div class="col-md-6 col-lg-6 col-xl-6">
                    <div class="row">
                      <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                          <label>Day</label> <span class="validationError" id="day_error"></span>
                          <input type="text" class="form-control" name="day" id="day" value="{{ array_shift($day_number) }}" readonly="">
                        </div>
                      </div>
                      <div class="col-md-10 col-lg-10 col-xl-10 col-sm-12 col-xs-12">
                        <div class="form-group">
                          <label>Day Heading</label> <span class="validationError" id="day_heading_error"></span>
                          <input type="text" class="form-control" name="day_heading" id="day_heading" placeholder="Enter day heading">
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xl-12">
                      <div class="form-group">
                        <label>Destinations</label> <span class="validationError" id="destinations_error"></span>
                          <select class="form-control destinations" name="destinations[]" id="destinations" multiple="">
                            @foreach($destinations as $destination)
                              <option value="{{$destination->id}}">{{$destination->dest_name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-6 col-xl-6">
                    <div class="row">
                      <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                          <label>Description</label> <span class="validationError" id="description_error"></span>
                          <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-right">
                    <button class="btn btn-primary active" type="button" id="store_form"> <span class="crop_text"><i class="fa fa-save"></i> Add Day Schedule</span>
                      <span class="crop_wait" style="display: none">                  
                       Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                    </span>
                    </button>
                  </div> 
                </div>
            </form>
            @else
             <div class="col-md-12 text-center" style="margin-bottom: 10px;margin-top: 40px;">
              <h4>Itinerary for all days has been created</h4>
            </div>
          @endif
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        @include('departure/itinerary_list')
      </div>
    </div>
    <style type="text/css">
      .panel-heading .btn{color: #423b3b;} #day{border: 1px solid #ced4da;}span.select2.select2-container.select2-container--default.select2-container--focus.select2-container--below {
    width: 100% !important;
}span.select2.select2-container.select2-container--default {
    width: 100% !important;
}
    </style>
    <div class="modal fade updateDayItinerary" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content hold">
          <div class="modal-header">
            <h5 class="modal-title text-white" id="mySmallModalLabel">Edit Day Schedule</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-x">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
          </div>
          <form role="form" id="editDayItinerary" style="background-color: #fdfdfd;" class="p-1">
            @csrf
            <input type="hidden" name="itinerary_id" id="edit_id">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Day</label>
                    <input type="text" class="form-control" name="edit_day" id="edit_day" readonly="">
                  </div>
                </div>
                <div class="col-md-10 col-lg-10 col-xl-10 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Day Heading</label> <span class="validationError" id="edit_day_heading_error"></span>
                    <input type="text" class="form-control" name="edit_day_heading" id="edit_day_heading" placeholder="Enter day heading">
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-xl-12">
                  <div class="form-group" id="editSelect">
                  <label>Destinations</label><span class="validationError" id="edit_destinations_error"></span><br>
                    <select class="form-control" name="edit_destinations[]" id="edit_destinations" multiple="">
                      @foreach($destinations as $desti)
                        <option value="{{$desti->id}}">{{$desti->dest_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="edit_description" id="edit_description"></textarea>
                  </div>
                </div>
                <div class="col-md-12 text-right">
                    <span class="text-success" id="mesegese" style="margin-left: 10px;margin-bottom:16px;"></span>
                    <button class="btn btn-primary active" type="button" id="edit_store_form" style="margin-bottom:16px;"><i class="fa fa-save"></i>Update Day Schedule</button>
                </div>
              </div>
            </div>
          </form>
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
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#store_form').click(function (e) {
        e.preventDefault();
        var day_heading = $('#day_heading').val();
        if (day_heading == "") {
            $("span#day_heading_error").html('This field is required!');
            $("input#day_heading").focus();
            return false;
        } else {
            $("span#day_heading_error").hide();
        }
        var destinations = $('#destinations').val();
        if (destinations.length < 0) {
            $("span#destinations_error").html('Please select atleast 1 description!');
            $("select#destinations").focus();
            return false;
        } else {
            $("span#size_error").hide();
        }
        $('#store_form').html('Please wait...')
        $('#store_form').prop('disabled', true);
        var formDatas = new FormData(document.getElementById('ItineraryForm'));
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('itinerary_store', request()->route('id')) }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
              $('#mesegese').html("<span class='sussecmsg'> Day Schedule Added Successfully!</span>");
              location.reload();
          },
          errors: function () {
             $('#mesegese').html("<span class='sussecmsg'>Something wend wrong!</span>");
          }
        });
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function () {
      $('#edit_store_form').click(function (e) {
        e.preventDefault();
        var edit_id = $('#edit_id').val();
        var edit_day_heading = $('#edit_day_heading').val();
        if (edit_day_heading == "") {
            $("span#edit_day_heading_error").html('This field is required!');
            $("input#edit_day_heading").focus();
            return false;
        } else {
            $("span#edit_day_heading_error").hide();
        }
        var edit_destinations = $('#edit_destinations').val();
        if (edit_destinations.length < 0) {
            $("span#edit_destinations_error").html('Please select atleast 1 description!');
            $("select#edit_destinations").focus();
            return false;
        } else {
            $("span#size_error").hide();
        }
        $('#edit_store_form').html('Please wait...')
        $('#edit_store_form').prop('disabled', true);
        var formDatas = new FormData(document.getElementById('editDayItinerary'));
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "/departure/itinerary-update/"+edit_id,
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
              $('#mesegese').html("<span class='sussecmsg'> Day Schedule Added Successfully!</span>");
              window.location.reload();
          },
          errors: function () {
             $('#mesegese').html("<span class='sussecmsg'>Something wend wrong!</span>");
          }
        });
      });
    });
  </script>

   <script type="text/javascript">
    $(document).ready(function () {
      $('.editItinerary').on('click', function() {
        $('.updateDayItinerary').modal('show');
        
        var id = $(this).data('id');
        var day_number = $(this).data('daynumber');
        var day_heading = $(this).data('dayheading');
        var description = $(this).data('description');

        var destination_id = $(this).attr("data-destId");
        var dest_id = JSON.parse(destination_id);
        
        var destination_name = $(this).attr("data-destName");
        var dest_name = JSON.parse(destination_name);
        console.log(dest_name);

        $("#edit_id").val(id);
        $("#edit_day").val(day_number);
        $("#edit_day_heading").val(day_heading);
        $('#edit_description').summernote().summernote('code', description);

        var data = [];
        $('#edit_destinations').val('').trigger('change');

        for (var i = 0; i < dest_id.length; i++) {
            var $select = $("#edit_destinations");
            var items = {id: dest_id[i], text: dest_name[i]};
            //console.log(items);
            var data = $select.val() || [];
            $(items).each(function () {
                if (!$select.find("option[value='" + this.id + "']").length) {
                    $select.append(new Option(this.text, this.id, true, true));
                }
                data.push(this.id);
            });
            $select.val(data).trigger('change');
        }
      });
    });

</script>
  <script type="text/javascript">
    $('#destinations').select2({
      placeholder: 'Destination(s)',
    });
    $("li a").each(function () {
      if (this.href == window.location.href) {
        $(this).addClass("active");
      }
    })
  </script>
  <script>
     $(document).ready(function() {
      $('#edit_destinations').select2({
        dropdownParent: $("#editSelect")
      });
      $('#description').summernote({
         toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['codeview']]
        ],
         callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:150,
        focus: true
        });
    });
  </script>
  <script>
     $(document).ready(function() {
      $('#edit_description').summernote({
         toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['codeview']]
        ],
         callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:220,
        focus: true
        });
    });
  </script>

@endsection