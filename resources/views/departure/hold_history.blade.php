@extends('layouts.app')
@section('tagSection')
<title>Hold History | Departure Cloud</title>
@endsection
@section('content')


<div class="wrapper">
    <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Hold History</li>
                            </ol>
                        </div>
                        
                    </div>
                </div>
            </div>
              <div class="layout-px-spacing">
            @if(session()->has('msg'))
            <div class="alert alert-success">
            {{ session()->get('msg') }}
           </div>
            @endif
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="departureTable" class="table table-bordered table-striped">
                                        <thead>
                                          <tr>
                                           <th>Sl.</th>
                                           <th>Holded</th>
                                           <th>H-Email</th>
                                           <th>Dep Name</th>
                                           <th>Dep Owner</th>
                                           <th>H-Seat</th>
                                           <th>H-Duration</th>
                                           <th>Auto-Release</th>
                                           <th>Price</th>
                                           <th>Action</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                         
                                         @foreach($hold as $key => $row)
                                         <tr>
                                            <td>{{$key+1}}</td>
                                            @foreach($row->hold as $value)
                                            <td>{{$value->company_name}}</td>
                                            <td>{{$value->email}}</td>
                                            @endforeach
                                            <td>{{$row->title}}</td>
                                            <td>{{$row->company_name}}
                                            <td>{{$row->hold_seat}} Unit</td>
                                            <td>{{$row->hold_duration}} hours</td>
                                            <td> {{date('d M, Y', strtotime($row->date))}}</td>
                                            <td>{{$row->currency_code}} {{$row->price}}</td>
                                            <td><a class="btn btn-danger btn-sm text-white disableDepartue" data-id="{{ $row->hold_id }}" data-status="{{ $row->status }}">Release</a></td>
                                         </tr>
                                         @endforeach
                                         
                                        </tbody>
                                     </table>
               
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('footerSection')
  <script type="text/javascript">
    $(".disableDepartue").click(function () {
      if (confirm("Are you sure you want to release this?"))
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
      $.ajax({
        url: '/forcehold/departure/release/' + id,
        type: 'GET',
        data: {
            "id": id,
            "_token": token,
        },
        success: function (data) {
        window.location.reload();
        }
      });
      }
    });
  </script>
  
  @endsection