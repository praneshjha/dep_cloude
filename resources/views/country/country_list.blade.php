@extends('layouts.app')
@section('tagSection')
<title>Countries List | Departure Cloud</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
<div class="wrapper">
    <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Countries</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Countries</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-rounded-circle card-box bg-transparent px-0">
                <form class="" action="{{route('user_list')}}">
                    <div class="row m-md-0">
                        
                    </div>
                </form>
            </div>
            <div class="row">
              <div class="col-md-12 col-xl-12">
                <div class="widget-rounded-circle card-box">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table id="" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                            <th>Sl.</th>
                            <th>Country Name</th>
                      <th>Country Slug</th>
                      <th>Title</th>
                      <th>Meta Keywords</td>
                      <th>Meta Description</td>
                      <th>Action</th>
                      <th>Edit</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($countryl as $key => $row)
                    <tr>
                      <td>{{ ($countryl->currentpage()-1) * $countryl->perpage() + $key + 1 }}</td>
                      <td><a href="{{$row->country_slug}}" target="_blank">{{$row->country_name}}</a></td>
                      <td>{{$row->country_slug}}</td>
                      <td>{{$row->meta_title}}</td>
                      <td>{{$row->meta_keywords}}</td>
                      <td>{{$row->meta_description}}</td>
                      <td>@if($row->status == 1)
                            {{"Open"}}
                          @else 
                            {{"Close"}}
                          @endif
                      </td>
                      <td>
                        <!-- <a href="javascript:void(0);" data-toggle="modal" data-target="#booking_modal"
                            title="Edit Tags" class="tooltipbubble formbtn" id="countryEdit" data-id="{{($row->id)}}" data-title="{{$row->meta_title}}" data-keywords="{{$row->meta_keywords}}" data-description="{{$row->meta_description}}">
                          <i class="far fa-edit"></i>
                        </a> -->
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#booking_modal"
                            title="Edit Tags" class="tooltipbubble editCbtn" id="countryEdit" data-id="{{($row->id)}}" data-title="{{$row->meta_title}}" data-keywords="{{$row->meta_keywords}}" data-description="{{$row->meta_description}}">
                          <i class="far fa-edit"></i>
                        </a>

                        <!--01 <button type="button" class="btn btn-primary editbtn btn-sm" value="{{$row->id}}">
                          <i class="far fa-edit"></i></button> -->

                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
                <div class="col-md-12 pagiNate mt-3">{{$countryl->links()}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

            
  <div class="modal fade" id="editCountry" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="width: 600px;">
    <div class="modal-dialog modal-mb " role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="mySmallModalLabel">Edit Country Meta Tags</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
              <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
            </button>
        </div>
        <form id="myEditFormCountry" method="post" action="{{route('country_update')}}">
          @csrf
          <div class="modal-body" style="width: 600px;">
              <div class="form-group">
                  <label for="exampleFormControlInput1">Edit Country Meta Tags
                  <input type="text" class="form-control" id="edit_id" name="edit_id">
              </div>
              <div class="row">
              <div class="col-md-12">
                      <div class="form-group">
                          <label for="exampleFormControlInput1">Meta Title</label>
                          <textarea class="form-control" name="meta_title" id="meta_title"></textarea>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="exampleFormControlInput1">Meta Keywords</label>
                          <textarea class="form-control" name="meta_keywords" id="meta_keywords"></textarea>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="exampleFormControlInput1">Meta Description</label>
                          <textarea class="form-control" name="meta_description" id="meta_description"></textarea>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer" style="width: 650px;">
              <button class="btn btn-secondary" data-dismiss="modal" id=""><i
                          class="flaticon-cancel-12"></i> Close
              </button>
              <input type="submit" class="btn btn-primary" id="update_{{$row->id}}" name="submit" value="Update">
             
              <img src="{{ asset('images/loader.gif') }}" id="gif_{{$row->id}}"
                    style="width: 8%; visibility: hidden;">
              <span class="text-success" id="mesegese_{{$row->id}}" style="margin-left: 10px"></span>
          </div>
      </form>
        </div>
      </div>                
    </div>

@endsection

@section('footerSection')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<!--<script type="text/javascript">
    $(document).ready(function () {
            $('#edit_send_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait_edit").show();
                $(".crop_text_edit").hide();
                var edit_id = $('#edit_id').val();
                var formDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/countries/update/' + edit_id,
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                      //console.log(data);
                      $('#mesegeses').html("<span class='sussecmsg'>"+data+"</span>");
                      location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
      
  </script> -->
  <script>
    $(document).ready(function(){
      $(document).on('click','.editCbtn',function(){
        var id = $(this).attr("data-id");
        var title = $(this).attr("data-title");
        var keywords = $(this).attr("data-keywords");
        var description = $(this).attr("data-description");
        $('#editCountry').modal('show');
        // alert(title); 
        $("#edit_id").val(id);
      $("#meta_title").val(title);
      $("#meta_keywords").val(keywords);
      $("#meta_description").val(description);
      });
    });
  </script>

@endsection