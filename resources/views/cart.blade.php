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

		<div style="width: 75%">
			<section class="h-100 h-custom">

				<div class="h-100 py-5">
					<div class="row d-flex justify-content-center align-items-center">
						<div class="col-md-12">
							<div class="" style=" font-family:'Poppins">

								<table class="table table-responsive border" id="cart_table">
									<thead>
										<th scope="col" class="th-lg">
											<span>
												<img class="img-fluid" src="/theme/img/barcode.png" style="    width: 29px;
												">
											</span>
											SKU
										</th>
										<th scope="col" class="th-lg">
											<span>
												<img class="img-fluid" src="/theme/img/box.png">
											</span>
											Product
										</th>
										<th scope="col" class="th-lg" width="15%">
											<span>
												<img class=" cart-icons-cart " src="/theme/img/Price_Target.png">
											</span>
											Price
										</th>
										<th scope="col" class="th-lg">
											<span>
												<img src="/theme/img/Arrows_Down_Up.png" alt="">
											</span>
											Quantity
										</th>
										<th scope="col" class="th-lg">
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
											<td class="align-middle">
												<p class="mb-0" style="font-weight: 500;"> <a
														class="text-dark cart-page-items"
														href="{{ url('product-detail/'.$cart['product_id'].'/'.$cart['option_id'].'/'.$cart['slug']) }}" ">{{$cart['code']}}</a>
												</p>

											</td>
											<td scope=" row">
														<div class="d-flex align-items-center">
															@if(!empty($cart['image']))
															<img src="{{$cart['image']}}" class="img-fluid rounded-3"
																style="width: 120px;" alt="Book">
															@else
															<img src="/theme/img/image_not_available.png"
																class="img-fluid rounded-3" style="width: 78px;
														height: 83px;" alt="Book">
															@endif
															<div class="flex-column ms-4">
																<p class="mb-2">
																	<a class="text-dark pe-3 cart-page-items"
																		href="{{ url('product-detail/'.$cart['product_id'].'/'.$cart['option_id'].'/'.$cart['slug']) }}">{{$cart['name']}}
																	</a>
																</p>
															</div>
														</div>
											</td>
											<td class=" align-middle">
												<p class="mb-0 ps-2  cart-page-items">${{$cart['price']}}</p>

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

											</td>
											<td class="align-middle">
												<p class="mb-0 text-danger ps-2"
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
								<div class="w-100 d-flex">
									<div class="col-md-8 coupon-code">
										<div class=" align-items-center d-flex">
											<div>
												<span class="coupon-code-label"><img class="img-fluid"
														src="/theme/img/Vector.png" class="img-fluid">&nbsp;&nbsp;Coupon
													code</span>
											</div>
											<div class="col-4 ps-3">
												<div class="form-signupp">
													<input type="text" name="code" id="code"
														class="fontAwesome form-control" placeholder="Your code"
														required>
												</div>
											</div>
											<div class="col-5 p-0">
												<span>
													<button class="apply-coupon-code-button w-100">
														Apply Coupon
													</button>
												</span>
											</div>
										</div>
									</div>
									<div class="col-md-4 p-0">
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


		<div class="col-md-3 p-0  mt-5">
			<div class="card mb-4 p-0 col-md-12">

				<div class="card-header py-3 bg-transparent">
					<h5 class="mb-0 cart-total">Cart Total</h5>
				</div>
				<div class="card-body ">
					<ul class="list-group list-group-flush">
						<li
							class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
							<div>
								<img class=" img-fluid" src="/theme/img/dollar.png"><strong>Subtotal</strong>
							</div>
							<span id="cart_subtotal"><strong>${{$cart_total}}</strong></span>
						</li>
						<li
							class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
							<div>
								<img class="img-fluid" src="/theme/img/pricing_tag.png">
								<strong>Total</strong>
							</div>
							<span id="cart_grand_total"><strong class="text-danger">${{$cart_total}}</strong></span>
						</li>
						<li
							class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
							@if (Auth::check() == true && !empty($contact->contact_id))
							<a href="{{ url('/checkout')}}">
								<button class="procedd-to-checkout col w-100 tm-5">
									PROCEED TO CHECKOUT
								</button>
							</a>
							@elseif(Auth::check() == true && empty($contact->contact_id))
							<a href="{{ url('/checkout/')}}">
								<button class="procedd-to-checkout col w-100 tm-5">
									PROCEED TO CHECKOUT
								</button>
							</a>
							@elseif (Auth::check() != true)
							<a href="{{ url('/user/')}}">
								<button class="procedd-to-checkout col w-100 tm-5">
									PROCEED TO CHECKOUT
								</button>
							</a>
							@endif
						</li>



					</ul>
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