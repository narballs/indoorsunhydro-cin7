@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container-fluid">
	<div class="container">
  		<!-- Title -->
  		<div class="d-flex justify-content-between align-items-center py-3">
    		<h2 class="h5 mb-0"><a href="#" class="text-muted"></a> Order #{{$order->order_id}}</h2>
  	</div>
  	<?php //dd($order);?>
  	<!-- Main content -->
  	<div class="row">
    	<div class="col-lg-8">
      		<!-- Details -->
      		<div class="card mb-4">
        		<div class="card-body">
          			<div class="mb-3 d-flex justify-content-between">
            			<div>
            				<?php //dd($order);?>
              				<span class="me-3">{{$order->created_at}}</span>
              				<span class="me-3">#{{$order->order_id}}</span>
              				<span class="me-3">Visa -1234</span>
              				<span class="badge rounded-pill bg-info">SHIPPING</span>
            			</div>
            			
            			<div class="d-flex">
              				<button class="btn btn-link p-0 me-3 d-none d-lg-block btn-icon-text"><i class="bi bi-download"></i> <span class="text">Invoice</span></button>
              				<div class="dropdown">
                				<button class="btn btn-link p-0 text-muted" type="button" data-bs-toggle="dropdown">
                  				<i class="bi bi-three-dots-vertical"></i>
                				</button>
                				<ul class="dropdown-menu dropdown-menu-end">
                  					<li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Edit</a></li>
                  					<li><a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print</a></li>
                				</ul>
              				</div>
            			</div>
          			</div>
          			<div class="row">
          				<table class="w-100">
          					<tr><th>Status</th><th>Stage</th><th>Created By</th><th>Processedby</th></tr>
          					<tr>
          						<td class="badge bg-success rounded-pill">	
          								{{$order->status}}
          						</td>
          						<td>{{$order->stage}}</td>
          						@if($order->createdby && $order->processedby)
	          						<td>
	          							{{$order->createdby->firstName}}   {{$order->createdby->lastName}}
	          						</td>
	          						<td>{{$order->processedby->firstName}}  {{$order->processedby->lastName}}
	          						</td>
	          					@endif
          					</tr>
          				</table>
          				
            		</div>
            	
            			
            			
				
          			<table class="table table-borderless">
            			<tbody>
            				<?php //dd($orderitems)?>
            			
              				<tr>
                				<td>
                  					<div class="d-flex mb-2">
	                    		
	                    				<div class="flex-lg-grow-1 ms-3">
	                      					<h6 class="small mb-0"><a href="#" class="text-reset"></a></h6>
	                      					<span class="small"></span>
	                    				</div>
                  					</div>
                				</td>
                				<td></td>
                				<td class="text-end"></td>
              				</tr>
              			
              			<!-- 	<tr>
				                <td>
				                  <div class="d-flex mb-2">
				                    <div class="flex-shrink-0">
				                      <img src="https://via.placeholder.com/280x280/FF69B4/000000" alt="" width="35" class="img-fluid">
				                    </div>
				                    <div class="flex-lg-grow-1 ms-3">
				                      <h6 class="small mb-0"><a href="#" class="text-reset">Smartwatch IP68 Waterproof GPS and Bluetooth Support</a></h6>
				                      <span class="small">Color: White</span>
				                    </div>
				                  </div>
				                </td>
                				<td>1</td>
                				<td class="text-end">$79.99</td>
              				</tr> -->
            			</tbody>
            			<tfoot>
            				<?php //dd($customer_details);?>
            				<table class="w-100">
				            	<tr>
				                	<td colspan="2"><h4>Billing Details</h4></td>

				                	<td colspan=""><h4>Shipping Details</h4></td>
				            	</tr>
				            	<tr>
				                	<td colspan="2">{{$customer->postalAddress1}}</td>
				                	<td colspan="2">{{$customer->address1}}</td>

				            	<tr>

				                	<td colspan="2">{{$customer->postalCity}}</td>
				            	</tr>
				            	<tr>
				                	<td colspan="2">{{$customer->postalState}}</td>
				            	</tr>
				            	<tr>
				                	<td colspan="2">{{$customer->postCode}}</td>
				            	</tr>
            			</tfoot>
          			</table>
        		</div>
      		</div>
      <!-- Payment -->
      	<div class="card mb-4">
        	<div class="card-body">
	          	<div class="row">
	            	<div class="col-lg-12">
	              		<h3 class="h5">Line Items</h5>
	            	</div>
	            	<div class="col-lg-12">
	              		<table class="table">
	              		
	              		<?php //dd($orderitems);?>
	              			@foreach($orderitems as $orderitem)
	              			@if($orderitem->product)
	              				<tr>
	              					<td>
	              						<div class="d-flex mb-2">
	                    				<div class="flex-shrink-0 mx-4 col-md-1">
	                      				
	                      					<img src="{{$orderitem->product->images}}" alt="" width="35" class="img-fluid">
	                   					</div>
	                    				<div class="flex-lg-grow-1 ms-3 col-md-3">
	                      					<h4 class="small mb-0"><a href="#" class="text-reset">{{$orderitem->product->name}}</a></h4>
	                    				</div>
	                    				<div class="flex-lg-grow-1 ms-3 col-md-1">
	                      					<h4 class="small mb-0">X</h4>
	                    				</div>
	                    				<div class="flex-lg-grow-1 ms-3 col-md-2">
	                      					<h4 class="small mb-0">{{$orderitem->quantity}}</h4>
	                    				</div>
	                    				<div class="flex-lg-grow-1 ms-3 col-md-2">
	                      					<h4 class="small mb-0">${{$orderitem->price}}</h4>
	                    				</div>
	                    				<div class="flex-lg-grow-1 ms-3 col-md-2">
	                      					<h4 class="small mb-0"><?php $total = $orderitem->price * $orderitem->quantity?>
	                      						{{$total}}

	                      					</h4>
	                    				</div>

                  					</div>
	              					</td>
	              				</tr>
	              			
	              				@endif
	              			@endforeach
	              				<tr>
	              					<?php //dd($order);?>
	              					<td>Total</td>
	              					<td>{{$order->total}}</td>
	              				</tr>
	              		</table>

	            	</div>
	          	</div>
        	</div>
      	</div>
    </div>
    <div class="col-lg-4">
      	<!-- Customer Notes -->
      	<h3 class="h6"><strong>Internal Comments</strong></h3>
      	<div class="card mb-4">
        	<div class="card-body">
          		
          		 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,

        	</div>
        	
      	</div>
      	<div class="col-lg-12">
      		<div>
      			<h3 class="h6"><strong>Delivery Notes</strong></h3>
      		</div>
		</div>
      
      	<div class="card mb-4">
        <!-- Shipping information -->
	        <div class="card-body">
		     	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,
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
    </script>
@stop