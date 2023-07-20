<div class="col-md-6 col-lg-3 d-flex align-self-stretch mt-2 product_row_mobile_responsive pt-1">
    <div class="p-2 shadow-sm  w-100 h-100" style="background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;">
        @if ($product->images != '')
            @if(!empty($contact_id))
                <a style="width:20px !important;" href="javascript:void(0);" class="ml-2 mt-2 subscribe">
                    <i class="fa-solid fav-{{ $option->option_id }} fa-heart {{ isset($user_buy_list_options[$option->option_id]) ? '' : 'text-muted' }} "
                        id="{{ $option->option_id }}" data-toggle="popover"
                        onclick="addToList('{{ $product->product_id }}', '{{ $option->option_id }}', '{{ isset($user_buy_list_options[$option->option_id]) }}')">
                    </i>
                </a>
            @endif
            <a href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                <div class="image-height-mbl" style="height: 300px;">
                    <span class="d-flex justify-content-center align-items-center">
                        <img src="{{ $product->images }}" class="img_responsive_mbl col-md-10 .image-body offset-1 mt-2"
                            style="" />
                    </span>
                </div>
            </a>
        @else
            <a href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                <div class="image-height-mbl"  style="height: 315px;">
                    <span class="d-flex justify-content-center align-items-center">
                        <img src=" {{ asset('theme/img/image_not_available.png') }}" class="img_responsive_mbl_not_available col-md-10 .image-body offset-1 mt-2"
                        style="" />
                    </span>
                </div>
            </a>
        @endif
        <div class="card-body d-flex flex-column text-center mt-2 prd_mbl_card_bdy">
            <h5 class="card-title card_product_title" style="font-weight: 500;font-size: 16px;" id="product_name_{{ $product->id }}">
                <a title="{{$product->name}}" class="product-row-product-title" href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                    {{ \Illuminate\Support\Str::limit($product->name, 33) }}</a>
            </h5>
            <input type="hidden" name="quantity" value="1" id="quantity">
            <input type="hidden" name="p_id" id="p_{{ $product->id }}" value="{{ $product->id }}">
            @csrf
            <div class="col-md-12 p-1">
                <?php
                $retail_price = 0;
                $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
                foreach ($option->price as $price) {
                    $retail_price = $price->$user_price_column;
                }
                ?>
                <h4 text="{{ $retail_price }}" class="text-uppercase mb-0 text-center p_price_resp mt-0">
                    ${{ number_format($retail_price, 2) }}</h4>
                @if ($product->categories)
                    <p class="category-cart-page  mt-2 mb-2" title="{{$product->categories->name}}">
                        Category:&nbsp;&nbsp;{{ \Illuminate\Support\Str::limit($product->categories->name, 4) }}
                    </p>
                @else
                    <p class="category-cart-page mt-2 mb-2">
                        Category:&nbsp;&nbsp;Unassigned
                    </p>
                @endif
                @if ($product->stockAvailable > 0 || $option->stockAvailable > 0)
                    <button class="prd_btn_resp ajaxSubmit button-cards col w-100 mt-2 mb-1" type="submit" style="max-height: 46px;"
                        id="ajaxSubmit_{{ $product->id }}"
                        onclick="updateCart('{{ $product->id }}', '{{ $option->option_id }}')">Add to cart</button>
                @else
                    <button class="prd_btn_resp ajaxSubmit mb-2 text-white bg-danger bg-gradient button-cards col w-100 autocomplete=off"
                        tabindex="-1" type="submit" style="max-height: 46px;" id="ajaxSubmit_{{ $product->id }}"
                        disabled onclick="return updateCart('{{ $product->id }}')">Out of Stock</button>
                @endif
            </div>
        </div>
    </div>
    <div id="popover-form" class="d-none">
        <form id="myform" class="form-inline" role="form">
            @foreach ($lists as $list)
                <div class="form-group">
                    <ul style="font-family: 'Poppins';font-style: normal;font-weight: 600;font-size: 14px;padding:1px;">
                        <li style="">
                            {{ $list->title }} &nbsp;<input type="radio" value="{{ $list->id }}"
                                name="list_id" />
                        </li>
                    </ul>
                </div>
            @endforeach
            <button type="submit" class="btn btn-warning"
                onclick="addToList('{{ $product->product_id }}', '{{ $option->option_id }}')">Add</button>
        </form>
    </div>
</div>
<script>
    function updateCart(id, option_id) {
        jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_' + id).val(),
                quantity: 1
            },
            success: function(response) {
                if (response.status == 'success') {
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
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        console.log(item.name);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: jQuery('#quantity').val() + ' X ' + product_name +
                            ' added to your cart',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });

        return false;
    }

    function addToList(product_id, option_id, status) {
        var list_id = $("input[name='list_id']:checked").val();
        var option_id = option_id;
        $.ajax({
            url: "{{ url('/add-to-wish-list/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                product_id,
                option_id,
                quantity: 1,
                list_id,
                status,
            },


            success: function(success) {
                if (success.success == true) {
                    $('.fav-' + option_id).toggleClass('text-muted');
                } else {
                    Swal.fire(
                        'Warning!',
                        'Please make sure you are logged in and selected a company.',
                        'warning',
                    );
                }

            }
        });
        return false;
    }
</script>
