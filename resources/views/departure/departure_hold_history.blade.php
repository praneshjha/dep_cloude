@extends('layouts.app')
@section('tagSection')
<title>Hold History | Departure Cloud</title>
@endsection
@section('content')
    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">Hold History</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Hold History</li>
                            </ol>
                        </div>
                    </div>
                </div>
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <nav>
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="{{route('departure_booking_history',$id)}}" class="nav-link"> Confirmed</a></li>
                                        <li class="nav-item"><a href="{{route('departure_hold_history',$id)}}" class="nav-link active"> Hold </a></li>
                                        <a href="{{route('all_departure_hold_history')}}" class="btn btn-info btn-sm float-right" style="position:absolute;top: 0;right: 0;">All Hold</a>
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead >
                                              <tr>
                                              <th>Sl.</th>
                                              <th>Dep. Name</th>
                                              <th>Buyer Name</th>
                                              <th>Hold Date & time</th>
                                              <th>Hold Units</th>
                                              <th>Total Price</th>
                                              <th>Action</th>
                                              </tr>
                                         </thead>
                                          <tbody>
                                          
                                          @foreach($book_date as $key=>$row)
                                            <tr>
                                              <td>{{$key+1}}</th>
                                                <td><a href="{{route('departure_details',$id)}}">{{$row->departure_name}}</a></td>
                                              <td>{{$row->name}}</td>
                                              <td>{{date('d-M-Y h:i a', strtotime($row->booked_value->created_at."+5 hours +30 minutes"))}}</td>
                                              <td>{{$row->booked_seat}}
                                              </td>
                                              <td>{{$row->currency->currency_symbol}}   {{$row->price}}
                                              </td>
                                              <td>
                                             <a href="{{url('/departures-hold-history-details/'.$row->unique_id)}}" class="" title="More Details"><i class="fa fa-eye"></i></a>
                                                  
                                              </td>
                                            </tr>
                                          @endforeach
                                          
                                          </tbody>
                                      </table>
                                      {{$book_date->links()}}
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

@endsection