@extends('layouts.app')
@section('tagSection')
<title>Supplier Users List | Departure Cloud</title>
@endsection
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Departures Cloud</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Supplier's Users</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                <h3>Supplier
                                    <span class="btn btn-info btn-sm"><span style="color:#ffeb00"> {{$tenant_id->name}}</span> 
                                    <span class="btn btn-info btn-sm"><span style="color:#ffeb00"> {{$tenant_id->email}}</span>
                                    <span class="btn btn-info btn-sm"><span style="color:#ffeb00"> {{$tenant_id->mobile}}</span>
                                    <span class="btn btn-info btn-sm"><span style="color:#ffeb00"> {{$tenant_id->company_name}}</span>
                                    <span class="btn btn-info btn-sm"><span style="color:#ffeb00"> {{$tenant_id->tenant_id}}</span> 
                                </h3>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                         <th>Sl</th>
                                         <th>Name</th>
                                         <th>Email</th>
                                         <th>Phone</th>
                                         <th>Company</td>
                                         <th>Role</th>
                                         <th>Status</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                       @foreach($userlist as $key => $row)
                                       <tr>
                                          <td>{{$key+1}}</td>
                                          <td><a href="{{ route('user_booking', ['id' => $row->id]) }}" >{{$row->name}}</a></td>
                                          <td>{{$row->email}}</td>
                                          <td>{{$row->mobile}}</td>
                                          <td>{{$row->company_name}}</td>
                                          <td style="color: #712d55;">{{$row->role}}</td>

                                          <td>@if($row->verified==0)
                                               <span class="badge badge-danger text-uppercase font-weight-bold">Not Verified</span>
                                              @else
                                              <span class="badge badge-success text-uppercase font-weight-bold">Verified</span>
                                              @endif
                                          </td>
                                       </tr>
                                       @endforeach
                                       
                                      </tbody>
                                       </table>
                                       {{$userlist->links()}}
                                       
                                </div>
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

$(function() {
    $("td[colspan=3]").find("p").hide();
    $("table").click(function(event) {
        event.stopPropagation();
        var $target = $(event.target);
        if ( $target.closest("td").attr("colspan") > 1 ) {
            $target.slideUp();
        } else {
            $target.closest("tr").next().find("p").slideToggle();
        }                    
    });
});
    
  $(".user-change").click(function () {
    if (confirm("Are you sure you provide buyer this user?"))
    var id = $(this).data("id");
    var status = $(this).data("status");
    var flag = (status == 0)?'Buyer':'Buyer & Supplier';
    var token = "{{ csrf_token() }}";
    if(id){
    $.ajax({
      url: '/user-type-change/' + id,
      type: 'POST',
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
<script type="text/javascript">
  $(".status").click(function () {
    if (confirm("Are you sure you want active this user?"))
    var id = $(this).data("id");
    var status = $(this).data("status");
    
    var flag = (status == 0)?'Varified':'Unvarified';
    
    var token = "{{ csrf_token() }}";
    if(id){
    $.ajax({
      url: '/user-status-change/' + id,
      type: 'POST',
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
<script type="text/javascript">
  $(".status1").click(function () {
    if (confirm("Are you sure you want inactive this user?"))
    var id = $(this).data("id");
    var status = $(this).data("status");
    
    var flag = (status == 0)?'Varified':'Unvarified';
    
    var token = "{{ csrf_token() }}";
    if(id){
    $.ajax({
      url: '/user-status-change/' + id,
      type: 'POST',
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