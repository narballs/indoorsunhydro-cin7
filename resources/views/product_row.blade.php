<?php
    $product_price = 0;
    $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
    foreach ($option->price as $price) {
        $product_price = $price->$user_price_column;
    }
?>
@if (!empty($product->categories) && ($product->categories->is_active == 1))
    @if ($product_price > 0)
        <div class="col-md-6 col-xl-3 col-lg-4 d-flex align-self-stretch mt-2 product_row_mobile_responsive pt-1">
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
                        <div class="image-height-mbl" style="min-height: 300px;max-height:300px;">
                            <span class="d-flex justify-content-center align-items-center">
                                <img src="{{ $product->images }}" class="img_responsive_mbl col-md-10 .image-body offset-1 mt-2"
                                    style="max-height: 300px;" />
                            </span>
                        </div>
                    </a>
                @else
                    <a href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                        <div class="image-height-mbl"  style="min-height: 300px;max-height:300px;">
                            <span class="d-flex justify-content-center align-items-center">
                                <img src=" {{ asset('theme/img/image_not_available.png') }}" class="img_responsive_mbl_not_available col-md-10 .image-body offset-1 mt-2" />
                            </span>
                        </div>
                    </a>
                @endif
                <div class="card-body d-flex flex-column text-center mt-2 prd_mbl_card_bdy">
                    <h5 class="card-title card_product_title tooltip-product" style="font-weight: 500;font-size: 16px;" id="product_name_{{ $product->id }}">
                        <a class="product-row-product-title" href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                            {{ \Illuminate\Support\Str::limit($product->name, 33) }}
                            {{-- <div class="tooltip-product-text">
                                <span class="">{{$product->name}}</span>
                            </div> --}}
                            <div class="tooltip-product-text bg-white text-primary">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner bg-white text-primary">
                                    <span class="">{{$product->name}}</span>
                                </div>
                            </div>
                        </a>
                    </h5>
                    <input type="hidden" name="quantity" value="1" id="quantity">
                    <input type="hidden" name="p_id" id="p_{{ $product->id }}" value="{{ $product->id }}">
                    @csrf
                    <div class="col-md-12 p-1 price-category-view-section">
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
                            <p class="category-cart-page  mt-3 mb-2" title="{{$product->categories->name}}">
                                Category:&nbsp;&nbsp;{{ \Illuminate\Support\Str::limit($product->categories->name, 4) }}
                            </p>
                        @else
                            <p class="category-cart-page mt-3 mb-2">
                                Category:&nbsp;&nbsp;Unassigned
                            </p>
                        @endif
                        <?php 
                            $enable_add_to_cart = App\Helpers\SettingHelper::enableAddToCart($option); 
                            $last_month_views = null;
                            $views_count = $product->product_views->whereBetween('created_at', [Carbon\Carbon::now()->subMonth()->startOfMonth(), Carbon\Carbon::now()->subMonth()->endOfMonth()])->count();
                            if ($views_count > 20) {
                                $last_month_views = $views_count . '+ views in last month';
                            } 
                            else if ($views_count <= 20 && $views_count > 0) {
                                $last_month_views = $views_count . ' view(s) in last month';
                            }
                            
                            $past_30_days = $date = Carbon\Carbon::today()->subDays(30);
                            $bought_products_count = $product->apiorderItem->where('created_at','>=',$date)->count();
                        ?>
                        @if (!empty($last_month_views))
                            <p class="text-dark mb-0 ft-size">{{$last_month_views}}</p>
                        {{-- @else
                        <p class="mt-2 pt-1"></p> --}}
                        @endif
                        @if ($bought_products_count > 0)
                            <small class="text-dark ft-size">{{$bought_products_count . '  bought in the past month'}}</small>
                        {{-- @else
                        <p class="text-danger category-cart-page font-weight-bold product_buys_count margin-for-empty"></p> --}}
                        @endif
                    </div>
                    <div class="col-md-12 add-to-cart-button-section">
                        @if ($enable_add_to_cart)
                            <button 
                                class="hover_effect prd_btn_resp ajaxSubmit button-cards col w-100  mb-1 original_cart_btn   original_cart_btn_{{$product->id}}" 
                                type="submit" 
                                style="max-height: 46px;" id="ajaxSubmit_{{ $product->id }}"
                                onclick="updateCart('{{ $product->id }}', '{{ $option->option_id }}')"
                            >
                                Add to cart
                            </button>
                            {{-- <button 
                                class="prd_btn_resp ajaxSubmit button-cards col w-100  mb-1 d-none button_swap_quantity"  
                                type="button" 
                                style="max-height: 46px;" id="button_swap_{{ $product->id }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <i class="fa fa-minus-circle" style="font-size: 20px;" onclick="subtracting_quantity('{{ $product->id }}', '{{ $option->option_id }}')"></i>
                                    <span id="swap_qty_number" class="text-center d-flex justify-content-center">
                                        <input type="number" readonly name="swap_qty_number" id="swap_qty_number_{{$product->id}}" value="0"  class="form-control swap_qty_number w-75 border-0 text-center" min="0">
                                    </span>
                                    <i class="fa fa-plus-circle"  style="font-size: 20px;" onclick="adding_quantity('{{ $product->id }}', '{{ $option->option_id }}')"></i>
                                </div>
                            </button>
                            --}}
                            <div class="col-sm-12 mt-0 button_swap_quantity button_swap_quantity_{{$product->id}} d-none" id="button_swap_{{ $product->id }}">
                                <div class="input-group">
                                    <div class="input-group-prepend custom-border qty_minus_mobile">
                                        <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="subtracting_quantity('{{ $product->id }}', '{{ $option->option_id }}')"><i class="fa fa-minus minus_qty_font qty_font"></i></button>
                                    </div>
                                    <input type="number" id="swap_qty_number_{{$product->id}}" name="swap_qty_number " readonly class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_{{$product->id}}"  style="font-weight: 500" value="0" min="0">
                                    <div class="input-group-prepend custom-border qty_plus_mobile">
                                        <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="adding_quantity('{{ $product->id }}', '{{ $option->option_id }}')"><i class="fa fa-plus plus_qty_font qty_font"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-total-{{ $product->id }} quantity_count_circle " style="display: none" data-product="{{$product->id}}" onclick="swap_quantity_input('{{ $product->id }}')">
                                <span class="cart-total-number-{{$product->id }} mr-2"></span>
                                Added to cart
                            </div>
                        @else
                            <button 
                                class="prd_btn_resp ajaxSubmit mb-3 text-white bg-danger bg-gradient button-cards col w-100 autocomplete=off"
                                tabindex="-1" 
                                type="submit" 
                                style="max-height: 46px;" 
                                id="ajaxSubmit_{{ $product->id }}"
                                disabled 
                                onclick="return updateCart('{{ $product->id }}')">Out of Stock</button>
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
    @endif
