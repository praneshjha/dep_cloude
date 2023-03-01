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
                                            <span class="badge bg-success">OPEN</span>
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
