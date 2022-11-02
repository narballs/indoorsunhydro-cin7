@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@include('partials.nav')
      	<div class="mb-5">
      		<p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
				PRODUCTS
		    </p>
      	</div>
      	<div class="container">
      		<div class="row">
     			@foreach($products as $key=>$product)
	     			@foreach($product->options as $option)
	      			<div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
					    <div class="card shadow-sm mb-4 w-100">
					    	@if($option->image != '')
					        	<a href="{{ url('product-detail/'.$product->id.'/'.$option->option_id) }}"><img src="{{$option['image']}}" class="col-md-10 offset-1" /></a>
					        @else
								<img src="{{ asset('theme/img/image_not_available.png') }}" class="w-100 img-fluid h-75 w-75" onclick="showdetails({{$product->id}})"/>
							@endif
					        <div class="card-body d-flex flex-column text-center">
					        	<input type="hidden" name="quantity" value="1" id="quantity">
								<input type="hidden" name="p_id" id="p_{{$product->id}}" value="{{$product->id}}">
					            <h5 class="card-title" style="font-weight: 500;
								font-size: 16px;"><a href="{{ url('product-detail/'.$product->id.'/'.$option->option_id) }}" id=product_name_{{$product->id}}>{{$product->name}}</a></h5>
					            <div class="mt-auto">
					                <p class="text-uppercase mb-0 text-center text-danger">${{$product->retail_price}}</p>
					                <button class="button-cards col w-100" style="max-height: 46px;" onclick="updateCart({{$product->id}},{{$option->option_id}})">Add to cart</button>
					            </div>
					        </div>
					    </div>
					</div>
					@endforeach
				@endforeach
			</div>
		</div>
		<div class="py-5 bg-light">
			<div class="col-md-12 text-center text-uppercase fs-4 mt-5">
				Subscribe to news letter
				<div class="fs-6 mt-1">
					Signup now for additional information or new products
					<div class="mt-3 mb-5">
						<input type="text" name="serach-prduct" placeholder="Enter your email"><button class="btn-outline-secondary text-white bg-dark h-35" type="button" id="button-addon1" >
							SUBSCRIBE
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Remove the container if you want to extend the Footer to full width. -->
		<script>
		function updateCart(id, option_id) {
			   jQuery.ajax({
               url: "{{ url('/add-to-cart/') }}",
               method: 'post',
               data: {
                 "_token": "{{ csrf_token() }}",
                  p_id: jQuery('#p_'+id).val(),
                  quantity: 1,
                  option_id: option_id

               },

               success: function(response){
					                    if(response.status == 'success'){
                        var cart_items = response.cart_items;
                        var cart_total = 0;
                        var total_cart_quantity = 0;

                        for (var key in cart_items) {
                            var item = cart_items[key];

                            var product_id = item.prd_id;
                            var price = parseFloat(item.price);
                            var quantity = parseFloat(item.quantity);

                            var subtotal = parseInt(price * quantity);
                            var cart_total = cart_total + subtotal;
                            var total_cart_quantity = total_cart_quantity + quantity;
                            $('#subtotal_' + product_id).html('$'+subtotal);
                            console.log(item.name);
                            var product_name = document.getElementById("product_name_"+jQuery('#p_'+id).val()).innerHTML;
                        }
                        
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: jQuery('#quantity').val() + ' X ' + product_name + ' added to your cart',
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
               }});

			    return false;
			}
		</script>
		
  		@include('partials.product-footer')
		
<!-- End of .container -->
@include('partials.footer')