@endif
<style>

    .hover_effect:hover {
        background-color: #FFFFFF !important;
        color: #7BC533 !important;
        border: 1px solid #7BC533 !important;
    }
    .product_buys_count {
        font-size: 11px;
    }
    .margin-for-empty {
        margin-bottom: 1.4rem ;
    }

    .price-category-view-section {
        min-height: 7.7rem;
    }


    /* addign tooltip for title of product */
    /* .tooltip-product {
      position: relative;
      display: inline-block;
    } */
    .tooltip-product-text {
        display: none;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 500;
        position: absolute;
        top: 47%;
        text-align: center;
        font-size: 12px;
        /* box-shadow: 1px 1px 4px 4px rgba(0, 0, 0, 0.25); */
        box-shadow: 0px 4px 4px rgba(146, 130, 130, 0.25);
    }

    .tooltip-arrow {
        width: 0; 
        height: 0; 
        border-left: 7px solid transparent;
        border-right: 7px solid transparent;
        border-top: 7px solid #fff !important;
        position: absolute;
        top: 100%;
        left: 10%;
    }
    .tooltip-product:hover .tooltip-product-text {
      display: block;
    }
    
    @media screen and (max-width:350px)  and (min-width: 280px){
        .add-to-cart-button-section {
            padding: 0px !important;
        }
    }
    @media screen and (max-width:550px)  and (min-width: 280px){
        .product_buys_count {
            font-size: 7.5px !important;
        }
        .margin-for-empty {
            margin-bottom: 0.95rem !important;
        }

        .ft-size {
            font-size: 0.5rem;
        }

        .price-category-view-section {
            min-height: 5.7rem;
        }
    }
</style>
<script>
    function updateCart(id, option_id) {
        updateBodyClickEventStatus(false);
        $('#last_button_clicked').val(id);

        $('.cart-total-' + id).addClass('added-to-cart');
        $('#button_swap_' + id).addClass('btn-added-to-cart');

        //$('.quantity_count_circle').css('visibility', 'visible');
        $('.added-to-cart').css('display', 'inline-flex');
        $('.btn-added-to-cart').css('display', 'none');

        $('.cart-total-' + id).css('display', 'none');
        $('#button_swap_' + id).css('display', 'block');
        
        $('#ajaxSubmit_'+id).addClass('d-none');
        $('#button_swap_'+id).removeClass('d-none');

        $('#swap_qty_number_'+id).val(1);
        $('.quantity_count_circle').each(function() {
            // var html = $(this).html();
            var html = $(this);
            var spanContent = $(html).find('span');
            if (parseInt($(html).find('span').html()) > 0) {
                spanContent.parent().css('display', 'inline-flex');
            } else {
                spanContent.parent().css('display', 'none');
            }
        });
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
                    jQuery('.cart-total-number-' + id).html($('#swap_qty_number_' + id).val());
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
    function swap_quantity_input(id) {
      updateBodyClickEventStatus(false);
      $('.quantity_count_circle').each(function() {
            // var html = $(this).html();
            var html = $(this);
            var spanContent = $(html).find('span');
            if (parseInt($(html).find('span').html()) > 0) {
                spanContent.parent().css('display', 'inline-flex');
            } else {
                spanContent.parent().css('display', 'none');
            }
        });
      $('.cart-total-'+id).css('display', 'none');
      $('.btn-added-to-cart').css('display', 'none');
      $('.quantity_count_circle').each(function() {
            // var html = $(this).html();
            var html = $(this);
            var spanContent = $(html).find('span');
            if (parseInt($(html).find('span').html()) > 0) {
                spanContent.parent().css('display', 'inline-flex');
            } else {
                spanContent.parent().css('display', 'none');
            }
        });
      $('#button_swap_'+id).css('display', 'block');
      $('.cart-total-'+id).css('display', 'none');
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
