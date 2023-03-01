@extends('layouts.app')
@section('tagSection')
<title>Departure Cloud - Notifications</title>
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
                                <li class="breadcrumb-item active">Notifications</li>
                            </ol>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                      <h4 class="page-title mb-3">Notifications<sup><span class="Notify badge badge-danger rounded-circle noti-icon-badge">{{$total}}</span></sup></h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                         <th>S.No.</th>
                                         <th>Title</th>
                                         <th>Message</th>
                                         <th>Date</th>
                                         <th>Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                       @foreach($notifications as $key => $row)
                                       <tr>
                                          <td>{{ ($notifications->currentpage()-1) * $notifications->perpage() + $key + 1 }}</td>
                                          <td>{{$row->title}}</td>
                                          <td>{!! $row->body_html !!}</td>
                                          <td>{{date('d M, Y', strtotime($row->created_at))}}</td>
                                          <td><a href="{{$row->url_1}}" target="_blank" onclick="showNotification({{$row->id}})" id="showNotification">View Details</a></td>
                                       </tr>
                                       @endforeach
                                        </tbody>
                                     </table>
               
                                 </div>
                            </div>
                            <div class="col-md-12 notificationPagination">{{$notifications->withQueryString()->links()}}
                            </div>
                        </div>

                    </div>
                </div>
                
            </div>
        </div>
        <style type="text/css">
          td>p{margin-bottom: 0;}
          .Notify.rounded-circle {border-radius: 4%!important;}
        </style>
@endsection
@section('footerSection')
  <script>
      function showNotification(id){
          //alert(id);
          jQuery.ajax({
              headers: {
                  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "/notification-status-change/"+id,
              contentType: false,
              processData: false,
              success: function (data) {
                  //alert(data.url);
                 window.location = data.url;
              },
              errors: function () {

              }

          });
      }
  </script>
@endsection