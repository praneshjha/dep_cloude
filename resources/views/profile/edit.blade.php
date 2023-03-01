@extends('layouts.app')
@section('tagSection')
<title>Company Profile | Departure Cloud</title>
@endsection
@section('content')
@section('headnav') 
@include('layouts.topnav')
@endsection

  <div class="layout-px-spacing">
    <div class="chat-section layout-top-spacing">
      <div class="widget-content widget-content-area br-6">
        <div class="row layout-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                    <form id="general-info" class="section general-info" action="{{route('update_prifile')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                        <div class="info">
                                            <h2 class="pb-4">Company Profile</h2>
                                            <div class="row">
                                                <div class="col-lg-11 mx-auto">
                                                    <div class="row">
                                                       
                                                        <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                            <div class="form">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="fullName">Company Name</label>
                                                                            <input type="text" class="form-control mb-4" id="fullName" name="company_name" placeholder="" value="{{$edit->company_name}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-lg-4 col-sm-6" style="margin-bottom: 10px">
                                                                        <div class="form-group">
                                                                        <label for="exampleInputFile">Company Logo</label> <span class="validationError" id="image_error"></span> 
                                                                        <input type="file" id="image" name="logo" onchange="readURL(this);">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2 col-lg-2 col-sm-6" style="margin-bottom: 10px">
                                                                    <img id="blah" onclick="triggerImage()" src="@if(isset(Auth::user()->logo)){{ asset('companyLogo/' . Auth::user()->logo) }} @else {{asset('images/no-image.png')}} @endif" class="" width="100" height="100"/>
                                                                    </div>
                                                                    <!-- <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Company ID</label>
                                                                        <input type="text" class="form-control mb-4" id="company_id" name="company_id" placeholder="" value="{{$edit->tenant_id}}" readonly>
                                                                    </div>
                                                                    </div> -->
                                                                    <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Contact Name</label>
                                                                        <input type="text" class="form-control mb-4" id="company_id" name="name" placeholder="" value="{{$edit->name}}">
                                                                    </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Contact No.</label>
                                                                        <input type="text" class="form-control mb-4" id="mobile" name="mobile" placeholder="" value="{{$edit->mobile}}">
                                                                    </div>
                                                                    </div>
                                                                    <!-- <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Email Id</label>
                                                                        <input type="text" class="form-control mb-4" id="contact_name" name="email" placeholder="" value="{{$edit->email}}">
                                                                    </div>
                                                                    </div> -->
                                                                    <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Address</label>
                                                                        <input type="text" class="form-control mb-4" id="city" name="address" placeholder="" value="{{$edit->address}}">
                                                                    </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">City</label>
                                                                        <input type="text" class="form-control mb-4" id="city" name="city" placeholder="" value="{{$edit->city}}">
                                                                    </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">State</label>
                                                                        <input type="text" class="form-control mb-4" id="" name="state" placeholder="" value="{{$edit->state}}">
                                                                    </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Country</label>
                                                                        <input type="text" class="form-control mb-4" id="" name="country" placeholder="" value="{{$edit->country}}">
                                                                    </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Pin Code</label>
                                                                        <input type="text" class="form-control mb-4" id="" name="pin_code" placeholder="" value="{{$edit->pin}}">
                                                                    </div>
                                                                    </div>
                                                               
                                                                
                                                                <div class="col-sm-6">
                                                                  <div class="form-group">
                                                                      <label for="profession">Website</label>
                                                                      <input type="text" class="form-control mb-4" id="profession" name="website" placeholder="ex. www.example.com" value="{{$edit->website}}">
                                                                  </div>
                                                                </div>
                                                                @if(auth::user()->main_user_type == 1)
                                                                <div class="col-sm-12">
                                                                <div class="form-group">
                                                                <label for="profession">Destinations</label>
                                                                <select class="form-control" name="destination[]" id="destination" style="display:none" multiple>
                                                                @foreach($user_destination as $row)
                                                                <option selected="selected">{{$row->destination_name}}</option>
                                                                @endforeach
                                                                </select>
                                                                </div>
                                                                </div>
                                                                @endif
                                                                </div>
                                                                <button type="submit" class="btn btn-primary" style="margin-top: 15px"><i class="fa fa-edit"></i>&nbsp;Update</button>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                    
                    

                            
          </div>      
       </div>
    </div>
</div>


@if(session('message'))
<div class="modal fade" id="myModal" role="dialog" style="">
   <div class="modal-dialog modal-sm" >
      <div class="modal-content">
         <div class="modal-body text-center">
            <h5>{{session('message')}}</h5>
            <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>
@endif


@endsection

@section('footerSection')
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script>
  
    function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#blah')
                            .attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                  }
                }


     function triggerImage(){
              $('#image').trigger('click');
     }    
</script>
<script>
$('#destination').select2({
      placeholder: 'Select Destinations',
      ajax: {
          url: "/start_from_destination",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
              return {
                  results: $.map(data, function (item) {
                      return {
                          text: item.destination,
                          id: item.destination
                      }
                  })
              };
          },
          cache: true
      }
    });
  </script>
<script>
   $('#myModal').modal({
       backdrop: 'static',
       keyboard: false  // to prevent closing with Esc button (if you want this too)
   })
   </script>
@endsection