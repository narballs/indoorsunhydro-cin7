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
        			<div class="row">
        				<input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
	        			<div class="text-muted col-md-10"><h5>{{$customer->firstName}} {{$customer->lastName}}</h5></div>
	          			<div class="mb-3 d-flex justify-content-between">
	          				<div class="row">
		            			<div class="col-md-2">
		            				<?php //dd($order);?>
		              				<span class="me-3"></span>
		              				<span class="me-3"></span>
		              				
		              					<?php 
		              						if ($customer->status == 1) {
		              							$status = 'Active';
		              						}
		              						else {
		              							$status = 'Inactive';
		              						}
		              					?>
		              				@if($status == 'Active')
		              					<span class="badge bg-success">
		              						{{$status}}
		              					</span>
		              				@else 
		              					<span class="badge bg-danger">
		              						{{$status}}
		              					</span>
		              				@endif

		            			</div>

		            		</div>
		            	

	            		</div>
	            		<div class="spinner-border d-none" role="status" id="spinner">
  							<span class="sr-only">Activating...</span>
						</div>
	            		@if($status == 'Active')
	            			<div>
		            			<button disabled class="btn btn-primary" type="button" onclick="updateContact()">Activate</button>
		              		</div>
		              	@else 
		              		<div>
		            			<button class="btn btn-primary" type="button" onclick="updateContact()">Activate</button>
		              		</div>
		              	@endif
            			
            			<div class="d-flex">
              				<!-- <button class="btn btn-link p-0 me-3 d-none d-lg-block btn-icon-text"><i class="bi bi-download"></i> <span class="text">Invoice</span></button> -->
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

          		<!-- 	<form method="POST" id="order_status" name="order_status">
          				<div>
              				<span class="me-3">Order Status</span>
            			</div>
            			<select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="status">
            				<?php //dd($customer);?>
            			

						</select>
						<div>
							<input class="btn btn-primary" type="button" value="Update" onclick="updateStatus()">
						</div>
					</form> -->
          			<table class="table table-borderless">
            			<tbody>
            				<?php //dd($orderitems)?>
            <!--  -->
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
				            <tr>
				                <td colspan="2"><sapn class="text-muted"><b>Company:</b></sapn>{{$customer->company}}</td>
				                <td class="text-end"></td>

				           
				            	<?php //dd($customer);?>
				            	<td></td>
				            	<td></td>
				                <td colspan="2"><b>Pricing Column:</b> {{$customer->priceColumn}}</td>

				                
				            </tr>
				                <tr>
				                <td colspan="2"><sapn class="text-muted"></td>
				                <td class="text-end"></td>

				           
				            	<?php //dd($customer);?>
				            	<td></td>
				            	<td></td>
				                <td colspan="2"><b>Email:</b> {{$customer->email}}</td>
				               
				                
				            </tr>
				          <!--   <tr>
				            	<td>
				            	</td>
				            	<td>
				            	</td>
				            	<td colspan="2"><b>Email:</b>{{$customer->email}}</td>
				            </tr> -->
				            <tr>
				              <td colspan="2"><sapn class="text-muted"><b>Website:</b></sapn>{{$customer->website}}</td>
				                <td class="text-end"></td>
				            </tr>
				           

				            <tr>
				            	<td colspan="2"><sapn class="text-muted"><b>Job Title:</b></sapn>{{$customer->jobTitle}}</td></tr>
				            <tr>
				                <td colspan="2"><h5>Billing Address</h5></td>
				                <td></td>
				                <td></td>
				               <td></td>
				                <td colspan="2"><h5>Delivery Address</h5></td>
				            </tr>
				            <tr class="fw-bold">
				            	<?php //dd($customer);?>
				                <td colspan="2" class="text-muted pt-0 pb-0 ">{{$customer->postalAddress1}}</td>
				                 <td></td>
				                <td></td>
				               <td></td>
				                <td class="text-end">{{$customer->address1}}</td>
				            </tr>
				             <tr class="fw-bold">
				            	<?php //dd($customer);?>
				                <td colspan="2" class="text-muted pt-0 pb-0 ">{{$customer->postalAddress2}}</td>
				                 <td></td>
				                <td></td>
				               <td></td>
				                <td class="text-end">{{$customer->address1}}</td>
				            </tr>
				             <tr>
				            	<td colspan="2" class="text-muted  pt-0 pb-0">State : {{$customer->postalState}}</td>
				            </tr>
				              <tr>
				            <td colspan="2" class="text-muted  pt-0 pb-0">Postal Code : {{$customer->postalPostCode}}</td>
				            <tr>
				              	<td colspan="2" class="text-muted  pt-0 pb-0">Postal City : {{$customer->postalCity}}</td>
				            </tr>
				             </tr>
				            	<td colspan="2" class="text-muted pt-0 pb-0">Mobile # {{$customer->phone}}</td>
				            <tr>
				        <!--     <tr>
				              	<td colspan="2" class="text-muted  pt-0 pb-0">Postal City : {{$customer->postalCity}}</td>
				            </tr> -->
            			</tfoot>
          			</table>
        		</div>
      		</div>
      <!-- Payment -->
      	<div class="card mb-4">
        	<div class="card-body">
	          	<div class="row">
	            	<div class="col-lg-12">
	              		<h2 class="h5 mb-0"><a href="#" class="text-muted"></a>Order History</h2>
	              		<?php //dd($customer->apiorders);
	              		// foreach($customer->apiorders as $apiorder) {
	              		// 	echo $apiorder->id;
	              		// }

	              		?>
	              		<table class="table">
	              			<tr>
	              				<th>Order #</th>
	              				<th>Date Created</th>
	              				<th>Status</th>
	              				<th>Total</th>
	              				<th>Ref#</th>
	              			</tr>
	              			<?php //dd($customer_orders);?>
	              			@foreach($customer_orders as $customer_order)
	              			<?php //dd($customer_orders);?>
	              				<tr>
	              					@if($customer_order->order_id)
	              						<td>
	              							{{$customer_order->order_id}}
	              					
	              						</td>
	              					@else 
	              						<td class="badge bg-danger">Pending Approval</td>
	              					@endif
	              					<td>
	              						{{$customer_order->createdDate}}
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
      	<div class="card mb-4">
        <!-- Shipping information -->
	        <div class="card-body">
		        <h3 class="h6">Shipping Information</h3>
		        <strong>Shipping Method</strong>
		        <span><a href="#" class="text-decoration-underline" target="_blank">FF1234567890</a> <i class="bi bi-box-arrow-up-right</i> </span>
		        <hr>
		        <h3 class="h6">Address</h3>
		        <address>
		            <strong>{{$customer->firstName}}{{$customer->lastName}}</strong><br>
		          
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
    </script>
@stop