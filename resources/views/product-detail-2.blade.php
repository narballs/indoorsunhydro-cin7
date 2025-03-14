@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')

<div class="w-100 mb-2">
    <div class="alert alert-success alert-dismissible d-none mb-0 text-center notify_user_div_detail">
        <a href="#" onclick="hide_notify_user_div()" class="close" aria-label="close">&times;</a>
        <span class="notify_text_detail"></span>
    </div>
</div>
<div class="w-100 mx-0 row justify-content-center align-items-center" style="background-color: #008BD3;height:70px;">
    <p class="fw-bold fs-2 my-auto border-0 text-white text-center align-middle">
        PRODUCT DETAIL
    </p>
</div>
<div class="container mt-3">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @yield('content')
</div>
<?php 
    // dd($products_to_hide);
?>
<input type="hidden" value="{{App\Helpers\UserHelper::getUserPriceColumn()}}" id="get_column">
<input type="hidden" name="products_to_hide" id="products_to_hide"
    value="{{ htmlspecialchars(json_encode($products_to_hide)) }}">


    @php
    
    $add_to_cart = true;
    $show_price = true;
    $auth = false;
    $paymentTerms = false;
    if (!empty($products_to_hide)) {
        if (in_array($productOption->option_id, $products_to_hide)) {
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

    if (auth()->user()) {
        $auth = true;
        $contact = App\Models\Contact::where('user_id', auth()->user()->id)->first();
        if (empty($contact)) {
            $paymentTerms = false;
        }
        $contact_id_new = null; 
        if ($contact->is_parent == 1) {
            $contact_id_new = $contact->contact_id;
        } else {
            $contact_id_new = $contact->parent_id;
        }

        $get_main_contact = App\Models\Contact::where('id', $contact_id_new)->first();
        if (!empty($get_main_contact) && strtolower($get_main_contact->paymentTerms) == 'pay in advanced') {
            $paymentTerms = true;
        } else {
            $paymentTerms = false;
        }
    } else {
        $auth = false;
        $paymentTerms = false;
    }
@endphp

<input type="hidden" name="paymentTerms" id="paymentTerms" value="{{$paymentTerms === false ? 0 : 1}}">
<input type="hidden" name="auth_value" id="auth_value" value="{{$auth === false ? 0 : 1}}">



<div class="row justify-content-center">
    <div class="col-md-12 col-xl-10 col-lg-12 col-sm-12 col-xs-12 mt-3 mb-3">
        <div class="row justify-content-between ml-1">
            {{-- similar product partial --}}
            <div
                class="col-md-8 col-xl-4 col-xxl-4 col-lg-4 col-sm-12 col-xs-12 col-12 order-md-2 order-lg-1 order-xl-1 order-xs-2 order-2 ">
                <div class="card rounded buy_again_div px-3">
                    @include('partials.product-detail.similar-products')
                </div>
            </div>
            {{-- product detail --}}
            <div
                class="col-md-12 col-sm-12 col-xl-8 col-xxl-8 col-lg-8 col-xs-12 col-12 order-md-1 order-lg-2 order-xl-2 order-xs-1 order-1">
                @if ($customer_demand_inventory_number === 1)
                <div class="alert alert-success alert-dismissible mb-0 unprocess_alert p-1 rounded-0">
                    <div class="d-flex justify-content-between">
                        <span class="text-dark">Stock has been updated</span>
                        <a href="#" class="close p-1" data-dismiss="alert" aria-label="close">&times;</a>
                    </div>
                </div>
                @endif
                @if (!empty($productOption->products) && !empty($productOption->products->categories) &&
                $productOption->products->category_id != 0 && strtolower($productOption->products->categories->name) ===
                'grow medium')
                <p class="text-dark bg-warning text-md-center border m-0 font-weight-bold">
                    This product is excluded from california free shipping promotion
                </p>
                @elseif (!empty($productOption->products->categories->parent) &&
                !empty($productOption->products->categories->parent->name) &&
                strtolower($productOption->products->categories->parent->name) === 'grow medium')
                <p class="text-dark bg-warning text-md-center border m-0 font-weight-bold">
                    This product is excluded from california free shipping promotion
                </p>
                @endif

                <div class="card py-3 no-border">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
                            <div
                                class="product-detail-new-image-div text-center d-flex justify-content-center align-items-center">
                                <div class="row justify-content-center align-items-center">
                                    <div class="col-md-8">
                                        @if ($productOption->products->images)
                                        <img id="main-image" src="{{$productOption->products->images}}"
                                            class="img-fluid" />
                                        @else
                                        <img id="main-image" src="/theme/img/image_not_available.png"
                                            class="img-fluid" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12 product-detail-content">
                            <?php
                                $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
                                $retail_price = 0;
                                $ai_price = 0;
                                foreach($productOption->price as $price) {
                                    $retail_price = $price->$user_price_column;
                                    if ($retail_price == 0) {
                                        $retail_price = $price->sacramentoUSD;
                                    } 
                                    if ($retail_price == 0) {
                                        $retail_price = $price->retailUSD;
                                    }

                                    $ai_price = $price->enable_ai_price == 1 ? $price->aiPriceUSD : 0;
                                }
                            ?>
                            <div class="product-detail-heading col-xl-12 col-lg-12 col-md-12 col-xs-12 mb-2"
                                id="product_name">
                                <div class="row">
                                    <div class="col-md-11 col-10">
                                        <p class="product-detail-heading-text-new product_name_detail_page"
                                            data-title="{{$productOption->products->name}}" id="product-detail-id">
                                            {{$productOption->products->name}}</p>
                                    </div>
                                    @if (!empty($contact_id))
                                    <div class="col-md-1 col-2 d-flex justify-content-center mt-3 mt-lg-0">
                                        <a style="width:20px !important;" href="javascript:void(0);" class="subscribe">
                                            <i class="fa-solid fav-{{ $productOption->option_id }} fa-heart {{ isset($user_buy_list_options[$productOption->option_id]) ? '' : 'text-muted' }} "
                                                id="{{ $productOption->option_id }}" data-toggle="popover"
                                                onclick="addToList('{{ $productOption->product_id }}', '{{ $productOption->option_id }}', '{{ isset($user_buy_list_options[$productOption->option_id]) }}')">
                                            </i>
                                        </a>
                                    </div>
                                    @endif
                                    <div class="col-md-12">
                                        <span class="sku-div">
                                            <span class="product-detail-sku-head-new">SKU: </span>
                                            <span class="product-detail-sku-new">{{$productOption->code}}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row align-items-center">
                                    @if ($show_price == true)
                                    @php
                                        $allowAIPrice = App\Helpers\UserHelper::allowAiPricing($productOption);

                                        // Calculate discount percentage (avoiding division by zero)
                                        $getItemDiscountPercentage = ($retail_price > 0) ? (($retail_price - $ai_price) / $retail_price * 100) : 0;

                                        // Choose the final price based on AI pricing allowance: if allowed and a valid AI price exists, use it; otherwise, revert to retail.
                                        $finalPrice = ($allowAIPrice && $ai_price > 0) ? $ai_price : $retail_price;
                                    @endphp
                



                                    <div class="col-md-12">
                                        @if ($ai_price > 0 && $allowAIPrice == true)
                                            <div class="deal-container my-2">
                                                <p class="deal-text">Limited time deal</p>
                                                <div class="d-flex align-items-center">
                                                    <span class="discount-badge">{{'- ' . number_format($getItemDiscountPercentage, 2)}}%</span>
                                                    <span class="text-danger product-detail-price mx-2" id="product_price">
                                                        ${{number_format($ai_price, 2)}}
                                                    </span>
                                                </div>
                                                <span class="original-price">Original Price: ${{number_format($retail_price, 2)}}</span>
                                            </div>
                                        @else
                                            <span class="text-danger product-detail-price" id="product_price">
                                                ${{number_format($retail_price, 2)}}
                                            </span>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="col-md-12 ">
                                        <div class="my-2"> <span class="text-uppercase text-muted brand"></span>
                                            <div class="price d-flex flex-row align-items-center">
                                                @if ($productOption->products->status != 'Inactive')
                                                @if($stock_updated == true)
                                                <span class="text-success" data-toggle="popover-hover"
                                                    data-bs-container="body" data-placement="top"
                                                    data-bs-placement="top" data-bs-content="Top popover"
                                                    style=" cursor: pointer;"><span class="stock_number_new mr-2">
                                                        {{$total_stock}}</span></span>
                                                <div>
                                                    
                                                    <span class="instock-label-new">IN STOCK</span>
                                                </div>
                                                @elseif ($productOption->stockAvailable > 0)
                                                <span class="text-success" data-toggle="popover-hover"
                                                    data-bs-container="body" data-placement="top"
                                                    data-bs-placement="top" data-bs-content="Top popover"
                                                    style=" cursor: pointer;"><span class="stock_number_new mr-2">
                                                        {{$productOption->stockAvailable}}</span></span>
                                                <div>
                                                    
                                                    <span class="instock-label-new">IN STOCK</span>
                                                </div>
                                                @else
                                                <div>
                                                    
                                                    <span class="out-of-stock-label-new">
                                                        @if ((empty($get_wholesale_terms) || strtolower($get_wholesale_terms) != 'pay in advanced') && auth()->user())
                                                            On Back Order
                                                        @else
                                                            {{ App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT OF STOCK');}}
                                                        @endif
                                                    </span>
                                                </div>
                                                @endif
                                                @else
                                                <div>
                                                    <span class="text-danger">NOT
                                                        AVAILABLE FOR SALE</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @if ($customer_demand_inventory_number === 1)
                                        @if ($inventory_update_time_flag == true)
                                        @if($stock_updated)
                                        @if (!empty($locations))
                                        @foreach ($locations as $location)
                                        <div>
                                            <p class="mb-1">
                                                <i class="fa fa-map-marker mr-2"></i>{{$location['branch_name'] . ':'}}
                                                <span class="text-success">{{ $location['available'] >= 0 ?
                                                    $location['available'] : 0 }}</span>
                                            </p>
                                        </div>
                                        @endforeach
                                        @endif
                                        @endif
                                        @else
                                        @if (!empty($locations))
                                        @foreach ($locations as $location)
                                        <div>
                                            <p class="mb-1">
                                                <i class="fa fa-map-marker mr-2"></i>{{$location['branch_name'] . ':'}}
                                                <span class="text-success">{{ $location['available'] >= 0 ?
                                                    $location['available'] : 0 }}</span>
                                            </p>
                                        </div>
                                        @endforeach
                                        @endif
                                        @endif
                                        @else
                                        @if (!empty($locations))
                                        @foreach ($locations as $location)
                                        <div>
                                            <p class="mb-1">
                                                <i class="fa fa-map-marker mr-2"></i>{{$location['branch_name'] . ':'}}
                                                <span class="text-success">{{ $location['available'] >= 0 ?
                                                    $location['available'] : 0 }}</span>
                                            </p>
                                        </div>
                                        @endforeach
                                        @endif
                                        @endif
                                    </div>

                                    <form id="cart" class="mb-2 px-0">
                                        <input type="hidden" name="p_id" id="p_id"
                                            value="{{$productOption->products->id}}">
                                        <input type="hidden" name="option_id" id="option_id"
                                            value="{{$productOption->option_id}}">
                                        <input type="hidden" name="product_slug" id="product_slug"
                                            value="{{$productOption->products->code}}">
                                        @csrf
                                        <div class="col-md-12 col-xl-10">
                                            <div class="cart row mt-0 mt-md-3 justify-content-between">
                                                <input type="hidden" name="get_wholesale_terms" id="get_wholesale_terms"
                                                    value="{{$get_wholesale_terms}}">
                                                @if ($add_to_cart == true)
                                                    @if ((empty($get_wholesale_terms) || strtolower($get_wholesale_terms) != 'pay in advanced') && auth()->user())
                                                        <div class="col-md-4 col-5 col-lg-4 col-xl-4">
                                                            <div
                                                                class="d-flex align-items-center px-2 product-detail-quantity-increase-decrease-div">
                                                                <i class="fa fa-minus product-detail-quantity-decrease"></i>
                                                                <input type="number" name="quantity" id="quantity" min="1"
                                                                    max="{{$productOption->stockAvailable}}" step="1" value="1"
                                                                    class="text-center form-control product-detail-quantity-number-new mb-0">
                                                                
                                                                <i class="fa fa-plus product-detail-quantity-increase"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8 col-7 col-lg-8 col-xl-8">
                                                            <button type="button"
                                                                class="btn product-detail-add-to-cart-new w-100" type="button"
                                                                id="ajaxSubmit">
                                                                <a class="text-white">Add to cart </a>
                                                            </button>
                                                        </div>
                                                    @else
                                                        @if ($total_stock > 0 || $productOption->stockAvailable > 0)
                                                            <div class="col-md-4 col-5 col-lg-4 col-xl-4">
                                                                <div
                                                                    class="d-flex align-items-center px-2 product-detail-quantity-increase-decrease-div">
                                                                    <i class="fa fa-minus product-detail-quantity-decrease"></i>
                                                                    <input type="number" name="quantity" id="quantity" min="1"
                                                                        max="{{$productOption->stockAvailable}}" step="1" value="1"
                                                                        class="text-center form-control product-detail-quantity-number-new mb-0">
                                                                    
                                                                    <i class="fa fa-plus product-detail-quantity-increase"></i>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="{{$total_stock > 0 || $productOption->stockAvailable > 0 ? 'col-md-8 col-7 col-lg-8 col-xl-8' : 'col-12'}}">
                                                            @if (!empty($notify_user_about_product_stock) && strtolower($notify_user_about_product_stock->option_value) === 'yes')
                                                                @if ($total_stock > 0)
                                                                    <button type="button"
                                                                        class="btn product-detail-add-to-cart-new w-100" type="button"
                                                                        id="ajaxSubmit">
                                                                        <a class="text-white">Add to cart </a>
                                                                    </button>
                                                                @elseif ($productOption->stockAvailable > 0)
                                                                    <button type="button"
                                                                        class="btn product-detail-add-to-cart-new w-100" type="button"
                                                                        id="ajaxSubmit">
                                                                        <a class="text-white">Add to cart </a>
                                                                    </button>
                                                                @else
                                                                    @if (auth()->user())
                                                                        <input type="hidden" name="notify_user_email_input"
                                                                            class="notify_user_email_input" id="auth_user_email"
                                                                            value="{{auth()->user()->email}}">
                                                                        <input type="hidden" name="sku" id="sku_value" class="sku_value"
                                                                            value="{{$productOption->products->code}}">
                                                                        <input type="hidden" name="product_id" id="product_id_value"
                                                                            class="product_id_value"
                                                                            value="{{$productOption->products->id}}">
                                                                        <div class="row justify-content-between align-items-center">
                                                                            <div class="col-md-10">
                                                                                <button type="button"
                                                                                    class="product-detail-notify-new w-100" type="button"
                                                                                    id="" onclick="notify_user_about_product_stock()">
                                                                                    <a class="text-white">Notify When in Stock </a>
                                                                                </button>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <div class="spinner-border text-primary stock_spinner d-none"
                                                                                    role="status">
                                                                                    <span class="sr-only"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <button type="button"
                                                                            class="product-detail-notify-new w-100 notify_popup_modal_btn"
                                                                            type="button" id="notify_popup_modal"
                                                                            onclick="show_notify_popup_modal()">
                                                                            <a class="text-white">Notify When in Stock </a>
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <?php 
                                                                    // $enable_add_to_cart = App\Helpers\SettingHelper::enableAddToCart($productOption);
                                                                    $enable_add_to_cart = true;
                                                                ?>
                                                                @if ($enable_add_to_cart)
                                                                    <button class="btn product-detail-add-to-cart-new w-100"
                                                                        type="button" id="ajaxSubmit">
                                                                        <a class="text-white">Add to cart </a>
                                                                    </button>
                                                                @else
                                                                    <button class="product-detail-add-to-cart-new" type="button">
                                                                        <a class="text-white">Add to cart</a>
                                                                    </button>
                                                                    @endif
                                                                @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="col-md-12">
                                                        <button type="button" class="product-detail-call-to-order-new w-100">
                                                            Call To Order
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="text-uppercase text-muted brand"></span>
                                    </form>

                                    <div class="col-md-12 col-xl-10 my-3">
                                        @if (!empty($request_bulk_quantity_discount) &&
                                        strtolower($request_bulk_quantity_discount->option_value) === 'yes')
                                        <button type="button" href="" data-bs-toggle="modal"
                                            data-bs-target="#bulk_quantity_modal" id="bulk_discount_href"
                                            class="bulk_discount_href btn w-100">Buy in Bulk</button>
                                        @endif
                                    </div>
                                    @if (!empty($enable_see_similar_products) && $total_stock < 1  && $productOption->stockAvailable < 1)
                                        <div class="col-md-12 col-xl-10 my-3">
                                            <button type="button" class="see-similar-order-button-new btn w-100"
                                                onclick="see_similar_products('{{ $productOption->products->id }}', '{{ $productOption->option_id }}')"
                                                data-bs-target="#see_similar_pop_up_detail">
                                                See Similar
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if (!empty($productOption->option1) || !empty($productOption->option2) || !empty($productOption->option3))
                                <div class="col-md-12 mt-3 mb-3">
                                    @php
                                    $image_src = [];
                                    $options_array = ['option1', 'option2', 'option3'];
                                    $image_type_array = ['case' => 'case.png', 'pallet' => 'pallet.png', 'box' => 'box.png'
                                    , 'pack' => 'case.png' ];
                                    foreach ($options_array as $option) {
                                    foreach ($image_type_array as $key => $image_type) {
                                    if (strpos(strtolower($productOption[$option]), $key) !== false) {
                                    $image_src[$option] = $image_type;
                                    }
                                    }
                                    }

                                    @endphp
                                    <div class="row align-items-center">
                                        <div class="col-md-3 col-xl-2 col-3">
                                            @if (isset($image_src['option1']))
                                            <img src="{{asset('theme/bootstrap5/images/' . $image_src['option1'] )}}"
                                                class="img-fluid"/>
                                            @endif
                                        </div>
                                        <div class="col-md-9 col-xl-10 col-9">
                                            <p class="mb-0">{{ $productOption->option1 }}</p>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-3 col-xl-2 col-3">
                                            @if (isset($image_src['option2']))
                                            <img src="{{asset('theme/bootstrap5/images/' . $image_src['option2'] )}}"
                                                class="img-fluid"/>
                                            @endif
                                        </div>
                                        <div class="col-md-9 col-xl-10 col-9">
                                            <p class="mb-0">{{ $productOption->option2 }}</p>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-3 col-xl-2 col-3">
                                            @if (isset($image_src['option3']))
                                            <img src="{{asset('theme/bootstrap5/images/' . $image_src['option3'] )}}"
                                                class="img-fluid"/>
                                            @endif
                                        </div>
                                        <div class="col-md-9 col-xl-10 col-9">
                                            <p class="mb-0">{{ $productOption->option3 }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        
                        
                        <div class="row mt-2">
                            @if (!empty($enable_image_scrapping) && strtolower($enable_image_scrapping->option_value) === 'yes')
                                <div class="col-md-12 my-3 d-inline-flex align-items-center">
                                    <button type="button" class="scrape_product_image mr-2" onclick="scrape_product_image('{{ $productOption->products->id}}')">
                                        Look for more images with AI
                                        <span class="scrape_product_image_icon">
                                            ✦ ✦
                                            ✦ ✦
                                        </span>
                                    </button>
                                    <div class="spinner-border text-success d-none" id="scrape_product_image_loader" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            @endif
                            <div class="mb-2 mb-md-1">
                                <div class="p-1 bg-custom-mobile-background">
                                    <span class="category-title-heading  bg-custom-background p-2">Category :
                                        @if($pname)
                                            <span class="category-title mt-4 ps-2">{{$pname}}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-1">
                                <div class="p-1">
                                    <span class="product-weight-heading p-2">Weight :
                                        <span class="product-weight-data">{{!empty($productOption->optionWeight) ? $productOption->optionWeight .
                                            'lbs' : ''}}</span>
                                    </span>
                                </div>
                            </div>
                            @if (isset($productOption->products->width) || isset($productOption->products->height) || isset($productOption->products->length))
                                <div class="mb-2 mb-md-1">
                                    <div class="p-1">
                                        <span class="product-dimension-heading p-2">
                                            Dimensions:
                                            <span class="product-dimension-data">
                                                {{ isset($productOption->products->length) ? $productOption->products->length . ' x ' : '' }}
                                                {{ isset($productOption->products->width) ? $productOption->products->width . ' x ' : '' }}
                                                {{ isset($productOption->products->height) ? $productOption->products->height : '' }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="">
                                <div class="p-1">
                                    <div class="category-description category-description px-2 py-0">
                                        <span>Description</span></div>
                                    <div class="p-1">
                                        <span class="about product-details-description mt-2 product_description">
                                            {!! $productOption->products->description !!}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!empty($ai_setting) && (strtolower($ai_setting->option_value) == 'yes'))
                            <div class="row ai_row">
                                <div class="col-md-12 col-xl-12 col-lg-12 col-sm-12 col-xs-12 mt-3 mb-3">
                                    <div class="card my-3 w-100 mx-2">
                                        <div class="card-header ai_row_title bg-light">
                                            <h5 class="card-title mb-0 ai-section-heading-title">Need help? Simply ask and get real-time answers</h5>
                                        </div>
                                        <div class="card-body ai_row_card_body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control border-right-0 ai_text_field" id="ai_text_field" onfocus="mark_arrow_border_green()" placeholder="Ask any question about this product!" aria-label="Ask Refus About this product" aria-describedby="basic-addon2">
                                                        <span class="input-group-text circle-right-ai border-left-0 bg-transparent" type="button"  id="basic-addon2"><i class="fa-solid fa-circle-arrow-right"></i></span>
                                                    </div>
                                                    <div class="text-danger ai_error"></div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="spinner-grow ai_spinner d-none" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <p class="ai_content px-2"></p>
                                                    <button class="btn clear_prompt btn-sm ml-2 d-none mt-3" type="button" onclick="clear_prompt()">
                                                        Clear
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @if (count($ai_questions) > 0)
                                            <div class="card-footer chat_gpt_search_footer py-3 px-1">
                                                <div class="col-md-12 ai_row_footer d-flex flex-wrap justify-content-start">
                                                    @foreach($ai_questions as $question)
                                                        <span class="ai_questions mx-3 w-auto add_custom_question" 
                                                            title="{{ $question->question }}" 
                                                            onclick="add_custom_question(this);">
                                                            <strong class="ai_question_strong">{{ $question->question }}</strong>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="popover-form" class="inventory_pop_over_form">
    <form id="myform" class="form-inline p-0 w-100" role="form">
        <div class="form-group" style="width:800px">
            <div style="font-family: 'Poppins';
                    font-style: normal;
                    font-weight: 400;
                    font-size: 14px;
                    padding:1px;
                    color: white;
                    max-width:800px;
                    z-index:9999;
                ">
                <span class="inventory_info" style="width: 800px !important">
                    @if ($customer_demand_inventory_number === 1)
                    @if ($inventory_update_time_flag == true)
                    @if (!$stock_updated)
                    Unable to show accurate stock levels.<br />
                    @endif
                    @if (!empty($locations))
                    @foreach ($locations as $location)
                    {{ $location['branch_name'] }}: {{ $location['available'] >= 0 ? $location['available'] : 0 }}<br />
                    @endforeach
                    @else
                    Unable to show accurate stock levels.<br />
                    @endif
                    @else
                    @if (!empty($locations))
                    @foreach ($locations as $location)
                    {{ $location['branch_name'] }}: {{ $location['available'] >= 0 ? $location['available'] : 0 }}<br />
                    @endforeach
                    @else
                    Unable to show accurate stock levels.<br />
                    @endif
                    @endif
                    @else
                    @if (!empty($locations))
                    @foreach ($locations as $location)
                    {{ $location['branch_name'] }}: {{ $location['available'] >= 0 ? $location['available'] : 0 }}<br />
                    @endforeach
                    @else
                    Unable to show accurate stock levels.<br />
                    @endif
                    @endif
                </span>
            </div>
        </div>
    </form>
</div>




<!-- Modal -->
<div class="modal fade notify_popup_modal_detail" id="notify_user_modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notify User About Product Stock</h5>
                <button type="button" class="close" onclick="close_notify_user_modal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="hidden" name="sku" id="sku_value" class="sku_value"
                                value="{{$productOption->products->code}}">
                            <input type="hidden" name="product_id" id="product_id_value" class="product_id_value"
                                value="{{$productOption->products->id}}">
                            <div class="col-md-12">
                                <input type="text" name="notify_user_email" id="notify_user_email"
                                    class="form-control notify_user_email_input" placeholder="Enter your email">
                                <div class="text-danger email_required_alert_detail"></div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="spinner-border text-primary stock_spinner_modal d-none" role="status">
                    <span class="sr-only"></span>
                </div>
                <button type="button" class="btn btn-secondary"
                    onclick="notify_user_about_product_stock()">Submit</button>
                <!-- You can add additional buttons here if needed -->
            </div>
        </div>
    </div>
</div>
{{-- notify user pop up modal end --}}

@include('partials.similar_products_slider')
<div class="modal fade notify_popup_modal_similar_portion" id="notify_user_modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notify User About Product Stock Portion</h5>
                <button type="button" class="close" onclick="close_notify_user_modal_similar()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="hidden" name="sku" id="sku_value" class="similar_productSku_value" value="">
                            <input type="hidden" name="product_id" id="product_id_value" class="similar_productId_value"
                                value="">
                            <div class="col-md-12">
                                <input type="text" name="notify_user_email" id="notify_user_email"
                                    class="form-control similar_notifyEmail_sidebar" value=""
                                    placeholder="Enter your email">
                                <div class="text-danger email_required_alert_similar"></div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="spinner-border text-primary stock_spinner_modal_similar d-none" role="status">
                    <span class="sr-only"></span>
                </div>
                <button type="button" class="btn btn-secondary"
                    onclick="notify_user_about_product_stock_similar_portion ($('.similar_productId_value').val() , $('.similar_productSku_value').val())">Submit</button>
                <!-- You can add additional buttons here if needed -->
            </div>
        </div>
    </div>
</div>
{{-- notify user pop up modal end --}}


{{-- bulk qty modal --}}
<div class="modal fade" id="bulk_quantity_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <button class="btn btn-sm modal-title bg-white bulk_close_btn" id="close_bulk_model" type="button"
                    data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-angle-left mr-2"></i>Back to
                    Product</button>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <form action="">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group border-bottom">
                                <div class="row">
                                    <h5 class="bulk_head">
                                        Use this form to request a bulk quote discount for commercial quantities.
                                    </h5>
                                    <p class="bulk_paragraph">
                                        This bulk quote feature should not be used for purchases of less than $5,000.
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="" class="bulk_label">
                                        {{-- What item(s) are you interested in? --}}
                                        Product Name & Sku <span class="text-danger">*</span>
                                    </label>
                                    {{-- <p class="bulk_paragraph">
                                        Please list any and all items you’re interested in. Example: Clonex Rooting Gel,
                                        FoxFarm Marine Cuisine Dry Fertilizer, 20 lbs..
                                    </p> --}}
                                    {{-- <span>
                                        <strong class="text-danger mb-1">(Separate each item with a comma)</strong>
                                    </span> --}}
                                    {{-- <input type="text" name="items_list" id="bulk_product_list"
                                        class="form-control bulk_input"> --}}
                                    <textarea type="text" readonly name="items_list" id="bulk_product_list"
                                        class="form-control bulk_input" cols="10" rows="3">{{!empty($productOption->products->name) ? $productOption->products->name  : ''}} 
{{'Sku'}}: {{!empty($productOption->products->code) ? $productOption->products->code : ''}}
                                    </textarea>
                                    <div class="text-danger" id="bulk_product_list_error"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="" class="bulk_label">
                                        What quantity would you like quoted out? <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control bulk_input" required name="quantity"
                                        id="bulk_quantity" placeholder="e.g 1000">
                                    <div class="text-danger" id="bulk_quantity_error"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="" class="bulk_label">
                                        What is your phone number? <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control bulk_input" required
                                        value="{{!empty(auth()->user())  && !empty($active_contact) ? $active_contact->phone : ''}}"
                                        name="phone_number" id="bulk_phone_number"
                                        placeholder="Type your phone number here...">
                                    <div class="text-danger" id="bulk_phone_number_error"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="" class="bulk_label">
                                        What is your email address? <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control bulk_input" required name="email"
                                        value="{{!empty(auth()->user())  && !empty($active_contact) ? $active_contact->email : ''}}"
                                        id="bulk_email" placeholder="name@example.com">
                                    <div class="text-danger" id="bulk_email_error"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="" class="bulk_label">
                                        What is your name? <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control bulk_input" required name="name"
                                        value="{{!empty(auth()->user())  && !empty($active_contact) ? $active_contact->firstName . ' ' . $active_contact->lastName  : ''}}"
                                        id="bulk_name" placeholder="Type your name here...">
                                    <div class="text-danger" id="bulk_name_error"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="" class="bulk_label">
                                        Where will these items be delivered? <span>(Optional)</span>
                                    </label>
                                    <p class="bulk_paragraph">
                                        Please put City, State, and Country e.g. (California, USA)
                                    </p>
                                    <input type="text" class="form-control bulk_input" name="delievery"
                                        id="bulk_delievery" placeholder="Type your answer here...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <div class="spinner-border bulk_loader d-none" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <button type="button" class="btn btn-primary submit_bulk_discount"
                            onclick="saveBulkQuantityDiscount()">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- bulk qty modal end --}}
<div class="modal fade" id="see_similar_pop_up_detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="see_similar_pop_up_detail" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="see_similar_pop_up_detail">Similar Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div
                class="modal-body similar_products_row-body_detail  d-flex justify-content-center align-items-center p-2">
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Scrape Image Modal -->
<div class="modal fade" id="scrapeImageModal" tabindex="-1" role="dialog" aria-labelledby="scrapeImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Images</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="scrapedImagesContainer" class="row"></div>
            </div>
        </div>
    </div>
</div>
{{-- Scrape Image Modal End --}}
@include('partials.product-footer')
@include('partials.footer')
@include('partials.product-detail-scripts')