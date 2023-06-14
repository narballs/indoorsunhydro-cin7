<div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch mt-3 mb-3">
    <div class="card shadow-sm mb-4 w-100 h-100">
        @if ($product->images != '')
            <a style="width:20px !important;" href="javascript:void(0);" class="ml-2 mt-2 subscribe">
                <i class="fa-solid fav-{{ $option->option_id }} fa-heart {{ isset($user_buy_list_options[$option->option_id]) ? '' : 'text-muted' }} "
                    id="{{ $option->option_id }}" data-toggle="popover"
                    onclick="addToList('{{ $product->product_id }}', '{{ $option->option_id }}', '{{ isset($user_buy_list_options[$option->option_id]) }}')">
                </i>
            </a>
            <a href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                <span class="d-flex justify-content-center align-content-center">
                    <img src="{{ $product->images }}" class="col-md-10 .image-body offset-1 mt-2"
                        style="width: 120px; max-height: 300px; " />
                </span>
            </a>
        @else
            <a href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                <span class="d-flex justify-content-center align-items-center">
                    <img src=" {{ asset('theme/img/image_not_available.png') }}" class="w-100  h-75 w-75" />
                </span>
            </a>
        @endif
        <div class="card-body d-flex flex-column text-center mt-2">
            <h5 class="card-title" style="font-weight: 500;font-size: 16px;" id="product_name_{{ $product->id }}">
                <a class="product-row-product-title"
                    href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">{{ $product->name }}</a>
            </h5>
            <input type="hidden" name="quantity" value="1" id="quantity">
            <input type="hidden" name="p_id" id="p_{{ $product->id }}" value="{{ $product->id }}">
            @csrf
            <div class="mt-auto">
                <?php
                $retail_price = 0;
                foreach ($option->price as $price) {
                    switch ($pricing) {
                        case 'RetailUSD':
                            $retail_price = $price->retailUSD;
                            break;
                        case 'WholesaleUSD':
                            $retail_price = $price->wholesaleUSD;
                            break;
                        case 'TerraInternUSD':
                            $retail_price = $price->terraInternUSD;
                            break;
                        case 'SacramentoUSD':
                            $retail_price = $price->sacramentoUSD;
                            break;
                        case 'OklahomaUSD':
                            $retail_price = $price->oklahomaUSD;
                            break;
                        case 'CalaverasUSD':
                            $retail_price = $price->calaverasUSD;
                            break;
                        case 'Tier1USD':
                            $retail_price = $price->tier1USD;
                            break;
                        case 'Tier2USD':
                            $retail_price = $price->tier2USD;
                            break;
                        case 'Tier3USD':
                            $retail_price = $price->tier3USD;
                            break;
                        case 'CommercialOKUSD':
                            $retail_price = $price->commercialOKUSD;
                            break;
                        case 'CostUSD':
                            $retail_price = $price->costUSD;
                            break;
                        default:
                            $retail_price = $price->retailUSD;
                            break;
                    }
                }
                ?>
                <h4 text="{{ $retail_price }}" class="text-uppercase mb-0 text-center text-danger">
                    ${{ number_format($retail_price, 2) }}</h4>
                @if ($product->categories)
                    <p class="category-cart-page mt-4">
                        Category:&nbsp;&nbsp;{{ $product->categories->name }}
                    </p>
                @else
                    <p class="category-cart-page mt-4">
                        Category:&nbsp;&nbsp;Unassigned
                    </p>
                @endif
                @if ($product->stockAvailable > 0 || $option->stockAvailable > 0)
                    <button class="ajaxSubmit button-cards col w-100 mt-2" type="submit" style="max-height: 46px;"
                        id="ajaxSubmit_{{ $product->id }}"
                        onclick="updateCart('{{ $product->id }}', '{{ $option->option_id }}')">Add to cart</button>
                @else
                    <button class="ajaxSubmit text-white bg-danger bg-gradient button-cards col w-100 autocomplete=off"
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
