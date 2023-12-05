@if (!empty($product_views_chunks) && count($product_views_chunks) > 0)
    <div class="w-100 home-page-product-section mt-3">
        <p class="recent_view_header fw-bold fs-2 my-auto border-0 text-white text-center align-middle text-uppercase p-2 mb-0">
            your recently viewed products
        </p>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row align-items-center">
                    <div class="col-md-1">
                        <a class="btn-sm left-right-angle-button-slider" href="#carouselExampleIndicators_recent" role="button" data-slide="prev">
                            <i class="fa fa-angle-left arrow_icon text-dark" style="font-size: 1.5rem;"></i>
                        </a>
                    </div>
                    <div class="col-md-10 p-0">
                        <div id="carouselExampleIndicators_recent" class="carousel slide">
                            <div class="carousel-inner">
                                @foreach($product_views_chunks  as $product_views_chunk)
                                <div class="carousel-item all_items">
                                    <div class="row mt-5">
                                        @foreach($product_views_chunk as $recent_products)
                                            @foreach ($recent_products->product->options as $option)
                                                @php
                                                    $product = $recent_products->product; 
                                                @endphp
                                                <div class="col-md-6 col-lg-4 col-xl-3 d-flex align-self-stretch mt-2 product_row_mobile_responsive pt-1">
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
                                                                <div class="image-height-mbl"  style="height: 300px;">
                                                                    <span class="d-flex justify-content-center align-items-center">
                                                                        <img src=" {{ asset('theme/img/image_not_available.png') }}" class="img_responsive_mbl_not_available col-md-10 .image-body offset-1 mt-2"
                                                                        style="" />
                                                                    </span>
                                                                </div>
                                                            </a>
                                                        @endif
                                                        <div class="card-body d-flex flex-column text-center mt-2 prd_mbl_card_bdy">
                                                            <h5 class="card-title card_product_title tooltip-product" style="font-weight: 500;font-size: 16px;" id="product_name_{{ $product->id }}">
                                                                <a class="product-row-product-title" href="{{ url('product-detail/' . $product->id . '/' . $option->option_id . '/' . $product->slug) }}">
                                                                    {{ \Illuminate\Support\Str::limit($product->name, 33) }}
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
                                                                @endif
                                                                @if ($bought_products_count > 0)
                                                                    <small class="text-dark ft-size">{{$bought_products_count . '  bought in the past month'}}</small>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-12 add-to-cart-button-section">
                                                                @if ($enable_add_to_cart)
                                                                    <button 
                                                                        class="hover_effect prd_btn_resp ajaxSubmit button-cards col w-100  mb-1" 
                                                                        type="submit" 
                                                                        style="max-height: 46px;" id="ajaxSubmit_{{ $product->id }}"
                                                                        onclick="updateCart('{{ $product->id }}', '{{ $option->option_id }}')"
                                                                    >
                                                                        Add to cart
                                                                    </button>
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
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <ol class="carousel-indicators mb-0 mt-4 position-relative top-0 d-flex align-items-center">
                                @foreach ($product_views_chunks as $key=> $recent_prod_li )
                                    <li data-target="#carouselExampleIndicators_recent" data-slide-to="{{$key}}" class="{{$key==0 ? 'active' : '' }}"></li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex justify-content-end">
                        <a class="btn-sm left-right-angle-button-slider" href="#carouselExampleIndicators_recent" role="button" data-slide="next">
                            <i class="fa fa-angle-right arrow_icon text-dark" style="font-size: 1.5rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif