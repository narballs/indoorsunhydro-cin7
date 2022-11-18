@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="mb-5 cart-title">
	<p style="line-height: 95px;"
		class=" fs-2 product-btn my-auto border-0 text-white text-center align-middle cart-title">
		CART
	</p>
</div>
@if (session('success'))
<div class="alert alert-success">
	{{ session('success') }}
</div>
@endif

<div class="container">
	@if (Session::has('message'))
	<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
	@endif
	<div class="row">
		<div class="col-sm-8 col-md-6 col-lg-8 d-flex align-self-stretch">
			<section class="h-100 h-custom">

				<div class="container h-100 py-5">
					<div class="row d-flex justify-content-center align-items-center">
						<div class="col-md-12">
							<div class="" style=" font-family:'Poppins">
								<table class="table" id="cart_table">
									<thead>

										<th scope="col">
											<span>
												<img class="img-fluid" src="/theme/img/box.png">
											</span>
											Product
										</th>
										<th scope="col" with="40%">
											<span>
												<img class=" cart-icons-cart " src="/theme/img/dollar.png">
											</span>
											Price
										</th>
										<th scope="col">Quantity</th>
										<th scope="col">
											<img class=" cart-icons-cart " src="/theme/img/pricing_tag.png">
											Total

									</thead>
									<tbody>
										<?php 
            								$cart_total = 0;
            								$cart_price = 0;
            							?>
										@if ($cart_items)
										@foreach ($cart_items as $pk_product_id => $cart)
										<?php 
			            							$total_quatity =  $cart['quantity'];
													$total_price = $cart['price'] * $total_quatity;
													$cart_total  = $cart_total + $total_price ;
			            				?>

										<tr id="{{'row_'.$pk_product_id}}" class="quantities">
											<th scope="row">
												<div class="d-flex align-items-center">
													<img src="{{$cart['image']}}" class="img-fluid rounded-3"
														style="width: 120px;" alt="Book">
													<div class="flex-column ms-4">
														<p class="mb-2">{{$cart['name']}}</p>
													</div>
												</div>
											</th>
											<td class="align-middle">
												<p class="mb-0" style="font-weight: 500;">${{$cart['price']}}</p>

											</td>
											<td class="align-middle">
												<div class="d-flex flex-row">
													<button class="btn btn-link px-2"
														onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
														<i class="fas fa-minus"></i>
													</button>
													<input id="{{'row_quantity_'.$pk_product_id}}" min="0"
														name="quantity" value="{{$cart['quantity']}}" type="number"
														class="form-control form-control-sm quantity"
														style="width: 50px;" />
													<button class="btn btn-link px-2"
														onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
														<i class="fas fa-plus"></i>
													</button>
												</div>
												{{-- <div class="d-flex flex-row" style="width:144px">
													<input type="number" name="quantity" id="quantity" min="1"
														max="{{$productOption->stockAvailable}}" step="1" value="1">
													<input type="hidden" name="p_id" id="p_id"
														value="{{$productOption->products->id}}">
													<input type="hidden" name="p_id" id="option_id"
														value="{{$productOption->option_id}}">
													<div class="quantity-nav">
														<div class="quantity-div quantity-up"></div>
														<div class="quantity-div quantity-down"></div>
													</div>
												</div> --}}
											</td>
											<td class="align-middle">
												<p class="mb-0 text-danger "
													style="font-weight: 600; font-size: 20; font-family:'Poppins'">
													<span id="subtotal_{{ $pk_product_id }}">${{$cart['price'] *
														$cart['quantity'] }}</span>
												</p>
												<p class="text-center remove-item-cart">
													<a style="color:#b5b5b5;  font-family:'Poppins"
														href="{{ url('remove/'.$pk_product_id) }}"
														id="remove">Remove</a>
												</p>
											</td>
										</tr>
										@endforeach
										@endif
									</tbody>
								</table>
								<div class="row">
									<div class="col-md-8 coupon-code">
										<div class="row">
											<div class="col-sm">
												<span class="coupon-code-label"><img class="img-fluid"
														src="/theme/img/Vector.png" class="img-fluid">&nbsp;&nbsp;Coupon
													code</span>
											</div>
											<div class="col-sm">
												{{-- <input type="text" placeholder="Your Code" id="coupon_code"
													name="coupon_code" class="form-control "> --}}<div
													class="form-signup">
													<input type="text" name="code" id="code"
														class="fontAwesome form-control" placeholder="Your code"
														required>
												</div>
											</div>
											<div class="col-sm">
												<span><button class="apply-coupon-code-button">APPLY
														COUPAN</button></span>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<button class="button-cards w-75 cart-updated" type="submit" id="update_cart"
											onclick="update_cart()">Update
											Cart</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<div class="col-md-4 d-flex align-self-stretch mt-5">
			<div class="card mb-4  col-md-12">
				<div class="card-header py-3 bg-transparent">
					<h5 class="mb-0 cart-total">Cart Total</h5>
				</div>
				<div class="card-body ">
					<ul class="list-group list-group-flush">
						<li
							class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
							<div>
								<img class="img-fluid" src="/theme/img/dollar.png"
									class="img-fluid"><strong>Subtotal</strong>
							</div>
							<span id="cart_subtotal"><strong>${{$cart_total}}</strong></span>
						</li>

						<li class="list-group-item  justify-content-between align-items-center border-0 px-0 mb-3">
							<div>
								<img class="img-fluid" src="/theme/img/shipping.png" class="img-fluid"><strong>
									&nbsp;&nbsp;Shipping</strong>
							</div>
							<div class="mt-2">
								<p class="cart-shipping-option">Enter your address to view shipping options.
								</p>
							</div>
							<div class="form-signup">
								<input type="text" name="email" id="email" class="fontAwesome form-control"
									placeholder="&#xf0e0; Your email" required>
							</div>
						</li>

						<li class="list-group-item  justify-content-between align-items-center border-0 px-0 mb-3">
							<div>
								<img class="img-fluid" src="/theme/img/calculator.png" class="img-fluid"><span
									class="cart-calculator">
									&nbsp;Calculate shipping
								</span>
							</div>
						</li>

						<li
							class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
							<div>
								<img class="img-fluid" src="/theme/img/FrameCart.png" class="img-fluid">
								<strong>Markup</strong>
							</div>
							<span id="cart_grand_total"><strong class="">${{$cart_total}}</strong></span>
						</li>

						<li
							class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
							<div>
								<img class="img-fluid" src="/theme/img/pricing_tag.png" class="img-fluid">
								<strong>Total</strong>
							</div>
							<span id="cart_grand_total"><strong class="text-danger">${{$cart_total}}</strong></span>
						</li>

					</ul>
					@if (Auth::check() == true && !empty($contact->contact_id))
					<a href="{{ url('/checkout')}}">
						<button class="button-cards col w-100 mt-4" style="max-height: 46px;">
							Proceed to checkout
						</button>
					</a>
					@elseif(Auth::check() == true && empty($contact->contact_id))
					<a href="{{ url('/checkout/')}}">
						<button class="button-cards col w-100 mt4" style="max-height: 46px;">
							Proceed to checkout
						</button>
					</a>
					@elseif (Auth::check() != true)
					<a href="{{ url('/user/')}}">
						<button class="button-cards col w-100 mt-4" style="max-height: 46px;">
							Login/Signup
						</button>
					</a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<div class="py-5 bg-light">
	<div class="col-md-12 text-center text-uppercase fs-4 mt-5">
		<h5 class="subscribe-heading">SUBSCRIBE TO NEWSLETTER</h5>
		<div class="fs-6 mt-1">
			<p class="subscribe-pra">Sign up now for additional information or new products</p>

			<div class="mt-3 mb-5">
				<div class="login-show-btn">
					<input type="text" name="email" id="email" class="fontAwesome pl-4" placeholder="Enter your email"
						required style="    font-family: 'Poppins', FontAwesome, 'Poppins';
						padding: 6px;
						/* padding: 1; */
						height: 61px;
						width: 307px;
						border: none;
						border-radius: 4px;">
					<button class="btn-outline-secondary text-white bg-dark h-35" type="button" id="button-addon1"
						style="width: 170px;
					height: 60px;
					left: 1075px;
					top: 1268px;
					background: #000000;
					border-radius: 0px 5px 5px 0px;">
						SUBSCRIBE
					</button>
				</div>

			</div>
		</div>
	</div>
