@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
	<div class="container">
		<!-- Title -->
		<div class="d-flex justify-content-between align-items-center py-3">
			<h2 class="h5 mb-0"><a href="#" class="text-muted"></a> Order #{{$order->id}}</h2>
		</div>
		<!-- Main content -->
		<div class="row">
			<div class="col-lg-8">
				<!-- Details -->
				<div class="card mb-4">
					<div class="card-body">
						<div class="mb-3 d-flex justify-content-between">
							<div>
								<span class="me-3">{{$formatedDate}}</span>
							</div>

							<div class="d-flex">
								<button class="btn btn-link p-0 me-3 d-none d-lg-block btn-icon-text"><i
										class="bi bi-download"></i> <span class="text">Invoice</span></button>
								<div class="dropdown">
									<button class="btn btn-link p-0 text-muted" type="button" data-bs-toggle="dropdown">
										<i class="bi bi-three-dots-vertical"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Edit</a></li>
										<li><a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print</a>
										</li>
									</ul>
								</div>
							</div>
						</div>

						<form method="POST" id="order_status" name="order_status">
							<div>
								<span class="me-3">Order Status</span>
							</div>
							<?php $status = $order->status;
	            				if ($status == 'DRAFT') {
	            					$selected = 'selected';
	            				}
	            				else {
	            					$selected = '';
	            				}
	            				if($status == 'APPROVED') {
	            					$selected = 'selected';
	            				}
	            				else {
	            					$selected = '';
	            				}
	            				if ($status == 'VOID'){
	            					$selected = 'selected';
	            				}
	            				else {
	            					$selected = '';
	            				}
	            			?>
							<select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example"
								id="status">
								<!-- @foreach($statuses as $status)
	  							
	  							@endforeach -->
								<option value="0" {{$selected}}>DRAFT
								</option>
								<option value="1" {{$selected}}>APPROVED
								</option>
								<option value="2" {{$selected}}>VOID
								</option>

							</select>
							<div class="row mb-5">
								<div class="col-sm-2">
									<input class="btn btn-primary btn-sm" type="button" value="Update"
										onclick="updateStatus()">
								</div>
						</form>
						<form>
							@csrf
							@if($order->isApproved == 2)
							<button type="button" class="btn btn-danger btn-sm" disabled>Cancel Order</button>
							@elseif($order->isApproved == 1)
							<div class="col-md-12" style=";
							">
								<button type="button" class="btn btn-secondary btn-sm" disabled>
									Cancel Order
								</button>
							</div>
							@else
							<div class="col-md-12">
								<input type="hidden" value="{{$orderitems[0]['order_id']}}" id="order_id">
								<input class="btn btn-danger btn-sm" type="button" value="Cancel Order"
									onclick=" cancelOrder(); addComment(0);">
							</div>
							<div class=" spinner-border d-none" role="status" id="spinner">
								<span class="sr-only" style="margin-left: 227px">Activating...</span>
							</div>
						</form>
						@endif
						<form>
							@csrf
							@if($order->isApproved == 1)
							<div class="col-md-12" style="margin-top: -31px;
							 margin-left: 122px;">
								<button type="button" class="btn btn-secondary btn-sm" disabled>
									Fullfilled
								</button>
							</div>
							@elseif ($order->isApproved == 2)
							<div class="col-md-12" style="margin-left: 122px;
							margin-top: -29px;">
								<button type="button" class="btn btn-danger btn-sm" disabled>
									Fullfilled
								</button>
							</div>
							@else
							<div class="col-md-12" style="margin-left: 50px;">
								<input class="btn btn-primary btn-sm" type="button" value="Fullfill Order"
									onclick="fullFillOrder()">
							</div>
							<div class="spinner-border d-none" role="status" id="spinner">
								<span class="sr-only" style="margin-left: 227px">Activating...</span>
							</div>
							@endif
						</form>
					</div>
					<table class="table">
						<tr>
							<th>Line Items</th>
							<th>Quantity</th>
							<th>Totals</th>
						</tr>
						<tbody>
							@foreach($orderitems as $item)
							<tr>
								<td>
									<div class="d-flex mb-2">
										<div class="flex-shrink-0 mx-4">
											<img src="{{$item->product->images}}" alt="" width="35" class="img-fluid">
										</div>
										<div class="flex-lg-grow-1 ms-3">
											<h6 class="small mb-0"><a href="#"
													class="text-reset">{{$item->Product->name}}</a></h6>
										</div>
									</div>
								</td>
								<td class="ms-2">{{$item->quantity}}</td>
								<td class="text-end">${{$item->price}}</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2">Subtotal</td>
								<td class="text-end">${{$order->total}}</td>
							</tr>
							<tr>
								<td colspan="2">Shipping</td>
								<td class="text-end">$0.00</td>
							</tr>
							<tr class="fw-bold">
								<td colspan="2"><strong>GRAND TOTAL</strong></td>
								<td class="text-end">${{$order->total}}</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- Payment -->
			<div class="card mb-4">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-6">
							<h3 class="h6">Payment Method</h3>
							<span>{{$order->paymentTerms}}</span></p>
						</div>
						<div class="col-lg-6">
							<h3 class="h6">Billing address</h3>
							<address>
								<strong>{{$customer->firstName}}&nbsp;{{$customer->lastName}}</strong><br>
								{{$customer->postalAddress1}}<br>
								{{$customer->postalAddress2}}<br>
								{{$customer->postalCity}}, {{$customer->state}}<br>
								<abbr title="Phone">P:</abbr> ({{$customer->phone}})
							</address>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<!-- Customer Notes -->
			<div class="card mb-4">
				<div class="card-body">
					<h3 class="h6" style="margin-bottom: 0px;"><strong>Order Notes</strong></h3>
					@foreach($orderComment as $comment)
					@php
					$user = auth()->user();
					@endphp
					<p>{{$user->first_name}} {{$user->last_name}} {{$comment->comment}}</p>
					<p style="margin-bottom: 0px;">Date</p>
					<p><i>{{$comment->created_at->format('F '.'d, Y, '.'g:i A')}}</i></p>
					@endforeach

				</div>
			</div>
			<div class="col-lg-12">
				<form method="POST" id="order_notes" name="order_notes">
					<div class="form-group">
						<label for="exampleFormControlTextarea1" class="ms-2">Add Order Notes</label>
						<textarea class="form-control" id="comment" rows="3">
	    			</textarea>
						<input class="btn btn-primary" type="button" value="Add Notes" onclick="addComment(1)">
						<input type="hidden" value="{!!$order->id!!}" id="order_id">
					</div>

				</form>
			</div>
			<div class="col-lg-12">
				<form method="POST" id="order_notes" name="order_notes">
					<div class="form-group">
						<label for="exampleFormControlTextarea1" class="ms-2">Fullfil</label>
						<textarea class="form-control" id="comment" rows="3">
	    			</textarea>
						<input class="btn btn-primary" type="button" value="Add Notes" onclick="fullFillOrder()">
						<input type="hidden" value="{!!$order->id!!}" id="order_id">
					</div>

				</form>
			</div>
			<div class="card mb-4">
				<!-- Shipping information -->
				<div class="card-body">
					<h3 class="h6">Shipping Information</h3>
					<strong>Shipping Method</strong>
					<span><a href="#" class="text-decoration-underline" target="_blank">FF1234567890</a> <i class="bi bi-box-arrow-up-right</i> </span>
		        <hr>
		        <h3 class=" h6">Address</h3>
							<address>
								<strong>{{$customer->firstName}} {{$customer->Name}}</strong><br>
								{{$customer->postalAddress1}}, {{$customer->postalAddress2}}<br>
								{{$customer->postalCity}}, <br>
								<abbr title="Phone">P:</abbr> ({{$customer->phone}})
							</address>
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
	function addComment(isUserAdded) {
		if (isUserAdded == 1) {
			var comment = $( "#comment" ).val();
    		var order_id = $( "#order_id" ).val();
		} else{
			var comment = 'order cancel';
    		var order_id = $( "#order_id" ).val();
		}
    		
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

		function cancelOrder () {
			var order_id = $( "#order_id" ).val();
            $.ajax({
				url: "{{ url('admin/order-cancel') }}",
        		method: 'post',
        		data: {
            		"_token": "{{ csrf_token() }}",
            		"order_id": order_id
        		},
        		success: function(response){
				    console.log(response);
					window.location.reload();
					// window.location.href = "/admin/orders";
    			}
			})      
		}

    	function fullFillOrder() {
    		var status = $( "#status" ).val();
    		var order_id = $( "#order_id" ).val();
    		$('#spinner').removeClass('d-none');
    		jQuery.ajax({
        		url: "{{ url('admin/order-full-fill') }}",
        		method: 'post',
        		data: {
            		"_token": "{{ csrf_token() }}",
            		"order_id": order_id
        		},
         		success: function(response){
         		console.log(response);
        			setInterval('location.reload()', 7000);
    			}
    		});
    	}
</script>
@stop