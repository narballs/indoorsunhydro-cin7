<style>
    .slider_image_div {
        max-height: 250px;
        min-height: 250px;
    }
    .ft-size-slider-product {
        font-size: 12px;
    }
    .button-cards-product-slider {
        background-color: #7BC533 !important;
        color: #ffffff !important;
        border: 1px solid #7BC533 !important;
        font-size: 14px;
    }
    #similar_products_owl_carasoul .owl-nav.disabled {
        display: block;
    }
    #similar_products_owl_carasoul .owl-stage-outer {
        display:flex;
        justify-content: center !important;
    }
    .notify_stock_btn_class {
        font-size: 15px;
    }
    .custom_stock_spinner {
        height: 1.5rem ;
        width: 1.5rem ;
    }

    @media screen and (max-width:550px)  and (min-width: 280px){
        
        .custom_stock_spinner {
            height: 1rem !important;
            width: 1rem !important;
        }
    }

    
</style>
{{-- @if (!empty($similar_products) && count($similar_products) > 0)
    <div class="w-100  mt-3">
        <p class="recent_view_header fw-bold fs-2 my-auto border-0 text-white text-center align-middle text-uppercase p-2 mb-0">
            Similar Products
        </p>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="owl-carousel owl-theme similar_products_owl_carasoul mt-4" id="similar_products_owl_carasoul">
                    @foreach($similar_products as $similar_product)
                        @foreach ($similar_product->options as $option)
                            @php
                                $product = $similar_product; 
                            @endphp
                            <div class="item mt-2  pt-1 similar_items_div ">
                                <div class="p-2 shadow-sm  w-100" style="background-color: #fff;
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
                                            <div class="image-height-mbl slider_image_div">
                                                <span class="d-flex justify-content-center align-items-center">
                                                    <img src="{{ $product->images }}" class="img_responsive_mbl col-md-10 .image-body offset-1 mt-2"
                                                    style="max-height:250px;" />
                                                </span>
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                                            <div class="image-height-mbl slider_image_div">
                                                <span class="d-flex justify-content-center align-items-center">
                                                    <img src=" {{ asset('theme/img/image_not_available.png') }}" class="img_responsive_mbl_not_available col-md-10 .image-body offset-1 mt-2"
                                                    style="" />
                                                </span>
                                            </div>
                                        </a>
                                    @endif
                                    <div class="card-body d-flex flex-column text-center mt-2 prd_mbl_card_bdy p-0">
                                        <h5 class="card-title card_product_title tooltip-product" style="font-weight: 500;font-size: 16px;" id="product_name_{{ $product->id }}">
                                            <a class="product-row-product-title" href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                                                {{ \Illuminate\Support\Str::limit($product->name, 20) }}
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
                                        <div class="col-md-12">
                                            @php
                                                $similar_product_option = App\Models\ProductOption::where('option_id', $option->option_id)->first();
                                            @endphp
                                            @if (!empty($similar_product_option) && $similar_product_option->stockAvailable > 0)
                                                <div>
                                                    <span class="text-success">{{'In Stock'}}</span>
                                                </div>
                                            @else
                                                <div>
                                                    <span class="text-danger">{{ App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT OF STOCK');
                                                        }}</span>
                                                </div>
                                            @endif
                                        </div>
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
                                                // $enable_add_to_cart = App\Helpers\SettingHelper::enableAddToCart($option); 
                                                $enable_add_to_cart = true;
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
                                                <p class="text-dark mb-0 ft-size ft-size-slider-product">{{$last_month_views}}</p>
                                            @endif
                                            @if ($bought_products_count > 0)
                                                <small class="text-dark mb-0 ft-size ft-size-slider-product">{{$bought_products_count . '  bought in the past month'}}</small>
                                            @endif
                                        </div>
                                        <div class="col-md-12 add-to-cart-button-section">
                                            @if ($enable_add_to_cart)
                                                <button 
                                                    class="btn hover_effect prd_btn_resp ajaxSubmit button-cards-product-slider col w-100  mb-1" 
                                                    type="submit" id="ajaxSubmit_{{ $product->id }}"
                                                    onclick="updateCart('{{ $product->id }}', '{{ $option->option_id }}')"
                                                >
                                                    Add to cart
                                                </button>
                                            @else
                                                <button 
                                                    class="btn prd_btn_resp ajaxSubmit mb-1 text-white bg-danger bg-gradient button-cards-product-slider col w-100 autocomplete=off"
                                                    tabindex="-1" 
                                                    type="submit" id="ajaxSubmit_{{ $product->id }}"
                                                    disabled 
                                                    onclick="return updateCart('{{ $product->id }}')">Out of Stock</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif --}}