</div>
@include('partials.product-footer')

@include('partials.footer')
<script>
	function update_cart() {
	var items_quantity = [];
	$('#cart_table > tbody  > tr.quantities').each(function(tr) {
		var product_id = this.id.replace('row_', '');
		var quantity = $('#row_quantity_' + product_id).val();
		items_quantity.push({
			id: product_id,
			quantity: quantity
		});
	});
	jQuery.ajax({
        url: "{{ url('update-cart') }}",
        method: 'post',
        data: {
            "_token": "{{ csrf_token() }}",
            "items_quantity" : items_quantity
        },
        success: function(response){
        	var cart_items = response.cart_items;
        	var cart_total = 0;
        	var total_cart_quantity = 0;

        	for (var key in cart_items) {
        		var item = cart_items[key];

        		var product_id = item.product_id;
        		var price = parseFloat(item.price);
        		var quantity = parseInt(item.quantity);

        		var subtotal = parseFloat(price * quantity);
        		cart_total += subtotal;
        		var total_cart_quantity = total_cart_quantity + quantity;

        		$('#subtotal_' + key).html('$' + subtotal.toFixed(2));
        	}

        	$('#cart_subtotal').html('$'+ cart_total.toFixed(2));
        	$('#cart_grand_total').html('<strong>$' + cart_total.toFixed(2) + '<strong>');
        	$('#top_cart_quantity').html(total_cart_quantity);
        	$('#topbar_cart_total').html('$'+parseFloat(cart_total));
            jQuery('.alert').html(response.success);
        }
    });

}
</script>