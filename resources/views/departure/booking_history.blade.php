@extends('layouts.app')
@section('tagSection')
<title>Booking History | Departure Cloud</title>
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
                                <li class="breadcrumb-item active">Booking History</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Booking History</h4>
                    </div>
                </div>
            </div>

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
                                         <th>Buyers</th>
                                         <th>Buyers Email</th>
                                         <th>Buyers Phone</th>
                                         <th>Departure Name</th>
                                         <th>Departure Owner</th>
                                         <!-- <th>Days/Nights</th>
                                         <th>From - To</td>
                                         <th>Travel Date</th> -->
                                         <th>Booking Units</th>
                                         <th>Booking Date</th>
                                         <th>Price</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                       
                                        <?php
                                           $today = date("Y-m-d");
                                           $date1=date_create($today);
                                           $date2=date_create();
                                           $diff=date_diff($date1,$date2);
                                           $date = $diff->format("%R%a");
                                        ?>
                                       @foreach($book as $key => $row)
                                       <tr>
                                          <td>{{$key+1}}</td>
                                          @foreach($row->company as $value)
                                          <td>{{$value->company_name}}</td>
                                          <td>{{$value->email}}</td>
                                          <td>{{$value->mobile}}</td>
                                          @endforeach
                                          <td>{{$row->title}}</td>
                                          <td>{{$row->company_name}}</td>
                                          <!-- <td>{{$row->no_of_days}}/{{$row->no_of_nights}}</td>
                                          <td>{{$row->from}}/{{$row->ending_at}}</td>
                                          <td>{{$row->start_date}}</td> -->
                                          <td>{{$row->booked_seat}}</td>
                                          <td>{{date('d M, Y', strtotime($row->date))}}</td>
                                          <td>Rs.{{$row->price_inr}}<br>$ {{$row->price_usd}} </td>
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

@endsection