@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<?php //dd($customer);?>
<div class="container-fluid">
	<div class="container">
		<!-- Title -->
		<div class="d-flex justify-content-between align-items-center py-3">
			<h2 class="h5 mb-0"><a href="#" class="text-muted"></a> Customer Details</h2>
		</div>
		<!-- Main content -->
		<div class="row">
			<div class="col-lg-8">
				<!-- Details -->
				<div class="card mb-4">
					<div class="card-body">
						<div class="row mb-5">
							<input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
							<input type="hidden" name="contact_id" id="contact_id" value="{{$customer->contact_id}}">
							<div class="row">
								<div class="text-muted col-md-4">
									<h5>{{$customer->firstName}} {{$customer->lastName}}
										@if ($customer->status == 1)
										<span class="fa fa-edit" onclick="updatePriceColumn(0)"></span>
										@endif
									</h5>
									<div id="first-last-name" class="d-none">
										<div><input type="text" name="first_name" value="{{$customer->firstName}}">
										</div>
										<div class="mt-3"><input type="text" name="last_name"
												value="{{$customer->lastName}}"></div>
										<div class="mt-3"><button type="button" value="update"
												onclick="updatePriceColumn(3)">Update</button></div>
									</div>
								</div>
								@if ($customer->status == 1)

								<div class="col-md-6"><b>Pricing:</b> {{$customer->priceColumn}} </a>
									<div class="spinner-border d-none" role="status"
										style="left: 50% !important;margin-left: -25em !important;" id="spinner2">
										<span class="sr-only">Activating...</span>
									</div>
								</div>
								@endif


								<?php 
		              			if ($customer->status == 1) {
		              				$status = 'Active';
		              			}
		              			else {
		              				$status = 'Inactive';
		              			}
		              		?>
								@if($customer->status != 1)
								<div class="col-md-6">
								</div>
								<div class="col-md-2"><button class="btn btn-primary" type="button"
										onclick="updateContact()">Activate</button>
								</div>
								@else
								<div>
									<span class="badge bg-success">{{$status}}</span>
								</div>
								@endif
								<div class="spinner-border d-none" role="status" style="left: 50% !important;
    margin-left: -25em !important;" id="spinner">
									<span class="sr-only">Activating...</span>
								</div>
								<div class="col-md-12">
									<b>Company:</b> {{$customer->company}}
								</div>
								<div class="col-md-12 mt-2">
									<b>Website:</b> {{$customer->website}}
								</div>
								<div class="col-md-12 mt-2">
									{{$customer->email}}
								</div>
								<div class="col-md-12 mt-2">
									{{$customer->phone}}
								</div>
								<div class="col-md-12 mt-5">
									<div class="row">
										<div class="col-md-6">
											<h3>Billing Address</h3>
											<div class="col-md-10 bg-light">
												<div>
													{{$customer->postalAddress1}}
												</div>
												<div>
													{{$customer->postalAddress2}}
												</div>
												<div>
													{{$customer->postalPostCode}}, {{$customer->postalState}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<h3>Delivery Address</h3>
											<div class="col-md-10 bg-light">
												<div>
													{{$customer->postalAddress1}}
												</div>
												<div>
													{{$customer->postalAddress2}}
												</div>
												<div>
													{{$customer->postalPostCode}}, {{$customer->postalState}}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
				<!-- Payment -->
				<div class="card mb-4">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-12">
								<h2 class="h5 mb-0"><a href="#" class="text-muted"></a>Order History</h2>
								<table class="table">
									<tr>
										<th>Order #</th>
										<th>Date Created</th>
										<th>Status</th>
										<th>Total</th>
										<th>Ref#</th>
									</tr>
									@foreach($customer_orders as $customer_order)
									<tr>
										@if($customer_order->order_id)
										<td>
											{{$customer_order->order_id}}

										</td>
										@else
										<td class="badge bg-danger">Pending Approval</td>
										@endif
										<?php  $createdDate = $customer_order->created_at;
        							$formatedDate = $createdDate->format('F j, Y');
        							?>
										<td>

											{{$formatedDate}}
										</td>
										<td>
											{{$customer_order->status}}
										</td>
										<td>
											{{$customer_order->total}}
										</td>
										<td>
											<a href="{{ url('admin/order-detail/'.$customer_order->id) }}">{{$customer_order->reference}}
										</td>
									</tr>
									@endforeach
								</table>

							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<!-- Customer Notes -->
				<div class="card mb-4">
					<div class="card-body">
						<h3 class="h6"><strong>Customer Notes</strong></h3>

						<div>{{$customer->notes}}</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
	function addComment() {
    		var comment = $( "#comment" ).val();
    		var order_id = $( "#order_id" ).val();
    		jQuery.ajax({
        		url: "{{ url('admin/order-comments') }}",
        		method: 'post',
        		data: {
            		"_token": "{{ csrf_token() }}",
            		"comment" : comment,
            		"order_id": order_id
        		},
        		success: function(response){
        			window.location.reload();
    			}
    		});
    	}
    	function updateStatus() {
    		var status = $( "#status" ).val();
    		var order_id = $( "#order_id" ).val();
    		jQuery.ajax({
        		url: "{{ url('admin/order-status') }}",
        		method: 'post',
        		data: {
            		"_token": "{{ csrf_token() }}",
            		"status" : status,
            		"order_id": order_id
        		},
        		success: function(response){
        			window.location.reload();
    			}
    		});
    	}
    	function updateContact() {
    		var contact_id = $( "#customer_id" ).val();
    		$('#spinner').removeClass('d-none');
    		jQuery.ajax({
        		url: "{{ url('admin/customer-activate') }}",
        		method: 'post',
        		data: {
            		"_token": "{{ csrf_token() }}",
            		"contact_id": contact_id
        		},
        		success: function(response){
        			console.log(response);
        			if (response.success == true) {
        				
        			 setInterval('location.reload()', 7000);
        			 //location.reload();
        			}
        			if (response.success == false) {
        				console.log(response.msg);
        				setInterval('location.reload()', 7000);
        			}

    			}
    		});
    	}

    	function updatePriceColumn(showSpinner) {
    		if (showSpinner == 2 ) {
    			$('#spinner').removeClass('d-none');
    			
    			var priceCol = $( "#priceCol" ).val();
    			var contact_id = $( "#contact_id" ).val();
    			
    		}
    		if (showSpinner == 1) {
    			$('#priceCol').removeClass('d-none');

    		}
    		else {
    			if (showSpinner == 0) {
    				$('#first-last-name').removeClass('d-none');
    			}

    			if (showSpinner == 3  ) {
    				var contact_id = $( "#contact_id" ).val();
    				$('#spinner').removeClass('d-none');
    				var first_name = $("input[name=first_name]" ).val();
    				var last_name = $("input[name=last_name]" ).val();
    				var contact_id = $( "#contact_id" ).val();
				}
			if (showSpinner != 0) {
    	
    		jQuery.ajax({
        		url: "{{ url('admin/update-pricing-column') }}",
        		method: 'post',
        		data: {
            		"_token": "{{ csrf_token() }}",
            		"contact_id": contact_id,
            		"pricingCol": priceCol,
            		"first_name": first_name,
            		"last_name" : last_name
        		},
        		
        		success: function(response){
        			console.log(response);
        			if (response.success == true) {
        				console.log('yes');

        			$('#spinner').addClass('d-none');
        			 setInterval('location.reload()', 1000);
        			 //location.reload();
        			}
        			if (response.success == false) {
        				console.log(response.msg);
        				setInterval('location.reload()', 1000);
        			}

    			}

    		});
    	}
    	}
    }
</script>
@stop