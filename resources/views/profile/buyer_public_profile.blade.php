@extends('layouts.app')
@section('tagSection')
<title>Public Profile | Departure Cloud</title>
@endsection
@section('content')
    <?php
    $user = DB::table('users')->where('tenant_id', Auth::user()->tenant_id)->first();
    ?>
    <style type="text/css">
        .badge-primary {
            margin: 5px;
        }

        .search-list {
            list-style-type: none;
            margin-left: -10;
            margin-top: 5px;
        }

    </style>
    <style>
      .img-fixed {
            object-fit: unset;
        }
        dd {
            font-size: 1rem;
        }
    </style>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Buyer Profile</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @if(session('message'))
                <div class="alert text-light alert-dismissible fade show " id="myElem" role="alert" style="">
                    <div id="success_msg" style="">
                        {{ session('message') }}
                    </div>

                </div>
        @endif
     
            <div class="row">
                <div class="col-md-12">
                    <figure class="profileCover">
                        <img src="@if(isset($user->banner_image)) {{ asset('BannerImage/' . $user->banner_image) }} @else {{asset('/assets1/images/cover.jpg')}} @endif" alt="profileCover" alt="profileCover" class="img-fixed">
                    </figure>
                </div>
                <div class="col-md-12 ">
                    <div class="d-md-flex align-items-center justify-content-between profilePicSection">
                        <div class="d-flex align-items-center">
                            <figure>
                                <img src="@if(isset($user->logo)){{ asset('companyLogo/' . $user->logo) }} @else {{asset('images/no-image.png')}} @endif" class="img-fixed" alt="profile-image">
                            </figure>
                            <div class="ml-2">
                                <h5 class="text-blue mb-1 mt-2">{{$user->name}}</h5>
                                <h3 class="mb-2 mt-0">{{$user->company_name}}</h3>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-3"></div>
                <div class="col-lg-6 col-xl-6 pb-4">
                    <div class="card-box position-relative">
                        <h3>Company Details</h3>

                        <dl>
                            <dt><i class="fas fa-user"></i>Contact Name</dt>
                            <dd>{{$user->name}}</dd>
                            <dt><i class="fas fa-phone fa-rotate-90"></i> Mobile</dt>
                            <dd>{{$user->mobile}}</dd>
                            <dt><i class="fas fa-envelope"></i> Email</dt>
                            <dd><a href="mailto:{{$user->email}}">{{$user->email}}</a></dd>
                            <dt><i class="fas fa-money-bill-wave"></i> Currency</dt>
                            <dd>{{$user->currency_symbol}} @if($user->currency_symbol)({{$user->currency_code}}) @endif</dd>
                            <dt><i class="fas fa-map-marker-alt"></i> Address</dt>
                            <dd>
                                @if(isset($user->address))
                                    {{ucfirst($user->address)}}<br>
                                @endif
                                @if(isset($user->city))
                                    {{ucfirst($user->city)}},
                                @endif
                                @if(isset($user->state))
                                    {{ucfirst($user->state)}},<br>
                                @endif
                                @if(isset($user->country))
                                    {{ucfirst($user->country)}} -
                                @endif
                                @if(isset($user->pin))
                                    {{ucfirst($user->pin)}}
                                @endif
                            </dd>
                        </dl>
                    
                    </div>
                </div>
               
            </div>
        </div>
    </div>

@endsection