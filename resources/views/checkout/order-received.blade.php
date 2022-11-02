@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@include('partials.nav')
<?php //dd($order) 
session()->forget('cart');

?>
      	<div class="mb-5">
      		<p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
				Thank You
		    </p>
      	</div>
      	<div class="container">
      		<div class="row">
      		<table class="mb-5">
      			<tr>
      				<th>Order number</th>
      				<th>Date</th>
      				<th>Email</th>
      				<th>Total</th>
      				<th>Payment Method</th>
      			</tr>
      			<tr>
      				<td>{{$order->id}}</td>
      				<td>{{$formatedDate}}</td>
      				<td>{{$order->user->email}}</td>
      				<td>$ {{$order->total}}</td>
      				<td>{{$order->paymentTerms}}</td>
      			</tr>
      		</table>
      		<table class="mt-5">
      			<tr><th>PRODUCTS</th></tr>
      			<?php //dd($order->apiOrderItem) ?>
      	     @foreach($order->apiOrderItem as $item)
      			<tr><td></td></tr>
      			<tr><td></td></tr>
      			<tr><td></td></tr>
      				<tr class="border-bottom mt-5">
      					<td class="mt-3">
      						<a href="{{ url('product-detail/'. $item->product->id) }}">{{$item->product->name}}</a>&nbsp;&nbsp;&nbsp;&nbsp; X {{$item->quantity}}
      					</td>
      				</tr>
      			@endforeach
      			<tr><td></td></tr>
      			<tr><td></td></tr>
      			<tr><td></td></tr>
      			<tr class="border-bottom">
      				<th>Subtotal</th><td><strong>${{$order->total}}</strong></td>
      			</tr>
      			<tr><td></td></tr>
      			<tr><td></td></tr>
      			<tr><td></td></tr>
      			<tr class="border-bottom">
      				<th>Shipping Method</th><td><strong>$0.00</strong></td>
      			</tr>
      			<tr><td></td></tr>
      			<tr><td></td></tr>
      			<tr><td></td></tr>
      			<tr class="border-bottom">
      				<th>Payment Method</th><td><strong>{{$order->paymentTerms}}</strong></td>
      			</tr>
      		</table>
      		<div class="col-md-5 " id="shipping_address">
               <div class="billing-address bg-light p-3 ms-4 mt-5 mb-5">
	                <div class="bg-light" >
	                    <div style="font-weight: 600;font-size: 20px;">Billing Address</div>
		                <div class="row mt-2">
		                    <div class="col-md-6 name">{{$order->user->contact->firstName}}</div>
		                    <div class="col-md-6 name">{{$order->user->contact->lastName}}</div>
		                </div>
	                </div> 
	                <div class="address-line bg-light name">
	                    {{$order->user->contact->postalAddress1}}
	                </div>

	              
	                 <div class="address-line bg-light name">
	                     {{$order->user->contact->postalAddress2}}
	                </div>
	          
	           <!--      <div class="row m-0 bg-light">
	                    <div class="col p-0 address-line">
	                        City
	                    </div>
	                     <div class="col p-0 address-line">
	                        State
	                    </div>
	                     <div class=" col p-0 address-line">
	                        Zip
	                    </div>
	                </div> -->
	                <div class="billing-address bg-light">
	                     <div class="row m-0">
	                        <div class="col p-0 name">
	                           {{$order->user->contact->postalCity}}
	                        </div>
	                         <div class="col p-0 name">
	                          {{$order->user->contact->postalState}}
	                        </div>
	                         <div class="col p-0 name">
	                           {{$order->user->contact->postalPostCode}}
	                        </div>
	                    </div>
	                </div>
	  
	            </div>
        	</div>
		</div>
	</div>


		<!-- Remove the container if you want to extend the Footer to full width. -->
		
		
  		@include('partials.product-footer')
		
<!-- End of .container -->
@include('partials.footer')