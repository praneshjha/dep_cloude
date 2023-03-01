<!DOCTYPE html>
<html>
<head>
	  <meta charset="utf-8">
    <meta name="csrf-token" content="MUR0yUEnYY4efGTKSn4P5IGO4ufXNLI1FOiiDEKS" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
<meta content="Coderthemes" name="author"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>

<link rel="shortcut icon" href="https://www.departurecloud.com/favicon.png">
<title>Dashboard | Departure Cloud</title>
<link href="https://www.departurecloud.com/assets1/libs/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/libs/datatables/responsive.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/libs/datatables/buttons.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/libs/datatables/select.bootstrap4.css" rel="stylesheet" type="text/css"/>

<link href="https://www.departurecloud.com/assets1/libs/select2/select2.min.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/libs/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css"/>

<link href="https://www.departurecloud.com/assets1/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css"/>

<link href="https://www.departurecloud.com/assets1/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/css/icons.min.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/css/app.min.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/libs/summernote.summernote-bs4.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/css/dc_style.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/css/dc_chat.css" rel="stylesheet" type="text/css"/>
<link href="https://www.departurecloud.com/assets1/css/dc_pullit.css" rel="stylesheet" type="text/css"/>

<script src="https://www.departurecloud.com/assets1/js/vendor.min.js"></script>
</head>
<body>
	<header class="tvHeader">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="d-flex align-items-center justify-content-between">
						<h2>Departures</h2>
						<i class="fas fa-plane" style="transform: rotate(311deg);"></i>
					</div>
				</div>
			</div>
		</div>
	</header>
	<section class="tvBody">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="widget-rounded-circle card-box">
						<div class="row">
							<div class="col-12">
								<table class="table table-hover table-striped">
									<thead>
										<tr>
										<th>Date</th>
										<th>Departure To</th>
										<th>Ex</th>
										<th>Airline</th>
										<th>Total Units</th>
										<th>Booked</th>
										<th>Hold</th>
										<th>Available</th>
										<th>Status</th>
									</tr>
									</thead>
									<tbody id="tvRowData">
									@if(count($data)>0)
										@foreach( $data as $key => $row )
										<tr class="tvData">
											<td>{{$row->start_date}}</td>
											<td>{{$row->departure_to}}</td>
											<td>{{$row->from}}</td>
											<td>@if($row->air_icon != "")<img src="{{$row->air_icon}}" style="width:20px;"> @endif {{$row->airline}}</td>
											<td>{{$row->total_seat}}</td>
											<td>{{$row->booked_seat}}</td>
											<td>{{$row->hold_seat}}</td>
											<td>{{$row->available_seat}}</td>
											<td>{{$row->status}}</td>
										</tr>
										@endforeach
									@else
										<tr>
											<td colspan="9" style="text-align: center;">Data not available..</td>
										</tr>
									@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<style type="text/css">
		.container-fluid{width:100%;max-width:100%;}
		.tvHeader{background: #681f4a;padding: 10px 0;margin-bottom: 10px;color: #fff;}
		.tvHeader h2{color: #fff;margin-bottom:0;}
		.tvBody table{overflow:hidden;}
		.tvBody table thead th{background: #340822;color: #fff;font-size:1.3rem;padding:.35rem;}
		.tvBody table tr {transition: all 1s zoomIn;}
		.tvBody table tr.slide-out {transform: translateX(100%);}
		body{background:#681f4a;}
		vtBody{background:#681f4a;}
		.table-striped tbody tr:nth-of-type(odd){background-color: #500e35;}
		.table-striped tbody tr{color:#fff;background-color: #681f4a;font-size: 1.3rem;}
		.table-striped tbody tr td{padding:.35rem}
		.tvBody .card-box {background-color:#681f4a;padding:0;}
	</style>
</body>

<script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" ></script>
<script  src="https://www.departurecloud.com/js/app.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/multiselect/jquery.multi-select.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/select2/select2.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/jquery-mockjax/jquery.mockjax.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/autocomplete/jquery.autocomplete.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/bootstrap-select/bootstrap-select.min.js"></script>

<script src="https://www.departurecloud.com/assets1/libs/datatables/jquery.dataTables.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/dataTables.bootstrap4.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/dataTables.responsive.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/responsive.bootstrap4.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/dataTables.buttons.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/buttons.bootstrap4.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/buttons.html5.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/buttons.flash.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/buttons.print.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/dataTables.keyTable.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/datatables/dataTables.select.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/pdfmake/pdfmake.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/pdfmake/vfs_fonts.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://www.departurecloud.com/assets1/libs/flatpickr/flatpickr.min.js"></script>

<!-- <script src="https://www.departurecloud.com/assets1/js/pages/datatables.init.js"></script> -->

<!-- App js-->
<script src="https://www.departurecloud.com/assets1/js/app.min.js"></script>
<script src="https://www.departurecloud.com/assets1/js/dc_script.js"></script>


<script type="text/javascript">
	const rows = Array.from(document.getElementsByClassName('tvData'));
	function slideOut(row) {
	  row.classList.add('slide-out');
	}
	function slideIn(row, index) {
	  setTimeout(function() {
	    row.classList.remove('slide-out');
	  }, (index + 5) * 200);  
	}
	rows.forEach(slideOut);
	rows.forEach(slideIn);
</script>

<script type="text/javascript">
	  	var aa = "<?php echo count($data);?>";
		if(aa >= 10){
	  		var curpage = 2;
		}else{
			var curpage = 1;
		}
	  	
	  	function sendRequest(){
	      	$.ajax({
		        url: "{{route('turkey_team')}}",
		        type: 'GET',
		        data: {page:curpage},
		        success: 
		          function(data){
		          	if(data.last_page == data.current_page){
				  		curpage = 1;
				  	}else{
				  		curpage++;
				  	}
				  	var html = "";
				  	for (let val of data.data) {
			           	html += "<tr class='tvData'><td>"+val.start_date+"</td><td>"+val.departure_to+"</td><td>"+val.from+"</td><td><img src="+val.air_icon+" style='width:20px;'> "+val.airline+"</td><td>"+val.total_seat+"</td><td>"+val.booked_seat+"</td><td>"+val.hold_seat+"</td><td>"+val.available_seat+"</td><td>"+val.status+"</td></tr>";
					}

					$('#tvRowData').html(html);
					const rows = Array.from(document.getElementsByClassName('tvData'));
					function slideOut(row) {
					  row.classList.add('slide-out');
					}
					function slideIn(row, index) {
					  setTimeout(function() {
					    row.classList.remove('slide-out');
					  }, (index + 5) * 200);  
					}
					rows.forEach(slideOut);
					rows.forEach(slideIn);
		        },
		    });
		};
		$(document).ready(function(){
		 	setInterval(sendRequest,22000);
		});
</script>
</html>
