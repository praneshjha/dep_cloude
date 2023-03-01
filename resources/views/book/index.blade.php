@extends('layouts.app')

@section('content')
@section('headnav') 
<link href="{{('assets/css/components/custom-modal.css')}}" rel="stylesheet" type="text/css" />
<li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
<li class="breadcrumb-item active"><a href=""> Book </li>
@endsection
<div id="content" class="main-content">
@if (\Session::has('msg'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('msg') !!}</li>
        </ul>
    </div>
@endif
            <div class="layout-px-spacing">
                <div class="chat-section layout-top-spacing">
                <h4>Stack Forms</h4>   
                <table class="table">
                  <thead>
                    <tr>
                     <th>Name</th>
                     <th>Days/Nights</th>
                     <th>Travel Date</th>
                     <th>Total</th>
                     <th>Booked</th>
                     <th>Available</th>
                     <th>Hold</th>
                     <th>Action</th>
                     <th>Hold</th>
                     <th>Release</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($book as $row)
                    <?php
                       $today = date("Y-m-d");
                       $date1=date_create($today);
                       $date2=date_create($row->start_date);
                       $diff=date_diff($date1,$date2);
                       $date = $diff->format("%R%a");
                    ?>
                   <tr>
                    <td>{{$row->title}}</td>
                    <td>{{$row->no_of_days}}/{{$row->no_of_nights}}</td>
                    <td>{{$row->start_date}}</td>
                    <td>{{$row->total_seat}}</td>
                    <td>{{$row->booked_seat}}</td>
                    <td>{{($row->total_seat-$row->booked_seat)-$row->hold_seat}}</td>
                    <td>{{$row->hold_seat}}</td>
                    <td>
                    <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#m{{$row->id}}">Book Now</a></td>
                      <!-- Modal -->
                      <div class="modal fade" id="m{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-sm" role="document">
                      <div class="modal-content">
                      <div class="modal-body">
                      <form action="{{route('store')}}" method="post">
                      @csrf
                      <input type="hidden" name="id" value="{{$row->id}}">
                      <input type="hidden" name="total_available" value="{{$row->booked_seat}}">
                      <div class="form-group">
                      <label for="email">Book Seat</label>
                      <input type="number" class="form-control" id="book" name="book" required>
                      </div>
                      </div>
                      <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Book</button>
                      </form>
                    </div>
                   </div>
                 </div>
                </div>
                <td><a href="" class="btn btn-danger btn-sm @if($date < +21) disabled @endif" data-toggle="modal" data-target="#n{{$row->id}}">Hold</a>
                <!-- Modal -->
                <div class="modal fade" id="n{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-sm" role="document">
                      <div class="modal-content">
                      <div class="modal-body">
                      <form action="{{route('hold')}}" method="post">
                      @csrf
                      <input type="hidden" name="id" value="{{$row->id}}">
                      <input type="hidden" name="total_hold" value="{{$row->hold_seat}}">
                      <div class="form-group">
                      <label for="email">Book Seat</label>
                      <input type="number" class="form-control" id="hold" name="hold" required>
                      </div>
                      </div>
                      <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Hold</button>
                      </form>
                    </div>
                   </div>
                 </div>
                </div>
                </td>
                <td><a href="" class="btn btn-success btn-sm @if($date <= +21) disabled @endif" data-toggle="modal" data-target="#n{{$row->id}}">Release</a></td>
                  </tr>
                   @endforeach
                  </tbody>
                </table>
                {{$book->links()}}   
             </div>
@endsection