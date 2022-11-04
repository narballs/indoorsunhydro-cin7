@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<div class="mb-5">
	<p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
		Cart
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
						<div class="col">
							<div class="table-responsive">
								<table class="table" id="cart_table">
									<thead>
										<tr>
											<th scope="col" class="h5">Product</th>
											<th scope="col">Price</th>
											<th scope="col">Quantity</th>
											<th scope="col">Total</th>
										</tr>
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
											</td>
											<td class="align-middle">
												<p class="mb-0" style="font-weight: 500;"><span
														id="subtotal_{{ $pk_product_id }}">${{$cart['price'] *
														$cart['quantity'] }}</span></p>
												<p class="text-center">
													<a href="{{ url('remove/'.$pk_product_id) }}" id="remove">Remove</a>
												</p>
											</td>
										</tr>
										@endforeach
										@endif
									</tbody>
								</table>
								<div><button class="button-cards col w-25" style="float:right" type="submit"
										class="update_cart" id="update_cart" onclick="update_cart()">Update
										Cart</button></div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<div class="col-md-4 d-flex align-self-stretch mt-5">
			<div class="card mb-4  col-md-12">
				<div class="card-header py-3 bg-transparent">
					<h5 class="mb-0">Cart Total</h5>
				</div>
				<div class="card-body ">
					<ul class="list-group list-group-flush">
						<li class="h-5 list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0 "
							style="font-weight: 500;">
							Subtotal
							<span id="cart_subtotal">${{$cart_total}}</span>
						</li>
						<!-- 	<li class="list-group-item d-flex justify-content-between align-items-center px-0" style="font-weight: 500;">
	                	Shipping
	                	<span></span>
	              	</li>
	              	<li class=" d-flex justify-content-between align-items-center px-0">
	                	<p style="font-size: 14px">Enter your address to view shipping options</p>
	               
	              	</li> -->
						<!-- 	<li class="list-group-item p-0"><input type="text" name ="email" class="w-100"></li>
              		<li class="list-group-item p-0" style="font-size: 14px">Calculate Shipping</li>
             		<li class="list-group-item d-flex justify-content-between align-items-center px-0" style="font-weight: 500;">
                	Markup</li> -->
						<li
							class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3 mt-5">
							<div>
								<strong>Total</strong>
							</div>
							<span id="cart_grand_total"><strong>${{$cart_total}}</strong></span>
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
@include('partials.product-footer')

@include('partials.footer')