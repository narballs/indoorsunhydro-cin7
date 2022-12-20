@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class=" cart-title mt-4  desktop-view">
	<p style="line-height: 95px;"
		class=" fs-2 product-btn my-auto border-0 text-white text-center align-middle cart-title">
		<span class="cart-page-cart-title">CART</span>
	</p>
</div>
@if (session('success'))
<div class="alert alert-success">
	{{ session('success') }}
</div>
@endif
<div class="container-fluid desktop-view" style="
	width: 1280px;
	margin: auto !important;
	">
	@if (Session::has('message'))
	<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
	@endif
	<div class="row">
		<div class="col-md-9">
			<section class=" h-100">
				<div class="h-100 py-5">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table mt-4" id="cart_table">
									<thead class="table-head-items">
										<tr>
											<th scope="col" class="th-lg" class="table-head-items">
												<span>
													<img class="img-fluid" src="/theme/img/barcode.png" style="width: 29px;
													">
												</span>
												<span class=" cart-total">SKU</span>
											</th>
											<th scope="col" class="th-lg" class="table-head-items">
												<span>
													<img class="img-fluid" src="/theme/img/box.png">
												</span>
												<span class=" cart-total">Product</span>

											</th>
											<th scope="col" class="th-lg" width="147px" class="table-head-items">
												<span>
													<img src="/theme/img/Price_Target.png" class="img-fluid">
												</span>
												<span class="cart-total">Price</span>

											</th>
											<th scope="col" class="th-lg" width="170px;" class="table-head-items">
												<span>
													<img src="/theme/img/Arrows_Down_Up.png" alt="">
												</span>
												<span class="cart-total">Quantity</span>

											</th>
											<th scope="col" class="th-lg" class="table-head-items">
												<span>
													<img class=" cart-icons-cart " src="/theme/img/pricing_tag.png">
												</span>

												<span class=" cart-total">Total</span>

											</th>
										</tr>
									</thead>
									<tbody style="padding: 40px">
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
												<span class="mb-0" style="font-weight: 500;">
													<a class="cart-page-items"
														href="{{ url('product-detail/'.$cart['product_id'].'/'.$cart['option_id'].'/'.$cart['slug']) }}" ">{{$cart['code']}}
														</a>
													</span>
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
																<span class="mb-2">
																	<a class=" pe-3 cart-page-items"
																		href="{{ url('product-detail/'.$cart['product_id'].'/'.$cart['option_id'].'/'.$cart['slug']) }}">{{$cart['name']}}
																	</a>
																</span>
															</div>
														</div>
											</td>

											<td class=" align-middle">
												<p class="mb-0 ps-2  cart-page-items">
													${{number_format($cart['price'],2)}}
												</p>
											</td>
											<td class="align-middle">
												<div class="col-md-3">
													<div class="quantity">
														<input type="number" name="quantity"
															id={{'row_quantity_'.$pk_product_id}} min="1" max="20"
															step="1" value="{{$cart['quantity']}}">
														<input type="hidden" name="p_id" id="p_id"
															value="{{$cart['product_id']}}">
														<input type="hidden" name="p_id" id="option_id"
															value="{{$cart['option_id']}}">
														<div class="quantity-nav">
															<div class="quantity-div quantity-up"
																onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
																</div>
															<div class="quantity-div quantity-down"
																onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
																</div>
														</div>
													</div>
												</div>
											</td>
											<td class="align-middle">
												<spsn class="mb-0 text-danger ps-2  cart-page-items">
													<span
														id="subtotal_{{ $pk_product_id }}">${{number_format($cart['price']
														*$cart['quantity'],2 )}}</span>
												</spsn>
												<p class="text-center remove-item-cart">
													<a style="font-family: 'Poppins';
														font-style: normal;
														font-weight: 400;
														margin-right: 4px;
														font-size: 12px;
														line-height: 18px;
														text-decoration-line: underline;
														color: #9A9A9A;" href="{{ url('remove/'.$pk_product_id) }}" id="remove">Remove</a>
												</p>
											</td>
										</tr>
										@endforeach
										@endif
									</tbody>
									<tfoot class="border-0" style="border-color: #ffff !important;">
										<tr>
											<td colspan="5">
												<div class="w-100 d-flex mt-4">
													<div class="col-md-8 coupon-code ps-0">
														<div class=" align-items-center d-flex">
															<div>
																<span class="coupon-code-label"><img class="img-fluid"
																		src="/theme/img/Vector.png"
																		class="img-fluid">&nbsp;&nbsp;Coupon
																	code</span>
															</div>
															<div class="col-4 ps-3">
																<div class="form-signupp">
																	<input type="text" name="code" id="code"
																		class="fontAwesome form-control"
																		placeholder="Your code" required>
																</div>
															</div>
															<div class="col-5 p-0">
																<span>
																	<button class="apply-coupon-code-button">
																		Apply Coupon
																	</button>
																</span>
															</div>
														</div>
													</div>
													<div class="col-md-4 p-0">
														<button class=" cart-updated" type="submit" id="update_cart"
															onclick="update_cart()">Update
															Cart</button>
													</div>
												</div>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<div class="col-md-3 p-0  mt-5">
			<div class="table-responsive">
				<table class="table mt-4">
					<thead>
						<tr>
							<th colspan="2" scope="col" class="th-lg">
								<span class=" cart-total p-1">
									Cart Totals
								</span>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<img class=" img-fluid" src="/theme/img/pricing_tag.png">
								<strong>Total</strong>
							</td>
							<td>
								<span id="cart_grand_total">
									<strong class="text-danger cart-page-items">
										${{number_format($cart_total,2)}}
									</strong>
								</span>
							</td>
						</tr>
					</tbody>
					<tfoot class="border-0" style="border-color: #ffff !important;">
						<tr>
							<td colspan="2">
								<li
									class="list-group-item d-flex justify-content-center align-items-center border-0 px-0 mb-3">
									@if (Auth::check() == true && !empty($contact->contact_id))
									<a href="{{ url('/checkout')}}">
										<button class="procedd-to-checkout mt-3">
											PROCEED TO CHECKOUT
										</button>
									</a>
									@elseif(Auth::check() == true && empty($contact->contact_id))
									<a href="{{ url('/checkout/')}}">
										<button class="procedd-to-checkout mt-3">
											PROCEED TO CHECKOUT
										</button>
									</a>
									@elseif (Auth::check() != true)
									<a href="{{ url('/user/')}}">
										<button class="procedd-to-checkout mt-3">
											Login or Register
										</button>
									</a>
									@endif
								</li>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<!--Mobile View -->
