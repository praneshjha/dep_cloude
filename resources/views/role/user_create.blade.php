@extends('layouts.app')
@section('tagSection')
<title>Manage Users | Departure Cloud</title>
@endsection
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                       <h4 class="page-title">Manage User</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Users</li>
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
                                <h3>Users List
                                </h3>
                                <h3>
                                <form class="app-search" method="get" action="{{route('user')}}">
                                    <div class="app-search-box">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search...." value="{{$keyword}}" name="keyword" style="margin-left:5px;">
                                            <div class="input-group-append" style="margin-right: 5px;">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fe-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                </h3>
                                @can('user_create', $permission)
                                    <a href="" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#modal">Create New User</a></h1>
                                @endcan
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Role</th>
                                            @can('user_activate_inactivate', $permission)
                                                <th>Status</th>
                                            @endcan
                                            @can('user_edit', $permission)
                                                <th>Action</th>
                                            @endcan
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($user as  $key => $row)
                                            <tr>
                                                <td>{{ ($user->currentpage()-1) * $user->perpage() + $key + 1 }}</td>
                                                <td>{{$row->name}}</td>
                                                <td>{{$row->email}}</td>
                                                <td>{{$row->mobile}}</td>
                                                <td>
                                                    @foreach($row->sub_name as $value)
                                                        <span class="badge badge-success text-light p-1"> {{ucfirst($value->name)}}</span>
                                                    @endforeach
                                                </td>
                                                @can('user_activate_inactivate', $permission)
                                                    <td>@if($row->verified == 0)
                                                            <a class="userdiasable1 badge badge-danger text-light" data-id="{{ $row->id }}" data-status="{{ $row->verified }}" style="cursor: pointer; color: #2f8263;">Inactive
                                                            </a>
                                                        @else
                                                            <a class="userdiasable badge badge-success text-light" data-id="{{ $row->id }}" data-status="{{ $row->verified }}" style="cursor: pointer; color: #F9423C;">
                                                                Active
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endcan
                                                @can('user_edit', $permission)
                                                    <td>
                                                        <a href="" class="btn btn-sm" data-toggle="modal" data-target="#modal{{$row->id}}" style="cursor: pointer; color: black;"><i class="fa fa-edit"></i></a> ||
                                                        <a href="" class="btn btn-sm delete" data-id="{{ $row->id }}" data-status="{{ $row->verified }}" style="cursor: pointer; color: #F9423C;">
                                                            <i class="fa fa-trash"></i></a>
                                                        <!-- <a href="" class="dropdown-item"   data-toggle="modal" data-target="">Email Send Again</a>          -->
                                                        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" id="modal{{$row->id}}" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-white" id="mySmallModalLabel">Update User</h5>
                                                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{route('user_update')}}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="id" value="{{$row->id}}">
                                                                        <div class="row p-1">
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <label for="exampleFormControlInput1">User Name </label>
                                                                                    <input type="text" class="form-control" id="name" name="name" value="{{$row->name}}" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <label for="exampleFormControlInput1">Email </label>
                                                                                    <input type="email" class="form-control" id="email" name="email" value="{{$row->email}}" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <label for="exampleFormControlInput1">Phone </label>
                                                                                    <input type="text" class="form-control" id="phone" name="phone" value="{{$row->mobile}}" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <label for="exampleFormControlInput1">Role</label>
                                                                                    <select class="form-control" name="role">
                                                                                        @foreach($role as $data)
                                                                                            <option value="{{$data->id}}" @if($row->role_id == $data->id) selected @endif>{{ucfirst($data->name)}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="form-group dc_disable_password">
                                                                                    <label for="exampleFormControlInput1">Old Password </label>
                                                                                    <br>
                                                                                    <input type="password" class="form-control" id="old_password" value="{{$row->text_password}}" disabled>
                                                                                    <i toggle="#old_password" class="fa fa-fw fa-eye field-icon toggle-password"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <label for="exampleFormControlInput1">New Password </label>
                                                                                    <input type="text" class="form-control" id="new_password" name="new_password" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>   
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                                    </div>
                                                                 </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endcan
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="col-md-12" style="text-align:center;">{{$user->withQueryString()->links()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" id="modal" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mySmallModalLabel">Create User</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('user_create')}}" method="post">
                        @csrf
                        <div class="row p-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">User Name </label>
                                    <input type="text" class="form-control" id="uname" name="name" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Email </label>
                                    <input type="email" class="form-control" id="uemail" name="email" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Phone </label>
                                    <input type="text" class="form-control" id="uphone" name="phone" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Role</label>
                                    <select class="form-control" name="role">
                                        @foreach($role as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>


@if(\Session::has('msg'))
        <div class="alert text-light alert-dismissible fade show " id="myElem" role="alert" style="">
            <div id="success_msg" style="">
                {{\Session::get('msg')}}
            </div>
            
        </div>
@endif

@endsection
@section('footerSection')
<script type="text/javascript">
    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
    <script type="text/javascript">
        $(".userdiasable").click(function () {
            var flag = (status == 0) ? 'active' : 'inactive';
            if (confirm("Are you sure you want to " + flag + " this User?"))
                var id = $(this).data("id");
            var status = $(this).data("status");

            var token = "{{ csrf_token() }}";
            if (id) {
                $.ajax({
                    url: '/departure/user/disable/' + id,
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
        $(".userdiasable1").click(function () {
            var flag = (status == 0) ? 'active' : 'Active';
            if (confirm("Are you sure you want to " + flag + " this user?"))
                var id = $(this).data("id");
            var status = $(this).data("status");

            var token = "{{ csrf_token() }}";
            if (id) {
                $.ajax({
                    url: '/departure/user/disable/' + id,
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
        $(".delete").click(function () {
            var flag = (status == 0) ? 'active' : 'Active';
            if (confirm("Are you sure you want to delete this user?"))
                var id = $(this).data("id");
            var status = $(this).data("status");

            var token = "{{ csrf_token() }}";
            if (id) {
                $.ajax({
                    url: '/user-delete/' + id,
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
    <script>
        $("#imgInp").change(function(){
        readURL(this);
        });
        $("#myElem").show().delay(4000).queue(function(n) {
          $(this).hide(); n();
        });
    </script>
    <style>
         .modal-dialog {
                max-width: 400px;
                margin: 0 auto;
            }
            .modal {
                left: unset;
                padding-right: 0 !important;
            }
            .modal.fade .modal-dialog {
                -webkit-transform: translate(25%,0);
                transform: translate(25%,0);
            }
            .modal.show .modal-dialog {
                -webkit-transform: translate(0,0);
                transform: translate(0,0);
            }
            .modal-backdrop.show {
                opacity: .7;
            }
            .form-control:disabled, .form-control[readonly] {
                background-color: #fff;
              }
              .modal-header{
                /*height: 61px;
                align-items: center;
                background-color: #093E8E;
                margin-top: 70px;
                border-radius: 0;*/
              }
    </style>
    <style>
    #myElem{
       display: flex;justify-content: center;height: 100%;position: absolute;z-index: 999;bottom: 10px;width: 100%;

    }
    #success_msg{
      background:#0a3f8e; position:absolute;    background: #0a3f8e;position: absolute;bottom: 46px;padding: 9px 42px;font-size: 16px;border-radius: 5px;
      border: 5px solid white;
    }
    .dc_disable_password{
        position: relative;
    }
    .dc_disable_password .form-control{
        border: 1px solid #ced4da;
    }
    .dc_disable_password .fa.fa-fw.fa-eye.field-icon,.dc_disable_password .fa.fa-fw.field-icon{
        position: absolute;
        right: 9px;
        top: 34px;
    }
   </style>
@endsection