@if (!empty($best_selling_products) && count($best_selling_products) > 0)
    <div class="w-100  mt-3">
        <p class="recent_view_header fw-bold fs-2 my-auto border-0 text-white text-center align-middle text-uppercase p-2 mb-0">
            Best Selling Products
        </p>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="owl-carousel similar_products_owl_carasoul owl-theme mt-4" id="similar_products_owl_carasoul">
                    @foreach($best_selling_products->take(24) as $best_selling_product)
                        @foreach ($best_selling_product->product->options as $option)
                            @php
                                $product = $best_selling_product->product; 
                            @endphp
                            @if (!empty($product->categories) && ($product->categories->is_active == 1))
                                <div class="item mt-2  pt-1 similar_items_div">
                                    <div class="p-2 shadow-sm  w-100" style="background-color: #fff;
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
                                                <div class="image-height-mbl slider_image_div">
                                                    <span class="d-flex justify-content-center align-items-center">
                                                        <img src="{{ $product->images }}" class="img_responsive_mbl col-md-10 .image-body offset-1 mt-2"
                                                            style="max-height:250px;" />
                                                    </span>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                                                <div class="image-height-mbl slider_image_div">
                                                    <span class="d-flex justify-content-center align-items-center">
                                                        <img src=" {{ asset('theme/img/image_not_available.png') }}" class="img_responsive_mbl_not_available col-md-10 .image-body offset-1 mt-2"
                                                        style="" />
                                                    </span>
                                                </div>
                                            </a>
                                        @endif
                                        <div class="card-body d-flex flex-column text-center mt-2 prd_mbl_card_bdy p-0">
                                            <h5 class="card-title card_product_title tooltip-product" style="font-weight: 500;font-size: 16px;" id="product_name_{{ $product->id }}">
                                                <a class="product-row-product-title" href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                                                    {{ \Illuminate\Support\Str::limit($product->name, 20) }}
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
                                            <div class="col-md-12">
                                                @php
                                                    $similar_product_option = App\Models\ProductOption::where('option_id', $option->option_id)->first();
                                                @endphp
                                                @if (!empty($similar_product_option) && $similar_product_option->stockAvailable > 0)
                                                    <div>
                                                        <span class="text-success">{{'In Stock'}}</span>
                                                    </div>
                                                @else
                                                    <div>
                                                        <span class="text-danger">{{ App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT OF STOCK');
                                                            }}</span>
                                                    </div>
                                                @endif
                                            </div>
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
                                                    // $enable_add_to_cart = App\Helpers\SettingHelper::enableAddToCart($option); 
                                                    $enable_add_to_cart = true;
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
                                                    <p class="text-dark mb-0 ft-size ft-size-slider-product">{{$last_month_views}}</p>
                                                @endif
                                                @if ($bought_products_count > 0)
                                                    <small class="text-dark ft-size ft-size-slider-product">{{$bought_products_count}} {{'bought in the past month'}}</small>
                                                @endif
                                            </div>
                                            <div class="col-md-12 add-to-cart-button-section">
                                                @if (!empty($notify_user_about_product_stock) && strtolower($notify_user_about_product_stock->option_value) == 'yes')
                                                    @if ($option->stockAvailable > 0)
                                                        <button 
                                                            class="btn hover_effect prd_btn_resp p-2 ajaxSubmit button-cards-product-slider col w-100  mb-1" 
                                                            type="submit" id="ajaxSubmit_{{ $product->id }}"
                                                            onclick="updateCart('{{ $product->id }}', '{{ $option->option_id }}')">
                                                            Add to cart
                                                        </button>
                                                    @else
                                                        @if (auth()->user())
                                                            <input type="hidden" name="sku" id="sku_value" class="sku_value" value="{{$product->code}}">
                                                            <input type="hidden" name="product_id" id="product_id_value" class="product_id_value" value="{{$product->id}}">
                                                            <div class="row justify-content-center align-items-center">
                                                                <div class="col-md-12">
                                                                    <button class="w-100 ml-0 bg-primary h-auto product-detail-button-cards text-uppercase notify_stock_btn_class rounded d-flex align-items-center justify-content-center"
                                                                        type="button" id="" onclick="notify_user_about_product_stock_similar('{{$product->id}}' , '{{$product->code}}')" data-product-id = {{$product->id}}>
                                                                        <a class="text-white">Notify</a>
                                                                        <div class="spinner-border text-white custom_stock_spinner stock_spinner_{{$product->id}} ml-1 d-none" role="status">
                                                                            <span class="sr-only"></span>
                                                                        </div>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <button class="w-100 ml-0 bg-primary h-auto product-detail-button-cards notify_stock_btn_class text-uppercase notify_popup_modal_btn rounded"
                                                                type="button" id="notify_popup_modal" onclick="show_notify_popup_modal_similar('{{$product->id}}' , '{{$product->code}}')">
                                                                <a class="text-white">Notify</a>
                                                            </button>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($enable_add_to_cart)
                                                        <button 
                                                            class="btn hover_effect prd_btn_resp p-2 ajaxSubmit button-cards-product-slider col w-100  mb-1" 
                                                            type="submit" id="ajaxSubmit_{{ $product->id }}"
                                                            onclick="updateCart('{{ $product->id }}', '{{ $option->option_id }}')"
                                                        >
                                                            Add to cart
                                                        </button>
                                                    @else
                                                        <button 
                                                            class="btn prd_btn_resp p-2 ajaxSubmit mb-1 text-white bg-danger bg-gradient button-cards-product-slider col w-100 autocomplete=off"
                                                            tabindex="-1" 
                                                            type="submit" id="ajaxSubmit_{{ $product->id }}"
                                                            disabled 
                                                            onclick="return updateCart('{{ $product->id }}')">Out of Stock</button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
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
        jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_' + id).val(),
                option_id: option_id,
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
    // stock notification
    function show_notify_popup_modal_similar (id , sku_value) {
        $('.notify_popup_modal_similar').modal('show');
        $('.productId_value').val(id);
        $('.productSku_value').val(sku_value);
    } 
    function close_notify_user_modal_similar () {
        $('.notify_popup_modal_similar').modal('hide');
        $('.notify_stock_btn_class').each(function() {
            $(this).attr('disabled', false);
        });
    }
    
    function notify_user_about_product_stock_similar (id , sku_value) {
        $('.notify_stock_btn_class').each(function() {
            var p_id = $(this).attr('data-product-id');
            if (p_id != id) {
                $(this).attr('disabled', true);
            }
        });
        var email = $('.similar_notifyEmail').val();
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
                        close_notify_user_modal_similar();
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
</script>