<!-- MultiStep Form  -->
<div class="container-fluid mobile-view">
	<div class="row justify-content-center mt-0">
		<div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0">
			<div class="card border-0 px-0">
				<div class="row">
					<div class="col-md-12 mx-0">
						<div id="msform">
							<!-- progressbar -->
							{{-- <ul id="progressbar">
								<li class="active" id="account"><strong>Your Card</strong></li>
								<li id="personal"><strong>Personal</strong></li>
								<li id="payment"><strong>Payment</strong></li>
								<li id="confirm"><strong>Finish</strong></li>
							</ul> --}}
							<!-- fieldsets -->
							<fieldset>
								<div class="form-card">
									<div class="card border-0">
										<div class="card-header bg-light p-0">
											<p class="your-cart-title ps-1"> Your Cart</p>
										</div>
										<div class="card-body p-0 m-0">
											<div class="col-md-12 p-0">
												<table>
													<thead>
													</thead>
													<tbody>
														@if ($cart_items)
														@foreach ( $cart_items as $pk_product_id => $cart )
														<tr>
															<td>
																@if(!empty($cart['image']))
																<img src="{{$cart['image']}}"
																	class="img-fluid rounded-3 mt-5" alt="Book"
																	style="width:141px;">
																@else
																<img src="/theme/img/image_not_available.png"
																	class="img-fluid rounded-3 mt-5" alt="Book"
																	style="width:141px;">
																@endif
															</td>
															<td>
																<div class="mt-5">
																	<a class=" pe-3 cart-page-items"
																		href="{{ url('product-detail/'.$cart['product_id'].'/'.$cart['option_id'].'/'.$cart['slug']) }}">{{$cart['name']}}
																	</a>
																	<div class="d-flex mt-2">
																		<div class="quantity-bg">
																			<p class="ps-1">
																				{{$cart['quantity']}}</p>
																		</div>
																		<div class="cart-page-price ps-3">
																			${{number_format($cart['price'],2)}}
																		</div>
																	</div>
																</div>
															</td>
															<td>
																<div class="d-flex justify-content-end aling-items-end">
																	<a href="{{ url('remove/'.$pk_product_id) }}"
																		id="remove">
																		<img class="img-fluid"
																			src="/theme/img/modal-icon2.png" alt="">
																	</a>
																</div>
																<div class="d-flex mt-3">
																	<input type="hidden" name="p_id" id="p_id"
																		value="{{$cart['product_id']}}">
																	<input type="hidden" name="p_id" id="option_id"
																		value="{{$cart['option_id']}}">
																	<span class="pe-1">
																		<button class="border-0 p-0"
																			onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
																			<i class="fa-solid fa-minus"></i>
																		</button>
																	</span>
																	<span class="ps-1">
																		<button class="border-0 p-0"
																			onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
																			<i class="fa-solid fa-plus"></i>
																		</button>
																	</span>
																</div>
															</td>
														</tr>
														@endforeach
														@endif
													</tbody>
												</table>
												<div class="total-cart-button">
													<button
														class="total-cart-button border-0 d-flex justify-content-center align-content-center w-100">
														<span class="m-auto">
															your cart: ${{number_format($cart_total,2)}}
														</span>
													</button>
												</div>
												<div class="d-flex justify-content-center align-items-center">
													<div>
														<img class="img-fluid coupon-code-modal-btn"
															src="/theme/img/modal-icon1.png" alt="">
													</div>
													<button type="button"
														class="btn btn-primary coupon-code-modal-btn ps-0"
														data-bs-toggle="modal" data-bs-target="#staticBackdrop">
														applay coupon
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<input type="button" name="next" class="next action-button" value="Next Step" />

							</fieldset>
							<fieldset>
								<div class="form-card">
									<div>
										<table class="table mt-5">
											<thead>
												<tr>
													<th style="border-top:none !important" scope="col">Cart Totals</th>
													<th style="border-top:none !important" scope="col"></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="d-flex">
															<span class="">
																<img src="theme/img/pricing_tag.png" width=" 22px">
															</span>
															<span>
																<p class="cart-total-checkout-page ps-3">Total</p>
															</span>
															<div
																class="d-flex justify-content-end aling-items-end ps-5">
																<p class="sub-total-checkout-page">
																	${{number_format($cart_total,2)}} </p>
															</div>
														</div>
													</td>
													<td></td>
												</tr>
											</tbody>
											<tfoot class="border-0">
												<tr>
													<td style="border-bottom: none">
														@if (Auth::check() == true && !empty($contact->contact_id))
														<a href="{{ url('/checkout')}}">
															<button class="procedd-to-checkout mt-2 ps-3">
																PROCEED TO CHECKOUT
															</button>
														</a>
														@elseif(Auth::check() == true &&
														empty($contact->contact_id))
														<a href="{{ url('/checkout/')}}">
															<button class="procedd-to-checkout mt-2 ps-3">
																PROCEED TO CHECKOUT
															</button>
														</a>
														@elseif (Auth::check() != true)
														<a href="{{ url('/user/')}}">
															<button class="procedd-to-checkout mt-2 ps-">
																Login or Register
															</button>
														</a>
														@endif

													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								{{-- <input type="button" name="previous" class="previous action-button-previous"
									value="Previous" />
								<input type="button" name="next" class="next action-button" value="Next Step" /> --}}
							</fieldset>
							<fieldset>
								<div class="form-card">

								</div>
								<input type="button" name="previous" class="previous action-button-previous"
									value="Previous" />
								<input type="button" name="make_payment" class="next action-button" value="Confirm" />
							</fieldset>
							<fieldset>
								<div class="form-card">
									<h2 class="fs-title text-center">Success !</h2>
									<br><br>
									<div class="row justify-content-center">
										<div class="col-3">
											<img src="https://img.icons8.com/color/96/000000/ok--v2.png"
												class="fit-image">
										</div>
									</div>
									<br><br>
									<div class="row justify-content-center">
										<div class="col-7 text-center">
											<h5>You Have Successfully Signed Up</h5>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content py-4" style="width:70% ;margin: auto;">
			<div class="modal-header border-0 pb-0 pt-0">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body border-0 p-0">
				<div class="d-flex justify-content-center align-items-center">
					<img class="img-fluid" src="/theme/img/modal-icon.png" alt="">
				</div>
				<div class="d-flex justify-content-center align-items-center mt-4">
					<p class="coupon-code-input-label m-0">coupon code</p>
				</div>
				<div class="form-signupp d-flex justify-content-center align-items-center mt-2">
					<div class="w-75 d-flex justify-content-center align-items-center">
						<input type="text" name="code" id="code" class="fontAwesome form-control"
							placeholder="Your code" required
							style="height: 46px; border-radius: inherit; text-align: center;">
					</div>
				</div>
			</div>
			<div class="modal-footer border-0 p-0 mt-2 d-flex justify-content-center align-items-center">
				<button type="button" class="btn btn-primary w-75 applay-coupon-code-modal-btn">applay
					coupon</button>
			</div>
		</div>
	</div>
