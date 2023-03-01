@extends('layouts.app')
@section('tagSection')
<title>My Hold Departures | Departure Cloud</title>
@endsection
@section('content')

<link href="{{('assets/css/components/custom-modal.css')}}" rel="stylesheet" type="text/css" />


    <div class="wrapper">
        <div class="wrapperOverlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box mt-3 mb-3 d-flex align-items-center justify-content-between">
                        <h4 class="page-title">
                            My Hold
                        </h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Departures Cloud</a></li>
                                <li class="breadcrumb-item active">Hold</li>
                            </ol>
                        </div>
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
                                        <li class="nav-item"><a href="{{route('my_booking')}}" class="nav-link  @if(Route::currentRouteName() == 'my_booking')  @endif"> Confirmed</a></li>
                                        <li class="nav-item"><a href="{{route('my_holding')}}" class="nav-link active @if(Route::currentRouteName() == 'my_holding')  @endif"> Hold </a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                           <th>Dep. ID</th>
                                           <th>Dep. Name</th>
                                         
                                           <th>No. of N/D</th>
                                          
                                           <th>Hold Date</th>
                                           <th>Hold Till</th>
                                           <th>Hold Unit</th>
                                           <th>Total Price</th>
                                           <th style="width: 10%;">Action</th>
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
                                             @foreach($myhold as $key => $row)
                                             <tr>
                                              <td>{{$key+1}}</th>
                                                <td>{{$row->departure_name}}</td>
                                              <td>{{$row->nights}}/{{$row->days}}</td>
                                              <td>{{date('d M, Y h:i a', strtotime($row->book_value->created_at."+5 hours +30 minutes"))}}</td>
                                              <td>{{date('d M, Y h:i a', strtotime($row->book_value->auto_release."+2 minutes"))}}</td>
                                              <td>{{$row->booked_seat}}
                                              </td>
                                              <td>{{$row->currency->currency_symbol}}   {{$row->price}}
                                              </td>
                                              <td>
                                              <a href="{{url('/departures-hold-history-details/'.$row->book_value->unique_id)}}" class="" title="More Details"><i class="fa fa-eye"></i></a> 
                                                  || 
                                                <a href="javascript:void(0);" class="releaseHold" data-id="{{$row->unique_id}}" data-status="" title="Release"><i class="fa fa-undo"></i></a>
                                                ||
                                                <a href="javascript:void(0);" class="disableDepartue" data-id="{{$row->book_value->unique_id}}" data-status="" title="Confirm Booking"><i class="fa fa-check-circle"></i></a>
                                              </td>
                                            </tr>
                                             @endforeach
                                             
                                            </tbody>
                                      </table>
                                      {{$myhold->links()}}
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
    $(".disableDepartue").click(function () {
        if (confirm("Are You sure, Want to confirm this booking?"))
        var id = $(this).data("id");
        var status = $(this).data("status");
        //var flag = (status == 0)?'Buyer':'Buyer & Supplier';
        var token = "{{ csrf_token() }}";
        if(id){

            $.ajax({
              
                url: '/departure-confirm/' + id,
                type: 'POST',
                data: {
                  "id": id,
                  "_token": token,
                },
                success: function (data) {
                    //console.log(data);
                    //alert('Departure has been published successfully. Details will be reviewed and approved by the admin soon!');
                    window.location.href = "{{url('/my-bookings')}}";
                }
            });
        }
    });

    $(".releaseHold").click(function () {
        if (confirm("Are You sure, You want to release?"))
        var id = $(this).data("id");
        var status = $(this).data("status");
        var token = "{{ csrf_token() }}";
        if(id){
            $.ajax({
                url: '/hold/departure/release/' + id,
                type: 'POST',
                data: {
                  "id": id,
                  "_token": token,
                },
                success: function (data) {
                    window.location.href = "{{url('/my-holdings')}}";
                }
            });
        }
    });
</script>
@endsection