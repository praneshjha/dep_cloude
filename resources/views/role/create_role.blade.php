@extends('layouts.app')
@section('tagSection')
<title>Manage Roles | Departure Cloud</title>
@endsection

@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Manage Role</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Roles</li>
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
                                <h3>Role List</h3>
                                @can('role_create', $permission)
                                    <a href="" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#modal">Create New Role</a></h1>
                                @endcan
                            </div>
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" id="modal" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <form action="{{route('role_create')}}" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="mySmallModalLabel">Create Role</h5>
                                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row">
                                                            @csrf
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="exampleFormControlInput1">Role Name</label>
                                                                    <input type="text" class="form-control" id="exampleFormControlInput1" name="role" required style="width: 300px">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer pt-3">
                                                        <button class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                @foreach($role as $data)
                                    <div class="d-flex align-items-center border px-2 py-1 justify-content-between">
                                        <h5>{{ucfirst($data->name)}}, Permissions</h5>
                                        <span>
                                            <a class="btn btn-primary btn-sm text-white" data-toggle="modal" data-target="#modal1{{$data->id}}"><i class="fa fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm delete text-white" data-id="{{ $data->id }}" data-status=""><i class="fa fa-trash"></i></a>
                                            <button class="btn btn-blue btn-sm show_hide{{$data->id}}"><i class="fa fa-plus"></i></button>
                                        </span>
                                    </div>
                                    <div class="slidingDiv{{$data->id}}" style="display: none">
                                        <div class="card-box bg-light">
                                            <form method="post" action="{{route('role_permission')}}" style="position:relative">
                                                @csrf
                                                <input type="hidden" value="{{$data->id}}" name="role_id">
                                                @foreach($permissions as $row)
                                                    <h4 class="mb-0">{{ucfirst($row->module)}}</h4>
                                                    <ul class="list-inline">
                                                        @foreach($row->sub_module as $value)
                                                            <li class="list-inline-item">
                                                                <?php $select = DB::table('permission_roles')->where('role_id', $data->id)->where('permission_id', $value->id)->get();?>
                                                                <div class="form-check form-check-primary">
                                                                    <input type="checkbox" value="{{$value->id}}" id="{{$value->id}}" class="form-check-input" name="permission_id[]" @foreach($select as $selected) checked @endforeach>&nbsp;
                                                                    <label class="form-check-label" for="{{$value->id}}">{{ucfirst($value->name)}}</label>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endforeach
                                                @can('role_edit', $permission)
                                                    <div class="col-12 text-right">
                                                        <input type="submit" class="btn btn-primary btn-sm" value="Submit" >
                                                    </div>
                                                @endcan
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" id="modal1{{$data->id}}" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <form action="{{route('role_edit')}}" method="post">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="mySmallModalLabel">Edit Role</h5>
                                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    @csrf
                                                    <div class="modal-body">

                                                        <input type="hidden" name="id" value="{{$data->id}}">
                                                        <div class="form-group">
                                                            <label for="exampleFormControlInput1">Role Name</label>
                                                            <input type="text" class="form-control" id="exampleFormControlInput1" name="role" value="{{$data->name}}" required style="width:300px">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
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

    @if($errors->any())
        <div class="modal fade" id="myModal" role="dialog" style="">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h5>@foreach($errors->all() as $error)
                                {{$error}}
                            @endforeach</h5>
                        <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
@section('footerSection')
    <script>
        $(document).ready(function () {
            // $('#myModal').modal({
            //     backdrop: 'static',
            //     keyboard: false  // to prevent closing with Esc button (if you want this too)
            // });
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    @foreach($role as $row)
        <script>
            $(document).ready(function () {
                $(".slidingDiv{{$row->id}}").hide();
                $('.show_hide{{$row->id}}').click(function (e) {
                    $(".slidingDiv{{$row->id}}").slideToggle("fast");
                    var val = $(this).text() == "-" ? "+" : "-";
                    $(this).hide().text(val).fadeIn("fast");
                    e.preventDefault();
                });
            });
        </script>
    @endforeach
    <script type="text/javascript">
        $(".delete").click(function () {
            var flag = (status == 0) ? 'active' : 'Active';
            if (confirm("Are you sure you want to delete this role?"))
                var id = $(this).data("id");
            var status = $(this).data("status");

            var token = "{{ csrf_token() }}";
            if (id) {
                $.ajax({
                    url: '/role-delete/' + id,
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
        $("#imgInp").change(function () {
            readURL(this);
        });
        $("#myElem").show().delay(4000).queue(function (n) {
            $(this).hide();
            n();
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
            -webkit-transform: translate(25%, 0);
            transform: translate(25%, 0);
        }

        .modal.show .modal-dialog {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
        }

        .modal-backdrop.show {
            opacity: .7;
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: #fff;
        }

        .li {
            list-style-type: none;
            text-decoration: underline;
        }
    </style>
    <style>
        #myElem {
            display: flex;
            justify-content: center;
            height: 100%;
            position: absolute;
            z-index: 999;
            bottom: 10px;
            width: 100%;

        }

        #success_msg {
            background: #0a3f8e;
            position: absolute;
            background: #0a3f8e;
            position: absolute;
            bottom: 46px;
            padding: 9px 42px;
            font-size: 16px;
            border-radius: 5px;
            border: 5px solid white;
        }
    </style>
@endsection
