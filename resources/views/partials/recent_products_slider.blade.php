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
        display: block !important;
    }
</style>
@if (!empty($product_views) && count($product_views) > 0)
    <div class="w-100  mt-3">
        <p class="recent_view_header fw-bold fs-2 my-auto border-0 text-white text-center align-middle text-uppercase p-2 mb-0">
            Recently viewed products
        </p>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="owl-carousel owl-theme mt-4" id="similar_products_owl_carasoul">
                    @foreach($product_views as $recent_products)
                        @foreach ($recent_products->product->options as $option)
                            @php
                                $product = $recent_products->product; 
                            @endphp
                            <div class="item mt-2  pt-1 ">
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
@endif