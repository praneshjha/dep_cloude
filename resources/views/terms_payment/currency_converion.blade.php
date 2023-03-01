@extends('layouts.app')
@section('tagSection')
<title>Currency Conversion | Departure Cloud</title>
@endsection
@section('content')
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Conversion</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Currency Conversion (INR to USD)</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                <h3>Currency Conversion
                                </h3>
                            </div>
                          <form action="{{route('currency_converion_update')}}" class="ml-5" method="post">
                                  @csrf
                                  <div class="row">
                                      <div class="col-lg-3 col-md-3 col-sm-3">
                                          <div class="form-group">
                                                <label for="email">Dollor USD:</label>
                                                <input type="number" Value="1" class="form-control" readonly> 
                                          </div>
                                      </div>
                                          <div class="col-lg-3 col-md-3 col-sm-3">
                                          <div class="form-group">
                                                <input type="hidden" name="id" value="{{$data->id}}">
                                                <label for="email">Rupee INR:</label>
                                                <input type="number" name="currency_conversion" class="form-control" value="{{$data->indian_currency}}" required> 
                                          </div>
                                      </div>
                                   </div>
                                    <button class="btn btn-primary btn-sm" type="submit"> Update</button> 
                                    @if(\Session::has('msg'))
                                    <span class="text-success">{{\Session::get('msg')}}
                                    <!-- <button class="btn btn-primary user-change pull-right" data-id="" data-status=""> Approve</span> -->
                                    @endif
                            </form> 
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footerSection')

@endsection