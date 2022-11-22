<div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch mt-3 mb-3">
    <div class="card shadow-sm mb-4 w-100 h-100">
        @if($option->image != '')
        <img src="{{$option->image}}" class="col-md-10 img-fluid offset-1 mt-2"
            onclick="showdetails({{$product->id}},{{$option->option_id}},'{{$product->slug}}')"
            style="max-height:210px;min-height:180px;width:201px" />
        @else
        <img src="{{ asset('theme/img/image_not_available.png') }}" class="w-100 img-fluid h-75 w-75"
            onclick="showdetails({{$product->id}}')" />
        @endif
        <div class="card-body d-flex flex-column text-center mt-2">
            <h5 class="card-title" style="font-weight: 500;font-size: 16px;" id="product_name_{{$product->id}}"><a
                    class="product-row-product-title"
                    href="{{ url('product-detail/'.$product->id.'/'.$option->option_id.'/'.$product->slug) }}">{{$product->name}}</a>
            </h5>

            <input type="hidden" name="quantity" value="1" id="quantity">
            <input type="hidden" name="p_id" id="p_{{$product->id}}" value="{{$product->id}}">
            @csrf
            <div class="mt-auto">
                <?php $retail_prices = $option->retailPrice;
						?>
                <h4 class="text-uppercase mb-0 text-center text-danger">${{ number_format($retail_prices,2)}}</h4>
                @if($product->categories)
                <p class="category-cart-page mt-4">
                    Category:&nbsp;&nbsp;{{$product->categories->name}}
                </p>
                @else
                <p class="category-cart-page mt-4">
                    Category:&nbsp;&nbsp;Unassigned
                </p>
                @endif
                @if($option->stockAvailable > 0)
                <button class="ajaxSubmit button-cards col w-100" type="submit" style="max-height: 46px;"
                    id="ajaxSubmit_{{$product->id}}"
                    onclick="updateCart('{{$product->id}}', '{{$option->option_id}}')">Add to cart</button>
                @else
                <button class="ajaxSubmit text-white bg-danger bg-gradient button-cards col w-100 autocomplete=off"
                    tabindex="-1" type="submit" style="max-height: 46px;" id="ajaxSubmit_{{$product->id}}" disabled
                    onclick="return updateCart('{{$product->id}}')">Out of Stock</button>
                @endif
            </div>

        </div>
    </div>
</div>
<script>
    function updateCart(id, option_id) {
			jQuery.ajax({
               url: "{{ url('/add-to-cart/') }}",
               method: 'post',
               data: {
                 "_token": "{{ csrf_token() }}",
                  p_id: jQuery('#p_'+id).val(),
                  quantity: 1
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