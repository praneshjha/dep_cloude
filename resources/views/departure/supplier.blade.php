@extends('layouts.app')
@section('tagSection')
<title>Supplier List | Departure Cloud</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
// <?php //dd($userlist); ?>
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Suppliers</h4> 
                        <span><h5>Active {{ $active_count }}/ Total {{ $toal_count }}</h5></span>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Suppliers</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-rounded-circle card-box bg-transparent px-0">
                <form class="" action="{{route('suplier_list')}}">
                    <div class="row m-md-0">
                        <div class="col-md-5 col-sm-5 col-lg-5 p-md-0">
                            <div class="form-group mb-0">
                                <input class="form-control" name="keyword" id="" value="{{$keyword}}" placeholder="Search by : Name, Email or Company">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-lg-3 p-md-0">
                            <div class="form-group mb-0">
                                <select class="form-control " name="status" id="name" data-toggle="select2">
                                    <option value="1">Verified</option>
                                    <option value="2" >Not Verified</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 0;"><i class="fas fa-search"></i> Search</button>
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 p-md-0">
                            <a href="{{route('suplier_list')}}" class="btn btn-secondary w-100" style="border-radius: 0;"><i class="fas fa-times"></i> Clear</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <nav>
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="{{route('user_list')}}" class="nav-link">Buyers</a></li>
                                        <li class="nav-item"><a href="{{route('suplier_list')}}" class="nav-link active"> Suppliers </a></li>
                                        <li style="position:absolute;
                                        right:0px;">
                                            <form action="{{route('suplier_list')}}">
                                                <input type="hidden" value="excel" name="excel">
                                                <button type="submit" class="btn nav-link" style="font-size:20px" title="Export CSV"><i class="fa fa-file-excel-o" aria-hidden="true" style="color:#207245;"></i>
                                                </button>
                                            </form>
                                        </li> 
                                    </ul>
                                </nav>
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
                                         <th>City(Country)</td>
                                         <th>Type</th>
                                         <th style="width: 12%;">Reg. Date</th>
                                         <th>Users</th>
                                         <th>A/T.Deps</th>
                                         <th>Mail Status</th>
                                         <th>User Status</th>
                                         <th>Action</th>
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
                                          <td>{{$row->city}} ({{$row->country}})</td>
                                          <td>@if($row->main_user_type == 1 && $row->role_id == 0)
                                                Supplier
                                            @endif
                                          </td>
                                          <td>{{date('d M, Y', strtotime($row->created_at))}}</td>
                                          <td><a class="badge badge-danger text-light font-weight-bold" href="{{ route('suplier_user_list', $row->id) }}" target="_blank" style="background-color: #2281f6;"><i class="fas fa-eye"></i></a>
                                          </td>
                                          <td>
                                            {{ $row->activedeps}}/{{$row->totalcount }}
                                          </td>
                                          <td>@if($row->email_verified_at == null)
                                               <span class="badge badge-danger text-uppercase font-weight-bold">Not Verified</span>
                                              @else
                                              <span class="badge badge-success text-uppercase font-weight-bold status12" >Verified</span>
                                              @endif
                                            </td>
                                          <td>@if($row->verified==0)
                                               <span class="badge badge-danger text-uppercase font-weight-bold">Unupproved</span>
                                              @else
                                              <span class="badge badge-success text-uppercase font-weight-bold">Approved</span>
                                              @endif
                                          </td>
                                          <td>
                                          @if($row->verified==0)
                                               <a class="badge badge-danger text-light font-weight-bold status" data-id="{{ $row->tenant_id }}" data-status="{{ $row->varified }}"><i class="fas fa-check"></i></a>
                                          @endif
                                          @if($row->main_user_type == 1)
                                          <a class="badge badge-danger text-light font-weight-bold user-change" data-id="{{ $row->tenant_id }}" data-status="{{ $row->main_user_type }}"><i class="fas fa-exchange-alt"></i></a>
                                          @endif
                                          </td>
                                       </tr>
                                       @endforeach
                                       
                                      </tbody>
                                       </table>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">{{$userlist->withQueryString()->links()}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerSection')

<script type="text/javascript">
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
<style type="text/css">
    ul.pagination {
        float: right;
    }
</style>
@endsection