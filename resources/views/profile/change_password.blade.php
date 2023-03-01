@extends('layouts.app')
@section('tagSection')
<title>Change Password | Departure Cloud</title>
@endsection
@section('content')

    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Change Password</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Password</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="general-info" class="section general-info" action="{{route('passwrod_update')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="info">
                                        <div class="row">
                                            <div class="col-lg-4 m-auto ">
                                                <div class="form">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label for="fullName">E-Mail Address</label>
                                                            <div class="input-group mb-2 mr-sm-2">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text"><i class="far fa-envelope" id="old_eye"></i></div>
                                                                </div>
                                                                <input type="email" class="form-control input-text-form" id="email" value="{{auth::user()->email}}" name="email" autocomplete="off" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label for="fullName">Old Password</label><span class="collorRed" id="oldPass" style="margin-left: 20px;"></span>
                                                            <div class="input-group mb-2 mr-sm-2">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text"><i class="fas fa-lock" id="new_pass"></i></div>
                                                                </div>
                                                                <input type="old_password" class="form-control input-text-form" id="old_password" name="old_password" autocomplete="off">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label for="fullName">New Password</label><span class="collorRed" id="newPass" style="margin-left: 20px;"></span>
                                                            <div class="input-group mb-2 mr-sm-2">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text"><i class="fas fa-lock" id="new_pass"></i></div>
                                                                </div>
                                                                <input type="password" class="form-control input-text-form" id="password" name="new_password" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label for="fullName">Confirm Password</label><span class="collorRed" id='message' style="margin-left: 20px;"></span>
                                                            <div class="input-group mb-2 mr-sm-2">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text"><i class="fas fa-lock" id="new_pass"></i></div>
                                                                </div>
                                                                <input type="password" class="form-control input-text-form" id="confirm_password" name="confirmed" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 text-center">
                                                            <button type="submit" class="btn btn-info btn-sm" id="change">Change Password</button>
                                                        </div>
                                                @if(session()->has('message'))
                                                    <div class="alert text-light alert-dismissible fade show " id="myElem" role="alert" style="">
                                                        <div id="success_msg" style="">
                                                            {{ session()->get('message') }}
                                                        </div>
                                                    </div>
                                                @elseif(session()->has('error'))
                                                    <div class="alert text-light alert-dismissible fade show " id="myElem" role="alert" style="">
                                                        <div id="success_msg" style="">
                                                            {{ session()->get('error') }}
                                                        </div>
                                                    </div>
                                                @endif
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
        </div>
    </div>
    
    <style type="text/css">
        .collorRed {
            color: red;
        }
    </style>
@endsection
@section('footerSection')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#change').click(function () {
                //e.preventDefault();
                var password = $('#password').val();
                if (password == "") {
                    $("span#newPass").html('This field is required!');
                    $("input#password").focus();
                    return false;
                }
                var confirm_password = $('#confirm_password').val();
                if (confirm_password == "") {
                    $("span#newPass").hide();
                    $("span#message").html('This field is required!');
                    $("input#confirm_password").focus();
                    return false;
                }

                if (confirm_password != password) {
                    $("span#message").html('Confirm password does not match!');
                    $("input#confirm_password").focus();
                    return false;
                }
            });
        });
    </script>
    <script>
        $('#confirm_password').on('keyup', function () {
                if ($('#password').val() == $('#confirm_password').val()) {
                    $('#change').prop('disabled', false);
                    $('#message').html('<i class="fa fa-check" aria-hidden="true"></i> Matching').css('color', 'green');
                } else {
                    $('#message').html('<i class="fa fa-times" aria-hidden="true"></i> Not Matching').css('color', 'red');
                    $('#change').prop('disabled', true);

                }
            }
        );
        $("#myElem").show().delay(5000).queue(function (n) {
            $(this).hide();
            n();
        });
    </script>
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