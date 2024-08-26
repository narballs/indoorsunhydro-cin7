<?php

    $enable_see_similar_products = App\Helpers\SettingHelper::getSetting('enable_see_similar_products', 'Yes'); 

    $product_price = 0;
    $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
    foreach ($option->price as $price) {
        $product_price = $price->$user_price_column;
        if ($product_price == 0) {
            $product_price = $price->sacramentoUSD;
        } 
        if ($product_price == 0) {
            $product_price = $price->retailUSD;
        }
    }

    $product_by_category = false;
    if (!empty($product->categories)) {
        if ($product->categories->is_active == 1) {
            $product_by_category = true;
        } else {
            $product_by_category = false;
        }
    } else {
        $product_by_category = true;
    }
?>
@if ($product_by_category == true)
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
                    @if(!empty($contact_id))
                        <a style="width:20px !important;" href="javascript:void(0);" class="ml-2 mt-2 subscribe">
                            <i class="fa-solid fav-{{ $option->option_id }} fa-heart {{ isset($user_buy_list_options[$option->option_id]) ? '' : 'text-muted' }} "
                                id="{{ $option->option_id }}" data-toggle="popover"
                                onclick="addToList('{{ $product->product_id }}', '{{ $option->option_id }}', '{{ isset($user_buy_list_options[$option->option_id]) }}')">
                            </i>
                        </a>
                    @endif
                    <a href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                        <div class="image-height-mbl"  style="min-height: 300px;max-height:300px;">
                            <span class="d-flex justify-content-center align-items-center">
                                <img src=" {{ asset('theme/img/image_not_available.png') }}" class="img_responsive_mbl_not_available col-md-10 .image-body offset-1 mt-2" />
                            </span>
                        </div>
                    </a>
                @endif
                <div class="card-body d-flex flex-column text-center mt-2 prd_mbl_card_bdy">
                    <h5 class="card-title card_product_title tooltip-product" style="font-weight: 500;font-size: 16px;" data-title="{{$product->name}}" id="product_name_{{ $product->id }}">
                        <a class="product-row-product-title" href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                            {{ \Illuminate\Support\Str::limit($product->name, 30) }}
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
                                if ($retail_price == 0) {
                                    $retail_price = $price->sacramentoUSD;
                                }
                                if ($retail_price == 0) {
                                    $retail_price = $price->retailUSD;
                                }
                            }
                            $add_to_cart = true;
                            $show_price = true;
                            if (!empty($products_to_hide)) {
                                if (in_array($option->option_id, $products_to_hide)) {
                                    if (!auth()->user()) {
                                        $add_to_cart = false;
                                        $show_price = false;
                                    } else {
                                        if (auth()->user()) {
                                            $contact = App\Models\Contact::where('user_id', auth()->user()->id)->first();
                                            if (empty($contact)) {
                                                $add_to_cart = false;
                                                $show_price = false;
                                            }
                                            $contact_id_new = null; 
                                            if ($contact->is_parent == 1) {
                                                $contact_id_new = $contact->contact_id;
                                            } else {
                                                $contact_id_new = $contact->parent_id;
                                            }

                                            $get_main_contact = App\Models\Contact::where('contact_id', $contact_id_new)->first();
                                            if (!empty($get_main_contact) && strtolower($get_main_contact->paymentTerms) == 'pay in advanced') {
                                                $add_to_cart = false;
                                                $show_price = false;
                                            } else {
                                                $add_to_cart = true;
                                                $show_price = true;
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        ?>
                        @if (!empty($option) && $option->stockAvailable > 0)
                            <div class="mt-1 mb-1">
                                <span class="text-success">{{'In Stock'}}</span>
                            </div>
                        @else
                            <div class="mt-1 mb-1">
                                <span class="text-danger">{{ App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT OF STOCK');
                                    }}</span>
                            </div>
                        @endif
                        @if ($show_price == true)
                            <h4 text="{{ $retail_price }}" class="text-uppercase mb-0 text-center p_price_resp mt-0">${{ number_format($retail_price, 2) }}</h4>
                        @endif
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
                    @if ($add_to_cart == true)
                        <div class="col-md-12 add-to-cart-button-section">
                            @if (!empty($notify_user_about_product_stock) && strtolower($notify_user_about_product_stock->option_value) == 'yes')
                                @if ($option->stockAvailable > 0)
                                    <div onclick="button_swap_quantity('{{ $product->id }}', '{{ $option->option_id }}')" class="col-sm-12 mt-0 button_swap_quantity button_swap_quantity_{{$product->id}} mb-2" id="button_swap_{{ $product->id }}">
                                        <div class="input-group">
                                            <div class="input-group-prepend custom-border qty_minus_mobile">
                                                <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="subtracting_quantity('{{ $product->id }}', '{{ $option->option_id }}')"><i class="fa fa-minus minus_qty_font qty_font"></i></button>
                                            </div>
                                            {{-- <input type="number" id="swap_qty_number_{{$product->id}}" name="swap_qty_number " onchange="update_qty_text('{{ $product->id }}', '{{ $option->option_id }}')" class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_{{$product->id}}"  style="font-weight: 500" min="1" max="{{$option->stockAvailable}}"> --}}
                                            <input type="number" id="swap_qty_number_{{$product->id}}" name="swap_qty_number" value="1"  class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_{{$product->id}}"  style="font-weight: 500" min="1" max="{{$option->stockAvailable}}">
                                            <div class="input-group-prepend custom-border qty_plus_mobile">
                                                <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="adding_quantity('{{ $product->id }}', '{{ $option->option_id }}')"><i class="fa fa-plus plus_qty_font qty_font"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <button 
                                        class="hover_effect prd_btn_resp ajaxSubmit button-cards col w-100  mb-1 original_cart_btn   original_cart_btn_{{$product->id}}" 
                                        type="submit" 
                                        style="max-height: 46px;" id="ajaxSubmit_{{ $product->id }}"
                                        onclick="updateCart('{{ $product->id }}', '{{ $option->option_id }}')"
                                    >
                                        Add to cart
                                    </button>
                                    
                                    {{-- <div class="cart-total-{{ $product->id }} quantity_count_circle " style="display: none" data-product="{{$product->id}}" onclick="swap_quantity_input('{{ $product->id }}')">
                                        <span class="cart-total-number-{{$product->id }} mr-2"></span>
                                        Added to cart
                                    </div> --}}
                                @else
                                    
                                    @if (auth()->user())
                                        <input type="hidden" name="sku" id="sku_value" class="sku_value" value="{{$product->code}}">
                                        <input type="hidden" name="product_id" id="product_id_value" class="product_id_value" value="{{$product->id}}">
                                        <div class="row justify-content-center align-items-center">
                                            <div class="col-md-12">
                                                <button class="w-100 ml-0 bg-primary h-auto product-detail-button-cards text-uppercase notify_stock_btn_class rounded d-flex align-items-center justify-content-center"
                                                    type="button" id="" onclick="notify_user_about_product_stock('{{$product->id}}' , '{{$product->code}}')" data-product-id = {{$product->id}}>
                                                    <a class="text-white">Notify</a>
                                                    <div class="spinner-border text-white custom_stock_spinner stock_spinner_{{$product->id}} ml-1 d-none" role="status">
                                                        <span class="sr-only"></span>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <button class="w-100 ml-0 bg-primary h-auto product-detail-button-cards notify_stock_btn_class text-uppercase notify_popup_modal_btn rounded"
                                            type="button" id="notify_popup_modal" onclick="show_notify_popup_modal('{{$product->id}}' , '{{$product->code}}')">
                                            <a class="text-white">Notify</a>
                                        </button>
                                    @endif
                                    @if (!empty($enable_see_similar_products))
                                        <button class="w-100 ml-0 see-similar-order-button text-uppercase mt-2 rounded btn-sm" onclick="see_similar_products('{{ $product->id }}', '{{ $option->option_id }}')" data-bs-target="#see_similar_pop_up" style="max-height: 46px;">
                                            See Similar
                                        </button>
                                    @endif
                                @endif
                            @else
                                @if ($enable_add_to_cart)
                                    <div onclick="button_swap_quantity('{{ $product->id }}', '{{ $option->option_id }}')" class="col-sm-12 mt-0 button_swap_quantity button_swap_quantity_{{$product->id}} mb-2" id="button_swap_{{ $product->id }}">
                                        <div class="input-group">
                                            <div class="input-group-prepend custom-border qty_minus_mobile">
                                                <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="subtracting_quantity('{{ $product->id }}', '{{ $option->option_id }}')"><i class="fa fa-minus minus_qty_font qty_font"></i></button>
                                            </div>
                                            {{-- <input type="number" id="swap_qty_number_{{$product->id}}" name="swap_qty_number " onchange="update_qty_text('{{ $product->id }}', '{{ $option->option_id }}')" class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_{{$product->id}}"  style="font-weight: 500" min="1" max="{{$option->stockAvailable}}"> --}}
                                            <input type="number" id="swap_qty_number_{{$product->id}}" name="swap_qty_number" value="1"  class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_{{$product->id}}"  style="font-weight: 500" min="1" max="{{$option->stockAvailable}}">
                                            <div class="input-group-prepend custom-border qty_plus_mobile">
                                                <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="adding_quantity('{{ $product->id }}', '{{ $option->option_id }}')"><i class="fa fa-plus plus_qty_font qty_font"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <button 
                                        class="hover_effect prd_btn_resp ajaxSubmit button-cards col w-100  mb-1 original_cart_btn   original_cart_btn_{{$product->id}}" 
                                        type="submit" 
                                        style="max-height: 46px;" id="ajaxSubmit_{{ $product->id }}"
                                        onclick="updateCart('{{ $product->id }}', '{{ $option->option_id }}')"
                                    >
                                        Add to cart
                                    </button>
                                    
                                    {{-- <div class="cart-total-{{ $product->id }} quantity_count_circle " style="display: none" data-product="{{$product->id}}" onclick="swap_quantity_input('{{ $product->id }}')">
                                        <span class="cart-total-number-{{$product->id }} mr-2"></span>
                                        Added to cart
                                    </div> --}}
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
                            @endif
                        </div>
                    @else
                        <div class="col-md-12">
                            <button class="w-100 ml-0 call-to-order-button text-uppercase" style="max-height: 46px;">
                                Call To Order
                            </button>
                        </div> 
                    @endif
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
        min-height: 9rem;
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

    .notify_stock_btn_class {
        font-size: 15px;
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
        .custom_stock_spinner {
            height: 1rem !important;
            width: 1rem !important;
        }
    }

    .custom_stock_spinner {
        height: 1.5rem ;
        width: 1.5rem ;
    }
</style>
<script>
    // stock notification
    function show_notify_popup_modal (id , sku_value) {
        $('.notify_popup_modal').modal('show');
        $('.productId_value').val(id);
        $('.productSku_value').val(sku_value);
    } 
    function close_notify_user_modal () {
        $('.notify_popup_modal').modal('hide');
        $('.notify_stock_btn_class').each(function() {
            $(this).attr('disabled', false);
        });
    }
    
    function notify_user_about_product_stock (id , sku_value) {
        $('.notify_stock_btn_class').each(function() {
            var p_id = $(this).attr('data-product-id');
            if (p_id != id) {
                $(this).attr('disabled', true);
            }
        });
        var email = $('.notifyEmail').val();
        var sku = sku_value;
        var product_id = id;
        $('.stock_spinner_modal').removeClass('d-none');
        $('.stock_spinner_'+product_id).removeClass('d-none');
        if (email != '') {
            $('.email_required_alert').html('');
        }
        if (email == '') {
            $('.email_required_alert').html('Email is Required');
            $('.stock_spinner_modal').addClass('d-none');
            $('.stock_spinner_'+product_id).addClass('d-none');
            return false;
        }
        else {
            $.ajax({
                url: "{{ url('product-stock/notification') }}",
                method: 'post',
                data: {
                "_token": "{{ csrf_token() }}",
                    email : email,
                    sku : sku,
                    product_id : product_id
                },
                success: function(response){

                    if (response.status === true) {
                        $('.stock_spinner_modal').addClass('d-none');
                        $('.stock_spinner_'+product_id).addClass('d-none');
                        $('.notify_user_div').removeClass('d-none');
                        close_notify_user_modal();
                        $('.notify_text').html(response.message);
                    } else {
                        $('.stock_spinner_modal').addClass('d-none');
                        $('.stock_spinner_'+product_id).addClass('d-none');
                        $('.notify_user_div').removeClass('d-none');
                        $('.notify_text').html('Something went wrong!');
                    }
                },
                error: function(response) {
                    var error_message = response.responseJSON;
                    $('.stock_spinner_modal').addClass('d-none');
                    $('.stock_spinner_'+product_id).addClass('d-none');
                    $('.notify_user_div').addClass('d-none');
                    var error_text  = error_message.errors.email[0];
                    $('.email_required_alert').html(error_text)
                },
                complete: function() {
                    // Re-enable all buttons with class 'notify_stock_btn_class'
                    $('.notify_stock_btn_class').prop('disabled', false);
                }
            });
        }
    }
    
    function hide_notify_user_div() {
        $('.notify_text').html('');
        $('.notify_user_div').addClass('d-none');
    }
    // end
    function updateCart(id, option_id) {
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
        // updateBodyClickEventStatus(false);
        // $('#last_button_clicked').val(id);

        // $('.cart-total-' + id).addClass('added-to-cart');
        // $('#button_swap_' + id).addClass('btn-added-to-cart');
        // $('.added-to-cart').css('display', 'inline-flex');
        // $('.btn-added-to-cart').css('display', 'none');

        // $('.cart-total-' + id).css('display', 'none');
        // $('#button_swap_' + id).css('display', 'block');
        
        // $('#ajaxSubmit_'+id).addClass('d-none');
        // $('#button_swap_'+id).removeClass('d-none');

        // $('#swap_qty_number_'+id).val(1);
        // $('.quantity_count_circle').each(function() {
        //     var html = $(this);
        //     var spanContent = $(html).find('span');
        //     if (parseInt($(html).find('span').html()) > 0) {
        //         spanContent.parent().css('display', 'inline-flex');
        //     } else {
        //         spanContent.parent().css('display', 'none');
        //     }
        // });
        var itemnumberQuantity = $('.swap_qty_number_'+id).val();
        var newValue = itemnumberQuantity.replace(/^0+/, ''); // Remove leading zeros
        if (newValue === "") {
            newValue = 0; // Handle case where all characters were zeros
        }
        $('.swap_qty_number_'+id).val(newValue);
        var itemQuantity = $('.swap_qty_number_'+id).val();
        if (parseInt(itemQuantity) <= 0 || itemQuantity === '' || itemQuantity === null) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Quantity must be greater than 0 and not Empty!',
                timer: 3000,
                showConfirmButton: false,
                position: 'top',
                timerProgressBar: true
            });
            $('.swap_qty_number_'+id).val(1);
            return false;
        }
        jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_' + id).val(),
                // quantity: 1
                quantity: itemQuantity
            },
            success: function(response) {
                if (response.status == 'errror') {
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
                        icon: 'error',
                        title: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
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
                    // jQuery('.cart-total-number-' + id).html($('#swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html(response.actual_stock);
                    jQuery('.swap_qty_number_'+id).val(response.actual_stock);

                    var grand_total = 0;
                    var grand_total = parseFloat(cart_total);
                    var tax = cart_total * (tax_rate / 100);
                    var grand_total_include_tax = 0;
                    grand_total_include_tax = (tax + grand_total).toFixed(2);
                    if (response.free_postal_state == true) {
                        if (grand_total <= initial_free_shipping_value) {
                            $('.promotional_banner_div_congrats').addClass('d-none');
                            $('.promotional_banner_div').removeClass('d-none');
                            $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                        } else {
                            $('.promotional_banner_div').addClass('d-none');
                            $('.promotional_banner_div_congrats').removeClass('d-none');
                        }
                    }
                    else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').addClass('d-none');
                    } 
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: itemQuantity + 'X ' + '<span class="text-dark toast_title">'+ product_name+'</span>' + '<br/>'+ ' Added to your cart',
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
//     function swap_quantity_input(id) {
//       updateBodyClickEventStatus(false);
//       $('.quantity_count_circle').each(function() {
//             // var html = $(this).html();
//             var html = $(this);
//             var spanContent = $(html).find('span');
//             if (parseInt($(html).find('span').html()) > 0) {
//                 spanContent.parent().css('display', 'inline-flex');
//             } else {
//                 spanContent.parent().css('display', 'none');
//             }
//         });
//       $('.cart-total-'+id).css('display', 'none');
//       $('.btn-added-to-cart').css('display', 'none');
//       $('.quantity_count_circle').each(function() {
//             // var html = $(this).html();
//             var html = $(this);
//             var spanContent = $(html).find('span');
//             if (parseInt($(html).find('span').html()) > 0) {
//                 spanContent.parent().css('display', 'inline-flex');
//             } else {
//                 spanContent.parent().css('display', 'none');
//             }
//         });
//       $('#button_swap_'+id).css('display', 'block');
//       $('.cart-total-'+id).css('display', 'none');
//    }

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

    function see_similar_products(product_id  , option_id) {
        $.ajax({
            url: "{{ url('/see-similar-products/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                product_id,
                option_id,
            },
            success: function(response) {
                if (response.status == 'success') {
                    if (response.products.length === 0) {
                        $('#see_similar_pop_up').modal('hide');
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: response.message,
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'top',
                            timerProgressBar: true
                        });
                    } else {
                        $('#see_similar_pop_up').modal('show');
                        $('.similar_products_row-body').html(generateProductsHtml(response , response.products));
                    }
                } else {
                    $('#see_similar_pop_up').modal('hide');
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
            },

            error: function(response) {
                $('#see_similar_pop_up').modal('hide');
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: 'No similar products found',
                    timer: 3000,
                    showConfirmButton: false,
                    position: 'top',
                    timerProgressBar: true
                });
            }
        });
    }

    function generateProductsHtml(response , products) {
        var price_column = response.price_column;
        var htmlContent = `<div class="row">`;

        products.forEach(function(product , price_column) {
            var productHtml = `
                <div class="col-md-6 col-xl-6 col-lg-6 d-flex align-self-stretch mt-2 product_row_mobile_responsive pt-1 h-100">
                    <div class="p-2 shadow-sm w-100" style="background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125); border-radius: 0.25rem;">
            `;

            // Add subscribe button if contact_id is present
            if (response.contact_id) {
                productHtml += generateSubscribeButton(product.product_id, product.option_id , response.user_buy_list_options);
            }

            // Add image or placeholder
            productHtml += generateProductImage(product);
            
            var productName = product.products.name;  // Get the text of the product name
            var shortenedName = productName.length > 30 ? productName.substring(0, 20) + '...' : productName;
            // Add the rest of the product details
            productHtml += `
                <div class="card-body d-flex flex-column text-center mt-1 prd_mbl_card_bdy p-2">
                    <h5 class="card-title card_product_title tooltip-product" style="font-weight: 500; font-size: 16px;" data-title="${product.products.name}" id="product_name_${product.products.id}">
                        <a class="product-row-product-title" href="${window.location.origin +'/product-detail/' + product.products.id + '/' + product.option_id + '/' + product.products.slug}">
                            ${shortenedName}
                            <div class="tooltip-product-text bg-white text-primary">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner bg-white text-primary">
                                    <span>${productName}</span>
                                </div>
                            </div>
                        </a>
                    </h5>
                    <input type="hidden" name="p_id" id="p_${product.products.id}" value="${product.products.id}">
                </div>
            `;
            if (product.show_price === true && product.default_price !== null) {
                if ((product.default_price[response.price_column]  != null) || parseFloat(product.default_price[response.price_column])) > 0 {
                    var formattedPrice = formatNumber(parseFloat(product.default_price[response.price_column]));
                } else if ((product.default_price.sacramentoUSD  != null) || (parseFloat(product.default_price.sacramentoUSD))) > 0) {
                    var formattedPrice = parseFloat(product.default_price.sacramentoUSD);
                } else {
                    var formattedPrice = parseFloat(product.default_price.retailUSD);
                }
                productHtml += `
                    <h4 class="text-uppercase mb-0 text-center p_price_resp mt-0 mb-2">
                        $${formattedPrice}
                    </h4>
                `;
            }

            if (product.add_to_cart == true) {
                productHtml += `
                    <div class="col-sm-12 mt-0 button_swap_quantity button_swap_quantity_${product.products.id} mb-2" id="button_swap_${product.products.id}">
                        <div class="input-group">
                            <div class="input-group-prepend custom-border qty_minus_mobile">
                                <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="subtracting_quantity('${product.products.id}', '${product.option_id}')"><i class="fa fa-minus minus_qty_font qty_font"></i></button>
                            </div>
                            
                            <input type="number" id="swap_qty_number_${product.products.id}" name="swap_qty_number" value="1"  class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_${product.products.id}"  style="font-weight: 500" min="1" max="${product.stockAvailable}">
                            <div class="input-group-prepend custom-border qty_plus_mobile">
                                <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="adding_quantity('${product.products.id}', '${product.option_id}')"><i class="fa fa-plus plus_qty_font qty_font"></i></button>
                            </div>
                        </div>
                    </div>
                    <button 
                        class="hover_effect prd_btn_resp ajaxSubmit button-cards col w-100  mb-1 original_cart_btn   original_cart_btn_${product.products.id}" 
                        type="submit" 
                        style="max-height: 46px;" id="ajaxSubmit_${product.products.id}"
                        onclick="updateCart('${product.products.id}', '${product.option_id}')">
                        Add to cart
                    </button>
                `;
            } else {
                productHtml += `
                    <div class="col-md-12">
                        <button 
                            class="w-100 ml-0 call-to-order-button text-uppercase" 
                            style="max-height: 46px;">
                            Call To Order
                        </button>
                    </div>
                `;
            }
            

            productHtml += `</div></div>`;
            htmlContent += productHtml;
        });

        htmlContent += `</div>`;
        return htmlContent;
    }

    function generateSubscribeButton(productId, optionId , user_buy_list_options) {
        return `
            <a style="width:20px !important;" href="javascript:void(0);" class="ml-2 mt-2 subscribe">
                <i class="fa-solid fav-${optionId} fa-heart ${user_buy_list_options[optionId] ? '' : 'text-muted'}"
                id="${optionId}" data-toggle="popover"
                onclick="addToList('${productId}', '${optionId}', '${user_buy_list_options[optionId]}')">
                </i>
            </a>
        `;
    }

    function generateProductImage(product) {
        if (product.images != '') {
            return `
                <a href="${window.location.origin +'/product-detail/' + product.products.id + '/' + product.option_id + '/' + product.products.slug}">
                    <div class="image-height-mbl" style="min-height: 130px; max-height:130px;">
                        <span class="d-flex justify-content-center align-items-center">
                            <img src="${product.products.images}" class="img_responsive_mbl col-md-10 image-body offset-1 mt-2" style="min-height: 130px; max-height: 130px;" />
                        </span>
                    </div>
                </a>
            `;
        } else {
            return `
                <a href="${window.location.origin +'/product-detail/' + product.products.id + '/' + product.option_id + '/' + product.products.slug}">
                    <div class="image-height-mbl" style="min-height: 130px; max-height:130px;">
                        <span class="d-flex justify-content-center align-items-center">
                            <img src="${asset('theme/img/image_not_available.png')}" class="img_responsive_mbl_not_available col-md-10 image-body offset-1 mt-2"  style="min-height: 130px; max-height: 130px;" />
                        </span>
                    </div>
                </a>
            `;
        }
    }

    function formatNumber(value) {
        return parseFloat(value).toFixed(2);
    }


</script>






<div class="modal fade" id="see_similar_pop_up" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="see_similar_pop_up" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="see_similar_pop_up">Similar Products</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body similar_products_row-body px-2 py-1">
        </div>
        <div class="modal-footer p-1">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