</div>
@include('partials.footer')
<div class="desktop-view">
	@include('partials.product-footer')
</div>
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
             
jQuery('<div class="quantity-nav"><div class="quantity-div quantity-up">&#xf106;</div><div class="quantity-div quantity-down">&#xf107</div></div>').insertAfter('.quantity input');
		jQuery('.quantity').each(function () {
		var spinner = jQuery(this),
			input = spinner.find('input[type="number"]'),
			btnUp = spinner.find('.quantity-up'),
			btnDown = spinner.find('.quantity-down'),
			min = input.attr('min'),
			max = input.attr('max');

		btnUp.click(function () {
			var oldValue = parseFloat(input.val());
			if (oldValue >= max) {
			var newVal = oldValue;
			} else {
			var newVal = oldValue + 1;
			}
			spinner.find("input").val(newVal);
			spinner.find("input").trigger("change");
		});

		btnDown.click(function () {
			var oldValue = parseFloat(input.val());
			if (oldValue <= min) {
			var newVal = oldValue;
			} else {
			var newVal = oldValue - 1;
			}
			spinner.find("input").val(newVal);
			spinner.find("input").trigger("change");
		});

		});
  
</script>
<style>
	.table-responsive {
		border: 1px solid #DFDFDF;
		padding: 13px;
		padding-top: 0px;
	}

	.table thead>tr>th {
		border-top: 1px solid #fff;
	}

	.table tbody>tr>td {
		border-top: 1px solid #fff;
	}

	.table thead th {
		vertical-align: bottom;
	}

	.table-head-items {
		font-family: 'Poppins';
		font-style: normal;
		font-weight: 600;
		font-size: 16px;
		line-height: 24px;
		color: #000000;
	}


	input[type=number]::-webkit-inner-spin-button,
	input[type=number]::-webkit-outer-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	input[type=number] {
		-moz-appearance: textfield;
	}

	.quantity input {
		width: 96px;
		height: 49px;
		font-size: 16px;
		line-height: 24px;
		float: left;
		display: block;
		padding: 0;
		margin: 0;
		padding-left: 34px;
		border: none;
		color: #7CC633;
		box-shadow: 0 0 0 1px rgb(0 0 0 / 8%);
		border-radius: 1px;
	}

	.quantity input:focus {
		outline: 0;
	}

	.quantity-nav {
		float: left;
		position: relative;
		height: 74px;
	}

	.quantity-div {
		position: relative;
		cursor: pointer;
		border: none;
		border-left: 1px solid rgba(0, 0, 0, 0.08);
		width: 18px;
		text-align: center;
		color: #333;
		font-size: 13px;
		font-family: "FontAwesome" !important;
		line-height: 2.5 !important;
		padding: 0;
		background: #FAFAFA;
		-webkit-transform: translateX(-100%);
		transform: translateX(-100%);
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		-o-user-select: none;
		user-select: none;
	}

	.quantity-div:active {
		background: #EAEAEA;
	}

	.quantity-div.quantity-up {
		position: absolute;
		width: 25;
		height: 25px;
		top: 0;
		border-bottom: 1px solid rgba(0, 0, 0, 0.08);
		font-family: "FontAwesome";
		border-radius: 0 4px 0 0;
		line-height: 1.6
	}

	.quantity-div.quantity-down {
		position: absolute;
		width: 25;
		height: 25px;
		bottom: 0;
		font-family: "FontAwesome";
		border-radius: 0 0 4px 0;
		margin-bottom: 25px;
	}

	.top-bar-height {
		height: 78px;
	}

	.cart-counter-details {
		font-family: 'Poppins';
		font-style: normal;
		font-weight: 600;
		font-size: 14px;
	}
</style>

<script>
	$(document).ready(function(){
    
	var current_fs, next_fs, previous_fs; //fieldsets
	var opacity;
	
	$(".next").click(function(){
		
		current_fs = $(this).parent();
		next_fs = $(this).parent().next();
		
		//Add Class Active
		$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
		
		//show the next fieldset
		next_fs.show(); 
		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
			step: function(now) {
				// for making fielset appear animation
				opacity = 1 - now;
	
				current_fs.css({
					'display': 'none',
					'position': 'relative'
				});
				next_fs.css({'opacity': opacity});
			}, 
			duration: 600
		});
	});
	
	$(".previous").click(function(){
		
		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();
		
		//Remove class active
		$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
		
		//show the previous fieldset
		previous_fs.show();
	
		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
			step: function(now) {
				// for making fielset appear animation
				opacity = 1 - now;
	
				current_fs.css({
					'display': 'none',
					'position': 'relative'
				});
				previous_fs.css({'opacity': opacity});
			}, 
			duration: 600
		});
	});
	
	$('.radio-group .radio').click(function(){
		$(this).parent().find('.radio').removeClass('selected');
		$(this).addClass('selected');
	});
	
	$(".submit").click(function(){
		return false;
	})
		
	});
</script>