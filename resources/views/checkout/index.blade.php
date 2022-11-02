@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="mb-5">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
		Checkout
	</p>
</div>

<div class="container">
      	<div class="row">
        	<div class="col-md-5 order-md-2 mb-4">
	          	<h4 class="d-flex justify-content-between align-items-center mb-3">
	            <span class="text-muted">Cart Total</span>
	          	</h4>
	          	<div class="border-bottom"></div>
	          	<div class="mt-4"><img src="theme/img/box.png"><span class="ms-3 fw-bold">Product</span></div>

          		<ul class="list-group mb-3 my-3 mt-5">
          			<?php
          				$cart_total = 0;
            			$cart_price = 0;
            		?>
          			@if(Session::get('cart'))
          				@foreach(Session::get('cart') as $cart)
          				<?php 
		            		$total_quatity =  $cart['quantity'];
							$total_price = $cart['price'] * $total_quatity;
							$cart_total  = $cart_total + $total_price ;
		            	?>
            				<li class="list-group-item d-flex justify-content-between lh-condensed">
              				<div>
                				<h6 class="my-0"><a href="">{{$cart['name']}}</a></h6>
              				</div>
              				<span class="text-muted rounded-circle">{{$cart['quantity']}}</span>
            				</li>
            			@endforeach
            		@endif
            		<li class="list-group-item d-flex justify-content-between">
              			<span class="fw-bold"><img src="theme/img/shipping.png">&nbsp;Shipping</span>
              			<strong>{{$cart_total}}</strong>
            		</li>
            		<li class="list-group-item d-flex justify-content-between">		
            			<p class="mb-0">
            				Enter your address to calculate shipping
            			</p>
            		</li>
            		<li class="list-group-item d-flex justify-content-between">

              			<input type="text" name="email_address" class="bg-light form-control" placeholder="Your email">
            		</li>
            		<li class="list-group-item d-flex justify-content-between">
              			<span class="fw-bold"><img src="theme/img/price_target.png">Subtotal</span>
              		<strong>{{$cart_total}}</strong>
            		</li>
          		</ul>
          	<div class="col-md-12 row">
          		<div class="col-md-5 p-0"><h4>Credit Card</h4></div><div class="col-md-6 p-0 ms-1"><img src='theme/img/stripe.png'></div>
          	</div>
          	<div class="col-md-12 p-0">
          		<table cellpadding="4">
                    <tr>
                        <td>
                           <img src="theme/img/visa.png" height="40px">
                        </td>
                        <td>
          			       <img src="theme/img/master.png">
          		        </td>
                       
                        <td>
                           <img src="theme/img/dinner.png">
                        </td>
                        <td>
                           <img src="theme/img/xxx.png">
                        </td>
                        <td>
                           <img src="theme/img/american_express.png">
                        </td>
                        <td>
                           <img src="theme/img/discover.png" height="40px">
                        </td>
                    </tr>
                </table>

          	</div>

          	<form class="card p-2">
          		<span class="fw-bold">Card Number</span>
            	<div class="input-group">

              		<input type="text" class="form-control bg-light" placeholder="42424242424242" name="cc">
              		<div class="input-group-append">
             	
              		</div>
            	</div>
 
            <div class="row">
              	<div class="col-md-7 mb-3">
                	<label for="cc-expiration" class="fw-bold">Expiration</label>
                	<input type="text" class="form-control bg-light" id="cc-expiration" placeholder="Expiration" required>
                	<div class="invalid-feedback bg-light">
                  		Expiration date required
                	</div>
              	</div>
            	<div class="col-md-5 mb-3">
                	<label for="cc-expiration" class="fw-bold">CVV</label>
                	<input type="text" class="form-control bg-light" id="cc-cvv" placeholder="CVV" required>
                	<div class="invalid-feedback">
                  			Security code required
                	</div>
            	</div>
            </div>
            <div> <button type="submit" class="button-cards w-100" >Proceed to checkout</button></div>

        </form>
        </div>
        <div class="col-md-7 order-md-1">
          	<h4 class="mb-3">Billing address</h4>
          	<div class="border-bottom"></span></div>
          	<form class="needs-validation mt-4 novalidate" action="{{url('order')}}" method="POST">
            	@csrf
                <div class="row">
              		<div class="col-md-6 mb-3">
                		<label for="firstName" >First name</label>
                		<input type="text" class="form-control bg-light" name="firstName" placeholder="First name" value="" required>
                		<div class="invalid-feedback">
                  			Valid first name is required.
                		</div>
              		</div>
              		<div class="col-md-6 mb-3">
                		<label for="lastName">Last name</label>
                		<input type="text" class="form-control bg-light" name="lastName" placeholder="" value="" required>
                		<div class="invalid-feedback">
                  		Valid last name is required.
                	</div>
              		</div>
            	</div>

            	<div class="mb-3">
              		<label for="company">Company Name(optional)</label>
              		<div class="input-group">
                		<input type="text" class="form-control bg-light" name="company" placeholder="Enter you company name" required>
                		<div class="invalid-feedback" style="width: 100%;">
                  		Your username is required.
                		</div>
              		</div>
            	</div>

            	<div class="mb-3">
              		<label for="username">Country</label>&nbsp;<span>United States</span>
                    <input type="hidden" name="country" value="United States">
            	</div>


            	<div class="mb-3">
              		<label for="address">Street Address</label>
              		<input type="text" class="form-control bg-light" name="address" placeholder="House number and street name" required>
              		<div class="invalid-feedback">
                		Please enter your shipping address.
              		</div>
            	</div>

            	<div class="mb-3">
              		<label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
              		<input type="text" class="form-control bg-light" name="address2" placeholder="Apartment, suite, unit etc (optional)">
            	</div>
            	<div class="mb-3">
              		<label for="town">Town/City <span class="text-muted">(Optional)</span></label>
              		<input type="text" class="form-control bg-light" name="town_city" placeholder="Enter your town">
            	</div>

            	<div class="row">
              		<div class="col-md-6 mb-3">
                		<label for="state">State</label>
                		<input type="text" class="form-control bg-light" name="state" placeholder="Enter State" value="" required>
                		<div class="invalid-feedback">
                  			Valid first name is required.
                		</div>
              		</div>
              		<div class="col-md-6 mb-3">
	                	<label for="zip">Zip</label>
	                	<input type="text" class="form-control bg-light" name="zip" placeholder="Enter zip code" value="" required>
	                	<div class="invalid-feedback">
	                  		Valid last name is required.
	                	</div>
              		</div>
            	</div>

            	<div class="row">
              		<div class="col-md-6 mb-3">
	                	<label for="phone">Phone</label>
	                	<input type="text" class="form-control bg-light" name="phone" placeholder="Enter your phone" value="" required>
	                	<div class="invalid-feedback">
	                  	Valid first name is required.
	                	</div>
              		</div>
              	<div class="col-md-6 mb-3">
                		<label for="email">Email</label>
                		<input type="text" class="form-control bg-light" name="email" placeholder="Enter your email" value="" 	required>
                		<div class="invalid-feedback">
                  		Valid last name is required.
                		</div>
              		</div>
            	</div>

         <!--    <div class="row">
              <div class="col-md-5 mb-3">
                <label for="country">Country</label>
                <select class="custom-select d-block w-100" id="country" required>
                  <option value="">Choose...</option>
                  <option>United States</option>
                </select>
                <div class="invalid-feedback">
                  Please select a valid country.
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <label for="state">State</label>
                <select class="custom-select d-block w-100" id="state" required>
                  <option value="">Choose...</option>
                  <option>California</option>
                </select>
                <div class="invalid-feedback">
                  Please provide a valid state.
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="zip">Zip</label>
                <input type="text" class="form-control" id="zip" placeholder="" required>
                <div class="invalid-feedback">
                  Zip code required.
                </div>
              </div>
            </div> -->
            <hr class="mb-4">
            <div class="custom-control custom-checkbox">
              	<input type="checkbox" class="custom-control-input" id="same-address">
              	<label class="custom-control-label bg-light" for="same-address">Shipping address is the same as my billing address</label>
            </div>
             <div class="col-md-12 order-md-1">
            <h4 class="mb-3">Delivery address</h4>
            <div class="border-bottom"></span></div>
            <form class="needs-validation mt-4 novalidate" action="{{url('order')}}" method="POST">
                @csrf
                <div class="row">
                  
                </div>
                <div class="mb-3">
                    <label for="username">Country</label>&nbsp;<span>United States</span>
                    <input type="hidden" name="delivery_country" value="United States">
                </div>


                <div class="mb-3">
                    <label for="address">Street Address</label>
                    <input type="text" class="form-control bg-light" name="delivery_address_1" placeholder="House number and street name" required>
                    <div class="invalid-feedback">
                        Please enter your shipping address.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control bg-light" name="delivery_address_2" placeholder="Apartment, suite, unit etc (optional)">
                </div>
                <div class="mb-3">
                    <label for="town">Town/City <span class="text-muted">(Optional)</span></label>
                    <input type="text" class="form-control bg-light" name="delivery_town_city" placeholder="Enter your town">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="state">State</label>
                        <input type="text" class="form-control bg-light" name="delivery_state" placeholder="Enter State" value="" required>
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control bg-light" name="delivery_zip" placeholder="Enter zip code" value="" required>
                        <div class="invalid-feedback">
                            Valid last name is required.
                        </div>
                    </div>
                </div>

                <div class="row">
               
                </div>
            <div>
            </div>
            <div class="custom-control custom-checkbox">
              	<input type="checkbox" class="custom-control-input" id="save-info">
              	<label class="custom-control-label" for="save-info">Save this information for next time</label>
            </div>
            <div> <button type="submit" class="button-cards w-100" >Proceed to checkout</button></div>
            </form>
            <hr class="mb-4">

           <hr class="mb-4">
          
        </div>
      </div>

@include('partials.footer')