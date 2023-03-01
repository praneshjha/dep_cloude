<div class="row">
    <div class="col-md-12"  > 
        <div class="row mt-3">
            @if(count($departures)> 0 )
                <div class="col-md-12">
                    <div class="card-box">
                        <div class="table-responsive" id="TableView">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Departure Name</th>
                                    <th>Owner</th>
                                    <th>Date</th>
                                    <th>Duration</th>
                                    <th>To</th>
                                    <th>Ex</th>
                                    <th>Avl. Units</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody id="all_dep_appends_table">
                                @foreach( $departures as $key => $departure )
                                    <?php
                                    $new_time = ($departure->hold_duration) + 5;
                                    $hold_till = DB::table('hold_departures')->where('departure_id', $departure->id)->get();
                                    if (count($hold_till) > 0) {
                                        foreach ($hold_till as $row) {
                                            if ($row->departure_id == $departure->id) {
                                                $hold = $row->hold_till;
                                            }
                                        }
                                    } else {
                                        $hold = 0;
                                    }
                                    $today = date("Y-m-d");
                                    $date1 = date_create($today);
                                    $date2 = date_create($departure->start_date);
                                    $diff = date_diff($date1, $date2);
                                    $date = $diff->format("%R%a");

                                    if (($hold < $date) && ($departure->available_seat > 0)) {
                                        $popup = '.bd-example-modal-sm';
                                    } else {
                                        $popup = 0;
                                    }
                                    ?>
                                    <tr>
                                        <td>{{ $departure->dep_id }}</td>
                                        <td><a href="{{route('all_departure_details',$departure->id)}}" title="{{$departure->title}}" data-toggle="tooltip">{{$departure->title}}</a></td>
                                        <td><a href="{{url('profile/'.$departure->company_url)}}" class="userprofileName">{{$departure->departure_ownner}}</a></td>
                                        <td>{{date('d M, Y', strtotime($departure->start_date))}}</td>
                                        <td>
                                            @if($departure->no_of_nights == null || $departure->no_of_days == null)
                                                
                                            @else
                                                {{$departure->no_of_nights}}N/{{$departure->no_of_days}}D
                                            @endif
                                        </td>
                                        <td>{{strtok($departure->from, ",")}}</td>
                                        <td>{{$departure->ending_at}}</td>
                                        <td>
                                            @if(($departure->total_seat)-($departure->hold_sum + $departure->book_sum) > 0)
                                                {{($departure->total_seat)-($departure->hold_sum + $departure->book_sum)}}
                                            @endif
                        
                                        </td>
                                        <td>
                                            @foreach($departure->price as $price)
                                                {{$price->currency_symbol}}  {{$price->price}}
                                            @endforeach
                                        </td>
                                        <td>
                                            @if(Auth::user()->main_user_type == 2)
                                            <a href="javascript:void(0)" id="dep-{{$departure->id}}" title="chat" class="chat_data tooltipbubble"><i class="far fa-comment-dots"></i></a>
                                            <input type="hidden" name="dep_val_{{$departure->id}}" id="dep-{{$departure->id}}-val" value="{{$departure->id}}">
                                                <a href="{{route('all_departure_details',$departure->id)}}" title="View Deprature"><i class="fa fa-eye"></i></a>
                                                <a href="{{route('departure_edit',$departure->id)}}" title="Edit Departure"><i class="fa fa-edit"></i></a>
                                                  @if(($hold < $date))
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#hold_modal" title="Hold Units" class="tooltipbubble" onclick="openHoldModal({{ $departure}})"><i class="fas fa-pause"></i></a>      
                                                @else
                                                 <a href="javascript:void(0);" title="This is Departure Beyond Hold Date" disabled class="tooltipbubble" style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
                                                @endif

                                        <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0) #booking_modal @endif"
                                           title="Book Units" class="tooltipbubble" onclick="openBookingModal({{ $departure}})"><i class="far fa-calendar-check"></i></a>
                                                <!-- <a href="javascript:void(0);" data-toggle="modal" data-target="@if(($hold < $date)).bd-example-modal-sm{{$departure->id}} @endif" title="Hold Units"><i class="fas fa-pause"></i></a>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0).bd-example-modal-sm{{$departure->id}}b @endif" title="Book Units"><i class="far fa-calendar-check"></i></a> -->

                                            @else
                                            <input type="hidden" name="dep_val_{{$departure->id}}" id="dep-{{$departure->id}}-val" value="{{$departure->id}}">
                                            <a href="javascript:void(0)" id="dep-{{$departure->id}}" title="chat" class="chat_data tooltipbubble"><i class="far fa-comment-dots"></i></a>
                                                <a href="{{route('all_departure_details',$departure->id)}}" title="View Deprature"><i class="fa fa-eye"></i></a>

                                                 @if(($hold < $date))
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#hold_modal" title="Hold Units" class="tooltipbubble" onclick="openHoldModal({{ $departure}})"><i class="fas fa-pause"></i></a>      
                                                @else
                                                 <a href="javascript:void(0);" title="This is Departure Beyond Hold Date" disabled class="tooltipbubble" style="color:#bdb1b1;cursor: no-drop;"><i class="fas fa-pause"></i></a>
                                                @endif

                                        <a href="javascript:void(0);" data-toggle="modal" data-target="@if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0) #booking_modal @endif"
                                           title="Book Units" class="tooltipbubble" onclick="openBookingModal({{ $departure}})"><i class="far fa-calendar-check"></i></a>

                                            @endif
                                        </td>
                                        <td>
                                            @if((($departure->total_seat)-($departure->hold_sum + $departure->book_sum)) > 0)
                                                <span class="badge bg-success">OPEN</span>
                                            @else
                                                <span class="badge bg-danger">SOLDOUT</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12" style="text-align:center;">Departure not Found</div>
            @endif 
        </div>
    </div>

    <div class="col-md-12 flexbox paginate_loader" style="display:none;">
              <div class="multi-spinner-container">
                  <div class="multi-spinner">
                    <div class="multi-spinner">
                      <div class="multi-spinner">
                        <div class="multi-spinner">
                          <div class="multi-spinner">
                            <div class="multi-spinner">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
    </div>
</div>
