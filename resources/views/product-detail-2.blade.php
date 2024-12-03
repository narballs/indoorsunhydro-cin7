@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<style>
    .product-detail-new-image-div {
        width: 100%;
        height: 100%;
        border: 1px solid #DFDFDF66;
    }

    .no-border {
        border: none;
    }

    .product-detail-sku-head-new {
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins';
        line-height: 21px;
    }

    .product-detail-sku-new {
        font-size: 14px;
        font-weight: 400;
        font-family: 'Poppins';
        line-height: 21px;
    }

    .sku-div {
        background-color: #7CC633;
        color: #fff;
        padding: 5px 10px;
        display: inline-flex;
    }

    '
 .product-detail-heading-text-new {
        font-size: 28px;
        font-weight: 600;
        font-family: 'Poppins';
    }

    .stock_number_new {
        font-size: 18px;
        font-weight: 400;
        font-family: 'Poppins';
        color: #37B34A
    }

    .instock-label-new {
        font-size: 18px;
        font-weight: 400;
        font-family: 'Poppins';
        color: #37B34A;
    }

    .out-of-stock-label-new {
        font-size: 18px;
        font-weight: 400;
        font-family: 'Poppins';
        color: #DE1919;
    }

    .product-detail-quantity-increase-decrease-div {
        border: 1px solid #798490;
    }

    .product-detail-quantity-number-new {
        border: none;
        font-size: 20px;
        font-weight: 400;
        font-family: 'Poppins';
    }

    .product-detail-quantity-number-new:focus {
        box-shadow: 0 0 0 0rem rgba(0, 123, 255, .25)
    }

    .product-detail-quantity-increase,
    .product-detail-quantity-decrease {
        font-size: 14px;
        color: #798490;
        cursor: pointer;
    }

    .product-detail-call-to-order-new,
    .product-detail-notify-new {
        background-color: #008BD3;
        color: #fff;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 600;
        font-family: 'Poppins';
        cursor: pointer;
        text-transform: uppercase;
        border-radius: 0px;
        border: none;
    }

    .see-similar-order-button-new,
    .product-detail-add-to-cart-new {
        background-color: #7CC633;
        color: #fff;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 600;
        font-family: 'Poppins';
        cursor: pointer;
        text-transform: uppercase;
        border-radius: 0px;
    }

    .see-similar-order-button-new:hover {
        background-color: #7CC633;
        color: #fff;
        border: none;
    }

    .bulk_discount_href:hover {
        text-decoration: none;
        color: #fff;
        border: none;
    }
    .ai_questions {
        padding: 10px;
        border: 1px solid #7cc63e;
        border-radius: 20px;
        background: #7cc63e;
        color: #fff;
        font-size: 14px;
        font-weight: 300;
        font-family: 'poppins';
    }
    .circle-right-ai {
        color: #7cc63e;
        font-size: 20px;
        font-family: 'poppins';
        font-weight: 400;
        
    }
    .ai_content {
        font-family: 'poppins';
        font-weight: 300;
        font-size: 14px;
    }

    .circle-right-ai:focus {
        border-color: transparent;
    }

    .ai_text_field {
        font-family: 'poppins';
        font-weight: 400;
        font-size: 14px;
        
    }

    .ai_spinner {
        color: #7cc63e;
        border: 1px solid #7cc63e;
    }

    .ai_text_field:focus  {
        border-color: #7cc63e;
        box-shadow: 0 0 0 0rem rgba(124, 198, 62, 0.25);
        
    }
    .clear_prompt , .clear_prompt:hover , .clear_prompt:focus , .clear_prompt:active {
        font-family: 'poppins';
        font-weight: 400;
        font-size: 14px;
        color: #7cc63e;
        border: 1px solid #7cc63e;
        border-radius: 20px;
        background: #fff;
    }
    .add_custom_question {
        cursor: pointer;
    }
    @media only screen and (max-width: 768px) {
        .ai_row {
            background-color: #fff !important;
            margin-left: 1rem;
            margin-right: 1rem;
        }
        .ai_row_title {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .ai_row_card_body {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .ai_row_footer {
            padding-left: 0rem !important;
            padding-right: 0rem !important;
        }

        .add_custom_question {
            margin-top : 0.5rem !important;
            margin-bottom : 0.5rem !important;
        }
        
    }
</style>

@php
// $enable_see_similar_products = App\Helpers\SettingHelper::getSetting('enable_see_similar_products', 'Yes');
$enable_see_similar_products = App\Models\AdminSetting::where('option_name', 'enable_see_similar_products')
->where('option_value', 'Yes')
->first();
@endphp
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

<input type="hidden" name="paymentTerms" id="paymentTerms" value="{{$paymentTerms}}">
<input type="hidden" name="auth_value" id="auth_value" value="{{$auth}}">


<div class="row justify-content-center w-100">
    <div class="col-md-12 col-xl-10 col-lg-12 col-sm-12 col-xs-12 mt-3 mb-3">
        <div class="row justify-content-center ml-1">
            {{-- similar product partial --}}
            <div
                class="col-md-8 col-xl-4 col-xxl-4 col-lg-4 col-sm-12 col-xs-12 col-12 order-md-2 order-lg-1 order-xl-1 order-xs-2 order-2">
                <div class="card rounded buy_again_div">
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
                        <div class="col-xl-4 col-lg-3 col-md-12 col-sm-12 col-xs-12">
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
                        <div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-xs-12 product-detail-content">
                            <?php
                                $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
                                $retail_price = 0;
                                foreach($productOption->price as $price) {
                                    $retail_price = $price->$user_price_column;
                                    if ($retail_price == 0) {
                                        $retail_price = $price->sacramentoUSD;
                                    } 
                                    if ($retail_price == 0) {
                                        $retail_price = $price->retailUSD;
                                    }
                                }
                            ?>
                            <div class="product-detail-heading col-xl-12 col-lg-12 col-md-12 col-xs-12 mb-2"
                                id="product_name">
                                <div class="row">
                                    <div class="col-md-11">
                                        <p class="product-detail-heading-text-new product_name_detail_page"
                                            data-title="{{$productOption->products->name}}" id="product-detail-id">
                                            {{$productOption->products->name}}</p>
                                    </div>
                                    @if (!empty($contact_id))
                                    <div class="col-md-1 d-flex justify-content-center">
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
                                    <div class="col-md-12">
                                        <span class="text-danger product-detail-price" id="product_price">
                                            ${{number_format($retail_price, 2)}}
                                        </span>
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
                                                    style=" cursor: pointer;"><span class="stock_number_new">
                                                        {{$total_stock}}</span></span>
                                                <div>
                                                    <small class="dis-price">&nbsp;</small>
                                                    <span class="instock-label-new">IN STOCK</span>
                                                </div>
                                                @elseif ($productOption->stockAvailable > 0)
                                                <span class="text-success" data-toggle="popover-hover"
                                                    data-bs-container="body" data-placement="top"
                                                    data-bs-placement="top" data-bs-content="Top popover"
                                                    style=" cursor: pointer;"><span class="stock_number_new">
                                                        {{$productOption->stockAvailable}}</span></span>
                                                <div>
                                                    <small class="dis-price">&nbsp;</small>
                                                    <span class="instock-label-new">IN STOCK</span>
                                                </div>
                                                @else
                                                <div>
                                                    <small class="dis-price">&nbsp;</small>
                                                    <span class="out-of-stock-label-new">{{
                                                        App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT
                                                        OF STOCK');
                                                        }}</span>
                                                </div>
                                                @endif
                                                @else
                                                <div>
                                                    <small class="dis-price">&nbsp;</small><span class="text-danger">NOT
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
                                        @csrf
                                        <div class="col-md-12 col-xl-10">
                                            <div class="cart row mt-3 justify-content-between">
                                                @if ($add_to_cart == true)
                                                    <div class="col-md-3">
                                                        <div
                                                            class="d-flex align-items-center px-2 product-detail-quantity-increase-decrease-div">
                                                            <i class="fa fa-minus product-detail-quantity-decrease"></i>
                                                            <input type="number" name="quantity" id="quantity" min="1"
                                                                max="{{$productOption->stockAvailable}}" step="1" value="1"
                                                                class="text-center form-control product-detail-quantity-number-new">
                                                            <input type="hidden" name="p_id" id="p_id"
                                                                value="{{$productOption->products->id}}">
                                                            <input type="hidden" name="option_id" id="option_id"
                                                                value="{{$productOption->option_id}}">
                                                            <input type="hidden" name="product_slug" id="product_slug"
                                                                value="{{$productOption->products->code}}">
                                                            <i class="fa fa-plus product-detail-quantity-increase"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
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
                                    @if (!empty($enable_see_similar_products) && $total_stock === 0 && $productOption->stockAvailable === 0)
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
                            <div class="col-md-12 mt-3 mb-3">
                                @if (!empty($productOption->option1) || !empty($productOption->option2) ||
                                !empty($productOption->option3))
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
                                    <div class="col-md-3 col-xl-2">
                                        @if (isset($image_src['option1']))
                                        <img src="{{asset('theme/bootstrap5/images/' . $image_src['option1'] )}}"
                                            style="max-width: 40px;" />
                                        @endif
                                    </div>
                                    <div class="col-md-9 col-xl-10">
                                        <p class="mb-0">{{ $productOption->option1 }}</p>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-3 col-xl-2">
                                        @if (isset($image_src['option2']))
                                        <img src="{{asset('theme/bootstrap5/images/' . $image_src['option2'] )}}"
                                            style="max-width: 40px;" />
                                        @endif
                                    </div>
                                    <div class="col-md-9 col-xl-10">
                                        <p class="mb-0">{{ $productOption->option2 }}</p>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-3 col-xl-2">
                                        @if (isset($image_src['option3']))
                                        <img src="{{asset('theme/bootstrap5/images/' . $image_src['option3'] )}}"
                                            style="max-width: 40px;" />
                                        @endif
                                    </div>
                                    <div class="col-md-9 col-xl-10">
                                        <p class="mb-0">{{ $productOption->option3 }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="row">
                                <div class="col-xl-4 col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <span class="category-title-heading">Category :</span>
                                    @if($pname)
                                    <span class="category-title mt-4 ps-2">{{$pname}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <span><strong>Weight :</strong>
                                        <span>{{!empty($productOption->optionWeight) ? $productOption->optionWeight .
                                            'lbs' : ''}}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                @if (!empty($productOption->products->width) && !empty($productOption->products->height)
                                && !empty($productOption->products->length))
                                <div class="col-xl-4 col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <span>
                                        <strong>Dimensions : </strong>
                                        <span>
                                            {{!empty($productOption->products->length) ?
                                            $productOption->products->length . ' ' . "x" : ''}}
                                            {{!empty($productOption->products->width) ? $productOption->products->width
                                            . ' ' . "x" : ''}}
                                            {{!empty($productOption->products->height) ?
                                            $productOption->products->height : ''}}
                                        </span>
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="category-description category-description mt-1  lh-lg">
                                    <span>Description</span></div>
                                <div class="">
                                    <span class="about product-details-description mt-2 product_description">
                                        {!! $productOption->products->description !!}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if (!empty($ai_setting) && (strtolower($ai_setting->option_value) == 'yes'))
                            <div class="row ai_row">
                                <div class="col-md-12 col-xl-8 col-lg-12 col-sm-12 col-xs-12 mt-3 mb-3">
                                    <div class="card my-3 w-100">
                                        <div class="card-header ai_row_title bg-light">
                                            <h5 class="card-title mb-0">Looking for Specific Info ?</h5>
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
                                            <div class="card-footer py-3 px-1">
                                                <div class="col-md-12 ai_row_footer d-flex justify-space-evenly">
                                                    {{-- <div class="row align-items-center"> --}}
                                                        @foreach($ai_questions as $question)
                                                        {{-- <div class="col-xl-4 col-lg-4 col-md-6 col-12 mt-2 add_custom_question" title="{{$question->question}}"  onclick="add_custom_question(this);"> --}}
                                                            <span class="ai_questions mx-3 w-auto add_custom_question" title="{{$question->question}}"  onclick="add_custom_question(this);">
                                                                <strong class="ai_question_strong">{{ \Illuminate\Support\Str::limit($question->question, 40) }}</strong>
                                                            </span>
                                                        {{-- </div> --}}
                                                        @endforeach
                                                    {{-- </div> --}}
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
<script>
    // mark border green on focus
    function mark_arrow_border_green() {
        $('.input-group-text').css('border-color', '#7cc63e');
    }
    // stock notification for similar products
    function show_notify_popup_modal_similar_portion (id , sku_value) {
        $('.notify_popup_modal_similar_portion').modal('show');
        $('.similar_productId_value').val(id);
        $('.similar_productSku_value').val(sku_value);
    } 
    function close_notify_user_modal_similar () {
        $('.notify_popup_modal_similar_portion').modal('hide');
        $('.notify_stock_btn_class').each(function() {
            $(this).attr('disabled', false);
        });
    }
    
    function  notify_user_about_product_stock_similar_portion  (id , sku_value) {
        $('.notify_stock_btn_class').each(function() {
            var p_id = $(this).attr('data-product-id');
            if (p_id != id) {
                $(this).attr('disabled', true);
            }
        });
        var email = $('.similar_notifyEmail_sidebar').val();
        var sku = sku_value;
        var product_id = id;
        $('.stock_spinner_modal_similar').removeClass('d-none');
        $('.stock_spinner_'+product_id).removeClass('d-none');
        if (email != '') {
            $('.email_required_alert_similar').html('');
        }
        if (email == '') {
            $('.email_required_alert_similar').html('Email is Required');
            $('.stock_spinner_modal_similar').addClass('d-none');
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
                        $('.stock_spinner_modal_similar').addClass('d-none');
                        $('.stock_spinner_'+product_id).addClass('d-none');
                        $('.notify_user_div_detail').removeClass('d-none');
                        close_notify_user_modal_similar();
                        $('.notify_text_detail').html(response.message);
                    } else {
                        $('.stock_spinner_modal_similar').addClass('d-none');
                        $('.stock_spinner_'+product_id).addClass('d-none');
                        $('.notify_user_div_detail').removeClass('d-none');
                        $('.notify_text_detail').html('Something went wrong!');
                    }
                },
                error: function(response) {
                    var error_message = response.responseJSON;
                    $('.stock_spinner_modal_similar').addClass('d-none');
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
        $('.notify_text_detail').html('');
        $('.notify_user_div_detail').addClass('d-none');
    }
    // end
</script>

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
                                        class="form-control bulk_input" cols="10" rows="3">{{!empty($productOption->products->name) ? $productOption->products->name : ''}} 
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
@include('partials.product-footer')
@include('partials.footer')
<script>
    document.getElementById('ai_text_field').addEventListener('focusout', function() {
        $('.circle-right-ai').css('border-color', '#ced4da');
    });
</script>
<style>
    .bulk_close_btn {
        border: 1px solid #CDCDCD;
        border-radius: 6px;
        font-size: 18px;
        color: #8F8F8F;
        font-family: 'poppins';
        font-weight: 400;
    }

    .bulk_close_btn:hover {
        color: #8F8F8F;
    }

    .submit_bulk_discount:hover {
        background-color: #7CC633;
        border-color: #7CC633;
        color: #fff;
    }

    .submit_bulk_discount:focus {
        background-color: #7CC633;
        border-color: #7CC633;
        color: #fff;
    }

    .submit_bulk_discount:active {
        background-color: #7CC633;
        border-color: #7CC633;
        color: #fff;
    }

    .submit_bulk_discount {
        background-color: #7CC633;
        border-color: #7CC633;
        color: #fff;
    }

    .product_description,
    p,
    h5,
    h4,
    h3,
    h2,
    h1 {
        font-family: 'Poppins';
        border: none;
        font-style: normal;
    }

    .product_description,
    h5,
    h4,
    h3,
    h2,
    h1 {
        font-size: 1rem;
        border: none;
        font-style: normal;
    }

    .product_description,
    em {
        font-style: normal;
    }

    .product_description,
    p {
        margin-bottom: 0rem !important;
    }

    .bulk_head {
        font-family: 'Poppins';
        font-size: 20px;
        font-weight: 600;
        color: #242424;

    }

    .bulk_paragraph {
        font-family: 'Poppins';
        font-size: 16px;
        font-weight: 400;
        color: #828282;
        paragraph-spacing: 16.02px;

    }

    .bulk_label {
        font-family: 'Poppins';
        font-size: 18px !important;
        font-weight: 500;
        color: #242424;
    }

    .bulk_input {
        font-family: 'Poppins';
        font-size: 18px;
        font-weight: 400;
        color: #828282;

    }

    .bulk_discount {
        font-family: 'Poppins';
        font-size: 22px;
        font-weight: 500;

    }

    .bulk_discount_text {
        font-family: 'Poppins';
        font-size: 18px;
        font-weight: 400;
    }

    .bulk_discount_href {
        font-family: 'Poppins';
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        color: #fff;
        background-color: #F4D130;
        border-radius: 0px;
        padding: 10px 20px;
    }

    .greyed {
        background: #eaeaea;
    }

    .update_inventory_number {
        color: #7bc533;
        font-family: 'poppins';
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 21px;
        letter-spacing: 0.55px;
        border: 1px solid #dae0e5;
    }

    .update_inventory_number:hover {
        color: #7bc533;
    }

    .buy_again_heading {
        color: #242424;
        font-family: 'Poppins';
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }

    .product_name {
        color: #000;
        font-family: 'Poppins';
        font-size: 14.669px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }

    .product_price {
        color: #DC4E41;
        font-family: 'Poppins';
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
    }

    .category_name {
        color: #8A8A8A;
        font-family: 'Poppins';
        font-size: 11.002px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        letter-spacing: 0.55px;
        text-transform: uppercase;
    }

    .buy_frequent_again_btn {
        flex-shrink: 0;
        border-radius: 6px;
        background: #7BC533;
        box-shadow: 0px 2.474916458129883px 3.712374687194824px 0px rgba(0, 0, 0, 0.08);
        color: #FFF;
        text-align: center;
        font-family: 'Poppins';
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 21.037px;
        /* 150.263% */
    }

    .buy_frequent_again_btn_call_to_order {
        flex-shrink: 0;
        border-radius: 6px;
        background-color: #008bd3;
        box-shadow: 0px 2.474916458129883px 3.712374687194824px 0px rgba(0, 0, 0, 0.08);
        color: #FFF;
        text-align: center;
        font-family: 'Poppins';
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 21.037px;
        /* 150.263% */
    }

    .border-div {
        height: 1.237px;
        background: #E1E1E1;
        width: 90% !important;
    }

    .buy_again_div {
        border: 1px solid #D7D7D721;
    }

    .search_row_my_account_page {
        margin-top: 0px;
    }

    .buy_again_product_image {
        height: 80px;
        width: 80px;
    }

    .notify_stock_btn_class {
        border-radius: 6px;
    }

    @media screen and (max-width: 600px) {
        .update_inventory_number {
            color: #7bc533;
            font-family: 'poppins';
            font-size: 12px;
            font-style: normal;
            font-weight: 400;
            line-height: 21px;
            letter-spacing: 0.55px;
            padding: 3px;
        }

        .update_inventory_number:hover {
            color: #7bc533;
        }

        .bulk_discount {
            font-family: 'Poppins';
            font-size: 15px;
            font-weight: 500;

        }

        .bulk_discount_text {
            font-family: 'Poppins';
            font-size: 12px;
            font-weight: 400;
        }

        .bulk_discount_href {
            font-family: 'Poppins';
            font-size: 12px;
            font-weight: 400;
            text-decoration: none;
            color: #fff;
            background-color: #F4D130;
            border-radius: 0px;
        }

        .bulk_head {
            font-family: 'Poppins';
            font-size: 15px;
            font-weight: 600;
            color: #242424;

        }

        .bulk_paragraph {
            font-family: 'Poppins';
            font-size: 12px;
            font-weight: 400;
            color: #828282;
            paragraph-spacing: 16.02px;

        }

        .bulk_label {
            font-family: 'Poppins';
            font-size: 12px !important;
            font-weight: 500;
            color: #242424;
        }

        .bulk_input {
            font-family: 'Poppins';
            font-size: 12px;
            font-weight: 400;
            color: #828282;

        }

    }

    .my-actions-class {
        margin: 0.2rem !important;
    }
</style>
<script>
    jQuery(document).ready(function(){
        jQuery(document).on('click', '#ajaxSubmit' , function(e) {
            var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
            var tax = 0;
            var tax_rate = parseFloat($('#tax_rate_number').val());
            $.ajaxSetup({
            });
            jQuery.ajax({
                url: "{{ url('add-to-cart') }}",
                method: 'post',
                data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_id').val(),
                option_id: jQuery('#option_id').val(),
                quantity: jQuery('#quantity').val()
                },
                success: function(response){
                var cart_total = 0;
                var total_cart_quantity = 0;
                
                if(response.status == 'error'){
                    var cart_items = response.cart_items;

                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var code =item.code;
                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseInt(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$'+subtotal);
                        
                    }
                    $src = $('#main-image').attr('src');
                    var product_name = document.getElementById("product_name").innerHTML;
                    var product_price = document.getElementById("product_price").innerHTML;
                    
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: response.message,
                        timer: 3000,
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
                if(response.status == 'success'){
                    var cart_items = response.cart_items;

                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var code =item.code;
                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseInt(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$'+subtotal);
                        
                    }
                    $src = $('#main-image').attr('src');
                    var product_name = document.getElementById("product_name").innerHTML;
                    var product_price = document.getElementById("product_price").innerHTML;
                    var productName = $('#product-detail-id').attr('data-title');
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
                        title: jQuery('#quantity').val() + 'X ' + '<span class="text-dark toast_title">'+ productName+'</span>' + '<br/>'+ '<div class="added_tocart">Added to your cart</div>',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true,
                        customClass: {popup: 'short-toast-popup'}
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#cart_items_quantity').html(total_cart_quantity);
                $('.cartQtyipad').html(total_cart_quantity);
                $('.cartQtymbl').html(total_cart_quantity);
                $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
                    
            }, 
            error: function(response) {
                console.log(response.responseJSON);
                var error_message = response.responseJSON;
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: error_message.message,
                        timer: 3000,
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
            });
        });

        });
        //   jQuery('<div class="quantity-nav"><div class="quantity-div quantity-up">&#xf106;</div><div class="quantity-div quantity-down">&#xf107</div></div>').insertAfter('.quantity input');
        jQuery('.quantity').each(function () {
            var spinner = jQuery(this),
                desktop_input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = desktop_input.attr('min'),
                max = desktop_input.attr('max');

            btnUp.click(function () {
                var oldValue = parseInt(desktop_input.val());
                if (oldValue >= max) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue + 1;
                }
                spinner.find("input[id=quantity]").val(newVal);
                let stock = jQuery(".stock_number_new").html();
                let stock_number_new = parseInt(stock);
                if (newVal === stock_number_new) {
                    btnUp.addClass('greyed');

                } else {
                    btnUp.removeClass('greyed');
                    btnDown.removeClass('greyed');
                }
            //   spinner.find("input[id=quantity").trigger("change");
            });

            btnDown.click(function () {
                // alert('hi');
                var oldValue = parseInt(desktop_input.val());
                if (oldValue <= min) {
                var newVal = oldValue;
                } else {
                var newVal = oldValue - 1;
                }
                spinner.find("input[id=quantity]").val(newVal);
                let stock = jQuery(".stock_number_new").html();
                let stock_number_new = parseInt(stock);
                if (newVal !== stock_number_new) {
                    btnUp.removeClass('greyed');
                    btnDown.removeClass('greyed');

                } 
                if (newVal == 1) {
                    btnDown.addClass('greyed');
                }
            //   spinner.find("input").trigger("change");
            });

            desktop_input.change(function() {
                var input_qty = parseInt($(this).val());
                var qty_stock_number_new = parseInt($(this).attr('max'));
                if (input_qty > qty_stock_number_new) {
                    $(this).val(qty_stock_number_new);
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: 'Quantity must be less than or equal to stock quantity',
                        timer: 3000,
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                } else {
                    $(this).val(input_qty);
                }
            })

        });
        //mobile
        jQuery(document).on('click', '#ajaxSubmit_mbl' , function(e) {
            var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
            var tax = 0;
            var tax_rate = parseFloat($('#tax_rate_number').val());
            $.ajaxSetup({
            });
            jQuery.ajax({
                url: "{{ url('add-to-cart') }}",
                method: 'post',
                data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_id').val(),
                option_id: jQuery('#option_id').val(),
                quantity: jQuery('.mobile_qty').val()
                },
                success: function(response){
                if(response.status == 'error'){
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var code =parseFloat(item.code)
                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseInt(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$'+subtotal);
                        
                    }
                    $src = $('#main-image').attr('src');
                    var product_name = document.getElementById("product_name").innerHTML;
                    var product_price = document.getElementById("product_price").innerHTML;
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        // title: jQuery('.mobile_qty').val() + ' X ' + product_name + '<div class="text-dark fw-bold fs-5">'+ product_price +'</div>'+ '<br>' + 'added to your cart',
                        timer: 2000,
                        text: response.message,
                        // imageUrl: "{{asset('theme/img/add_to_cart_gif.gif')}}",
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                }
                if(response.status == 'success'){
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var code =parseFloat(item.code)
                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseInt(item.quantity);
                        var subtotal = parseInt(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$'+subtotal);
                        
                    }
                    $src = $('#main-image').attr('src');
                    var product_name = document.getElementById("product_name").innerHTML;
                    var product_price = document.getElementById("product_price").innerHTML;

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
                        // title: jQuery('.mobile_qty').val() + ' X ' + product_name + '<div class="text-dark fw-bold fs-5">'+ product_price +'</div>'+ '<br>' + 'added to your cart',
                        timer: 2000,
                        text: 'Product added to cart',
                        // imageUrl: "{{asset('theme/img/add_to_cart_gif.gif')}}"
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#cart_items_quantity').html(total_cart_quantity);
                $('.cartQtyipad').html(total_cart_quantity);
                $('.cartQtymbl').html(total_cart_quantity);
                $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
                    
            }});

        });
        // jQuery('.qty_mbl_holder').each(function () {
            // var spinner = jQuery(this),
            var mobile_input = jQuery('.mobile_qty');
            var btnUp = jQuery('.up_qty_mbl');
            var btnDown = jQuery('.down_qty_mbl');
            var min = mobile_input.attr('min');
            var max = mobile_input.attr('max');

            btnUp.click(function () {
                var oldValue = parseInt(mobile_input.val());
                if (oldValue >= max) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue + 1;
                }
                mobile_input.val(newVal);
                let stock = jQuery(".stock_number_new").html();
                let stock_number_new = parseInt(stock);
                if (newVal === stock_number_new) {
                    btnUp.addClass('greyed');

                } else {
                    btnUp.removeClass('greyed');
                    btnDown.removeClass('greyed');
                }
            //   spinner.find("mobile_input[id=quantity").trigger("change");
            });

            btnDown.click(function () {
                // alert('hi');
                var oldValue = parseInt(mobile_input.val());
                if (oldValue <= min) {
                var newVal = oldValue;
                } else {
                var newVal = oldValue - 1;
                }
                mobile_input.val(newVal);
                let stock = jQuery(".stock_number_new").html();
                let stock_number_new = parseInt(stock);
                if (newVal !== stock_number_new) {
                    btnUp.removeClass('greyed');
                    btnDown.removeClass('greyed');

                } 
                if (newVal == 1) {
                    btnDown.addClass('greyed');
                }
            //   spinner.find("input").trigger("change");
            });

            mobile_input.change(function() {
                var input_value = $(this).val();
                var stock_number_new = $(this).attr('max');
                if (input_value >= stock_number_new) {
                    $(this).val(stock_number_new);
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: 'Quantity must be less than or equal to stock quantity',
                        timer: 3000,
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                } else {
                    $(this).val(input_value);
                }
            });

        // });
    // });
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
    function similar_product_add_to_cart(id, option_id) {
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
        jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: id,
                option_id: option_id,
                quantity: 1,
            },
            success: function(response) {
                if (response.status == 'error') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        var quantity = parseInt(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        jQuery('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = jQuery('#prd_name_' + id).html();
                    }

                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: response.message,
                        timer: 3000,
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
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
                        var quantity = parseInt(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        jQuery('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = jQuery('#prd_name_' + id).html();
                    }
                    var productName = jQuery('#prd_name_' + id).attr('data-title');
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
                        title: 1 + 'X ' + '<span class="text-dark toast_title">'+ productName+'</span>' + '<br/>'+ '<div class="added_tocart">Added to your cart</div>',
                        timer: 3000,
                        customClass: {popup: 'short-toast-popup'},
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);

                $('#cart_items_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });
    }
    
    function saveBulkQuantityDiscount() {
        var product_name_bulk = $('#bulk_product_list').val();
        var quantity_bulk  =  $('#bulk_quantity').val();
        var phone_number_bulk =  $('#bulk_phone_number').val();
        var email_bulk =  $('#bulk_email').val();
        var username_bulk =  $('#bulk_name').val();
        var delievery = $('#bulk_delievery').val();

        if (product_name_bulk == '' || quantity_bulk == '' || phone_number_bulk == '' || email_bulk == '' || username_bulk == '') {
            Swal.fire({
                html: '<i class="fa-solid fa-circle-xmark" style="color:#d33;font-size:40px;margin-bottom:10px;"></i><br><span style="font-size:16px;font-family:poppins">Please fill all Required fields</span>',
                customClass: {
                    popup: 'my-popup-class',
                    actions: 'my-actions-class'
                },
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#d33'
            });
            return;
        }

        $('.bulk_loader').removeClass('d-none');
        _token: '{{ csrf_token() }}'

        $.ajax({
            type: "POST",
            url: "{{ route('bulk_products_request') }}",
            data: {
                items_list: product_name_bulk,
                quantity: quantity_bulk,
                phone_number: phone_number_bulk,
                email: email_bulk,
                name: username_bulk,
                delievery: delievery,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#bulk_quantity_modal').modal('hide');
                $('.bulk_loader').addClass('d-none');
                Swal.fire({
                    html: '<i class="fa-solid fa-circle-check" style="color:#7CC633;font-size:40px;margin-bottom:10px;"></i><br><strong style="font-family:poppins">Bulk order request has been submitted.</strong><br/><span style="font-size:16px;font-family:poppins">We will get in touch with you as soon as we can.</span><br/><span style="font-size:16px;font-family:poppins">Thank you for your patience</span>',
                    customClass: {
                        popup: 'my-popup-class',
                        actions: 'my-actions-class'
                    },
                    confirmButtonText: 'Continue Shopping',
                    confirmButtonColor: '#7CC633'
                });
                $('.inventory_pop_over_form').removeClass('d-none');
                // Clear input fields
                // $('#bulk_product_list').val('');
                // $('#bulk_product_list').tagsinput('removeAll');
                $('#bulk_quantity').val('');
                // $('#bulk_phone_number').val('');
                // $('#bulk_email').val('');
                // $('#bulk_name').val('');
                $('#bulk_delievery').val('');
            },
            error: function(xhr, status, error) {
                $('.bulk_loader').addClass('d-none');
                Swal.fire({
                    html: '<i class="fa-solid fa-circle-xmark" style="color:#d33;font-size:40px;margin-bottom:10px;"></i><br><span style="font-size:16px;font-family:poppins">There was an error submitting the form</span>.<br/> <span style="font-size:16px;font-family:poppins"> Please try again later.</span>',
                    customClass: {
                        popup: 'my-popup-class',
                        actions: 'my-actions-class'
                    },
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#d33'
                });
            }
        });
    }

    
</script>

<script>
    $(document).ready(function() {
        $('#bulk_discount_href').click(function(e) {
            e.preventDefault();
            $('.inventory_pop_over_form').addClass('d-none');
        });

        $('#close_bulk_model').click(function(e) {
            e.preventDefault();
            $('.inventory_pop_over_form').removeClass('d-none');
        });

        var p_id= jQuery('#p_id').val();
        var option_id=jQuery('#option_id').val();
        var slug= jQuery('#product_slug').val();
        var auth_user = $('.notifyEmail').val() === '' ? null : $('.notifyEmail').val();    
        loadSimilarProducts(1);
        
        function loadSimilarProducts(page) {
            
            $.ajax({
                url: '/products/' + p_id + '/' + option_id + '/' + slug + '/get-similar-products?page=${page}',
                method: 'GET',
                data: { page: page },
                dataType: 'json',
                success: function(response) {
                    
                    if (response.data.length > 0) {
                        var html = buildSimilarProductsHtml(response);
                        $('#products-container').html(html);
                        updateSimilarProductsPaginationLinks();
                    } else {
                        var html = '<div class="row"><div class="col-md-12"><p class="buy_again_heading">No similar products found</p></div></div>';
                        $('#products-container').html(html);
                    }
                }
            });
        }

        function updateSimilarProductsPaginationLinks() {
            $('body').on('click', '.pagination-link', function(e) { 
                e.preventDefault();
                // $('html, body').animate({ scrollTop: 0 }, 'slow');
                var page = $(this).text();
                loadSimilarProducts(page);
            });
            
        }

        function buildSimilarProductsHtml(response) {
            var html = '';
            var data = response.data;
            var header = '<div class="row"><div class="col-md-12"><p class="buy_again_heading">Similar Products</p></div></div>';
            html += header;
            for (var i = 0; i < data.length; i++) {
                html += buildProductRow(data[i]);
            }

            var totalPages = response.last_page;
            var currentPage = response.current_page;
            var paginationHtml = $('#pagination-list').html(generatePaginationLinks(totalPages, currentPage));
            // html += paginationHtml;

            return html;
        }

        function buildProductRow(productData) {
            var rowHtml = '<div class="row mt-4 mb-3">';
            rowHtml += '    <div class="col-md-12">';
            rowHtml += '        <div class="row">';
            rowHtml += buildImageColumn(productData.images);
            rowHtml += buildDataColumn(productData);
            rowHtml += '        </div>';
            rowHtml += buildButtonRow(productData);
            rowHtml += '    </div>';
            rowHtml += '</div>';

            return rowHtml;
        }

        function buildImageColumn(imageUrl) {
            if (imageUrl != '') {
                imageUrl = imageUrl;
            } else {
                imageUrl = '/theme/img/image_not_available.png';
            }
            return '            <div class="col-md-4 col-lg-4 col-xl-5 image-div image-div-account">' +
                '                <img src="' + imageUrl + '" alt="Product Image" class="img-fluid">' +
                '            </div>';
        }

        function buildDataColumn(productData) {
            var column = $('#get_column').val();
            var stock_label = '';  
            var text_class = '';
            var products_to_hide = JSON.parse($('#products_to_hide').val());
            var show_price = true;
            var paymentTerms = $('#payment_terms').val();
            var auth_value = $('#auth_value').val();

            
            retail_price = 0;
            for (var i = 0; i < productData.options.length; i++) {
                if (products_to_hide.includes(productData.options[i].option_id)) {
                    if (auth_value == false) {
                        show_price = false;
                    } else {
                        if (paymentTerms == true) {
                            show_price = true;
                        } else {
                            show_price = false;
                        }
                    }
                }
                if (productData.options[i].stockAvailable > 0) {
                    stock_label = 'In Stock';
                    text_class = 'text-success';
                } else {
                    stock_label = 'Out of Stock';
                    text_class = 'text-danger';
                }
                retail_price = productData.options[i].default_price[column];
                if (retail_price == 0) {
                    retail_price = productData.options[i].default_price.sacramentoUSD;
                }
                if (retail_price == 0) {
                    retail_price = productData.options[i].default_price.retailUSD;
                }

                var dataHtml = '            <div class="col-md-8 col-lg-8 col-xl-7 data-div data-div-account">';
                dataHtml += '                <div class="row">';
                dataHtml += '                    <div class="col-md-10">';
                dataHtml += '                        <p class="product_name mb-1">';
                dataHtml += '                            <a class="product_name" data-title="'+productData.name+'" id="prd_name_' + productData.id + '" href="' + '/product-detail/' + productData.id + '/' + productData.options[i].option_id +'/'+ productData.code +'">' + productData.name + '</a>';
                dataHtml += '                        </p>';
                dataHtml += '                    </div>';
                dataHtml += '                    <div class="col-md-10">';
                dataHtml += '                        <p class="'+text_class+' mb-0">'+stock_label+'</p>';
                dataHtml += '                    </div>';
                if (show_price == true) {
                    dataHtml += '                    <div class="col-md-10">';
                    dataHtml += '                        <p class="product_price mb-1">$' + retail_price.toFixed(2) + '</p>';
                    dataHtml += '                    </div>';
                }
                dataHtml += '                    <div class="col-md-10">';
                dataHtml += '                        <p class="category_name mb-1">Category:';
                dataHtml += '                            <a class="category_name" href="' + '/products/' + productData.categories.id + '/' + productData.categories.slug +  '">' + productData.categories.name + '</a>';
                dataHtml += '                        </p>';
                dataHtml += '                    </div>';
                dataHtml += '                </div>';
                dataHtml += '            </div>';
            }
            
            return dataHtml;
        }

        function buildButtonRow(productData) {
            var products_to_hide = JSON.parse($('#products_to_hide').val());
            var add_to_cart = true;
            var paymentTerms = $('#payment_terms').val();
            var auth_value = $('#auth_value').val();
            for (var i = 0; i < productData.options.length; i++) {
                if (products_to_hide.includes(productData.options[i].option_id)) {
                    if (auth_value == false) {
                        add_to_cart = false;
                    } else {
                        if (paymentTerms == true) {
                            add_to_cart = true;
                        } else {
                            add_to_cart = false;
                        }
                    }
                }
                var buttonRowHtml = '        <div class="row justify-content-center mt-4">';
                if (productData.options[i].stockAvailable > 0) {
                    buttonRowHtml += '            <div class="col-md-10">';
                    buttonRowHtml += '                <button type="button" class="buy_frequent_again_btn border-0 w-100 p-2" onclick="similar_product_add_to_cart(\'' + productData.id + '\', \'' + productData.options[i].option_id + '\')">Add to Cart</button>';
                    buttonRowHtml += '            </div>';
                    buttonRowHtml += '            <div class="col-md-10 mt-4 border-div d-flex align-items-center align-self-center"></div>';
                    buttonRowHtml += '        </div>';
                } else {
                    if (add_to_cart == true) {
                        if (auth_user === null) {
                            buttonRowHtml += '            <div class="col-md-10">';
                            buttonRowHtml += '                <button type="button" id="notify_popup_modal" onclick="show_notify_popup_modal_similar_portion(\'' + productData.id + '\', \'' + productData.code + '\')" class="w-100 ml-0 bg-primary h-auto product-detail-button-cards notify_stock_btn_class text-uppercase notify_popup_modal_btn rounded><a class="text-white">Notify</a></button>';
                            buttonRowHtml += '            </div>';
                            buttonRowHtml += '            <div class="col-md-10 mt-4 border-div d-flex align-items-center align-self-center"></div>';
                            buttonRowHtml += '        </div>';
                        } else {
                            buttonRowHtml += '            <div class="col-md-10">';
                            buttonRowHtml += '                <button type="button" id="notify_popup_modal" data-product-id= '+productData.id+' onclick="notify_user_about_product_stock_similar_portion(\'' + productData.id + '\', \'' + productData.code + '\')" class="w-100 ml-0 bg-primary h-auto product-detail-button-cards notify_stock_btn_class text-uppercase notify_popup_modal_btn rounded d-flex align-items-center justify-content-center"><a class="text-white">Notify</a><div class="spinner-border text-white custom_stock_spinner stock_spinner_'+productData.id+' ml-1 d-none" role="status"><span class="sr-only"></span></div></button>';
                            buttonRowHtml += '            </div>';
                            buttonRowHtml += '            <div class="col-md-10 mt-4 border-div d-flex align-items-center align-self-center"></div>';
                            buttonRowHtml += '        </div>';
                        }
                    } else {
                        buttonRowHtml += '            <div class="col-md-10">';
                        buttonRowHtml += '                <button type="button" class="buy_frequent_again_btn_call_to_order border-0 w-100 p-2">Call To Order</button>';
                        buttonRowHtml += '            </div>';
                        buttonRowHtml += '        </div>';
                    }
                }
                
            }
                return buttonRowHtml;
        }

        function generatePaginationLinks(totalPages, currentPage) {
            var paginationHtml = '';

            for (var i = 1; i <= totalPages; i++) {
                var activeClass = (i === currentPage) ? 'active' : '';
                paginationHtml += '<li class="pagination-item"><a href="#" class="pagination-link ' + activeClass + '">' + i + '</a></li>';
            }

            return paginationHtml;
        }

    });
</script>



<script>
    // $(document).ready(function() {
        // open notify user modal 
        function show_notify_popup_modal () {
            $('.notify_popup_modal_detail').modal('show');
        } 
        function close_notify_user_modal () {
            $('.notify_popup_modal_detail').modal('hide');
        }
        
        function notify_user_about_product_stock () {
            var email = $('.notify_user_email_input').val();
            var sku = $('.sku_value').val();
            var product_id = $('.product_id_value').val();
            $('.stock_spinner_modal').removeClass('d-none');
            $('.stock_spinner').removeClass('d-none');
            if (email != '') {
                $('.email_required_alert_detail').html('');
            }
            if (email == '') {
                $('.email_required_alert_detail').html('Email is Required');
                $('.stock_spinner_modal').addClass('d-none');
                $('.stock_spinner').addClass('d-none');
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
                            $('.stock_spinner').addClass('d-none');
                            $('.notify_user_div_detail').removeClass('d-none');
                            close_notify_user_modal();
                            $('.notify_text_detail').html(response.message);
                        } else {
                            $('.stock_spinner_modal').addClass('d-none');
                            $('.stock_spinner').addClass('d-none');
                            $('.notify_user_div_detail').removeClass('d-none');
                            $('.notify_text_detail').html('Something went wrong!');
                        }
                    },
                    error: function(response) {
                        var error_message = response.responseJSON;
                        $('.stock_spinner_modal').addClass('d-none');
                        $('.stock_spinner').addClass('d-none');
                        $('.notify_user_div_detail').addClass('d-none');
                        var error_text  = error_message.errors.email[0];
                        $('.email_required_alert_detail').html(error_text)
                    }
                });
            }
        }
        
        function hide_notify_user_div() {
            $('.notify_text_detail').html('');
            $('.notify_user_div_detail').addClass('d-none');
        }

        function get_latest_inventory_number() {
            var url = window.location.href;
            url += '?latest_inventory_number=1'; // You can dynamically set the inventory number here
            window.location = url;
        }

    // });
</script>

<script>
    $('.circle-right-ai').click(function() {
        $('.ai_spinner').removeClass('d-none');
        var question  = $('.ai_text_field').val();
        var product_name_detail_page = $('.product_name_detail_page').attr('data-title');
        if (question == '') {
            $('.ai_spinner').addClass('d-none');
            // $('.ai_text_field').addClass('border-danger');
            $('.ai_error').html('Please enter a question');
            return false;
        } 
        else {
            $('.ai_text_field').prop('readonly', true);
            calling_ai_prompt(question , product_name_detail_page);
        }
    });

    function clear_prompt() {
        $('.ai_text_field').val('');
        $('.ai_content').text('');
        $('.ai_text_field').prop('readonly', false);
        $('.clear_prompt').addClass('d-none');
    }

    function add_custom_question(element) {
        $('.ai_spinner').removeClass('d-none');
        var question = $(element).find('.ai_question_strong').html();
        var product_name_detail_page = $('.product_name_detail_page').attr('data-title');
        if (question == '') {
            $('.ai_spinner').addClass('d-none');
            $('.ai_error').html('Please enter a question');
            return false;
        } 
        else {
            $('.ai_text_field').val(question);
            $('.ai_text_field').prop('readonly', true);
            calling_ai_prompt(question , product_name_detail_page);
        }
    }

    $('#ai_text_field').keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault(); // Prevent the form from submitting
            $('.ai_spinner').removeClass('d-none');
            var question  = $('.ai_text_field').val();
            var product_name_detail_page = $('.product_name_detail_page').attr('data-title');
            if (question == '') {
                $('.ai_spinner').addClass('d-none');
                $('.ai_error').html('Please enter a question');
                return false;
            } 
            else {
                $('.ai_text_field').prop('readonly', true);
                calling_ai_prompt(question , product_name_detail_page);
            }
        }
    });

    function calling_ai_prompt(question , product_name_detail_page) {
        $.ajax({
            url: "{{ url('ai-answer') }}",
            method: 'get',
            data: {
            "_token": "{{ csrf_token() }}",
                question : question,
                product_name : product_name_detail_page
            },
            success: function(response){

                if (response.status === 'success') {
                    $('.ai_spinner').addClass('d-none');
                    $('.ai_error').html('');
                    $('.ai_content').html(response.message);
                    $('.ai_text_field').prop('readonly', false);
                    $('.clear_prompt').removeClass('d-none');
                } else {
                    $('.ai_spinner').addClass('d-none');
                    $('.ai_error').html('');
                    $('.ai_content').html(response.message);
                    $('.ai_text_field').prop('readonly', false);
                    $('.clear_prompt').removeClass('d-none');
                }
            },
            error: function(response) {
                var error_message = response.responseJSON;
                $('.ai_spinner').addClass('d-none');
                $('.ai_error').html('');
                $('.ai_content').html(error_message.message);
                $('.ai_text_field').prop('readonly', false);
                $('.clear_prompt').removeClass('d-none');
            }
        });
    }
</script>


<script>
    // see similar products on the basis of category
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
                        $('#see_similar_pop_up_detail').modal('hide');
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: response.message,
                            timer: 3000,
                            position: 'top',
                            showConfirmButton: true,  // Show the confirm (OK) button
                            confirmButtonText: 'Okay',
                            timerProgressBar: true,
                            customClass: {
                                confirmButton: 'my-confirm-button',  // Class for the confirm button
                                actions: 'my-actions-class'  // Class for the actions container
                            }
                        });
                    } else {
                        $('#see_similar_pop_up_detail').modal('show');
                        $('.similar_products_row-body_detail').html(generateProductsHtml(response , response.products));
                    }
                } else {
                    $('#see_similar_pop_up_detail').modal('hide');
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: response.message,
                        timer: 3000,
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
            },

            error: function(response) {
                $('#see_similar_pop_up_detail').modal('hide');
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: 'No similar products found',
                    timer: 3000,
                    position: 'top',
                    showConfirmButton: true,  // Show the confirm (OK) button
                    confirmButtonText: 'Okay',
                    timerProgressBar: true,
                    customClass: {
                        confirmButton: 'my-confirm-button',  // Class for the confirm button
                        actions: 'my-actions-class'  // Class for the actions container
                    }
                });
            }
        });
    }

    function generateProductsHtml(response , products) {
        var price_column = response.price_column;
        var htmlContent = `<div class="owl-carousel owl-theme similar-products-carousel-ai-detail w-75">`;

        products.forEach(function(product , price_column) {
            var productHtml = `
                <div class="item">
                    <div class="d-flex align-self-stretch mt-2  pt-1 h-100">
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
                    <h5 class="card-title card_product_title tooltip-product similar_product_name_${product.products.id}" style="font-weight: 500; font-size: 16px;" data-title="${product.products.name}" id="product_name_${product.products.id}">
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
                if ((product.default_price[response.price_column]  != null) || (parseFloat(product.default_price[response.price_column]) > 0)) {
                    var formattedPrice = formatNumber(parseFloat(product.default_price[response.price_column]));
                } else if ((product.default_price.sacramentoUSD  != null) || (parseFloat(product.default_price.sacramentoUSD) > 0)) {
                    var formattedPrice = formatNumber(parseFloat(product.default_price.sacramentoUSD));
                } else {
                    var formattedPrice = formatNumber(parseFloat(product.default_price.retailUSD));
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
                            
                            <input type="number" id="swap_qty_number_${product.products.id}" onchange="update_qty_text_new('${product.products.id}', '${product.option_id}' ,this)" name="swap_qty_number" value="1"  class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_${product.products.id}"  style="font-weight: 500" min="1" max="${product.stockAvailable}">
                            <div class="input-group-prepend custom-border qty_plus_mobile">
                                <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="adding_quantity('${product.products.id}', '${product.option_id}')"><i class="fa fa-plus plus_qty_font qty_font"></i></button>
                            </div>
                        </div>
                    </div>
                    <button 
                        class="hover_effect prd_btn_resp  button-cards col w-100  mb-1 original_cart_btn   original_cart_btn_${product.products.id}" 
                        type="submit" 
                        style="max-height: 46px;" id="ajaxSubmit_${product.products.id}"
                        onclick="updateCartDetail('${product.products.id}', '${product.option_id}')">
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
            

            productHtml += `</div></div></div>`;
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
        var imageNotAvailableUrl = "{{ asset('/theme/img/image_not_available.png') }}";
        if (product.products.images != '') {
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
                            <img src="${imageNotAvailableUrl}" class="img_responsive_mbl_not_available col-md-10 image-body offset-1 mt-2"  style="min-height: 130px; max-height: 130px;" />
                        </span>
                    </div>
                </a>
            `;
        }
    }

    function formatNumber(value) {
        return parseFloat(value).toFixed(2);
    }
    
    function updateCartDetail(id, option_id) {
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
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
                position: 'top',
                showConfirmButton: true,  // Show the confirm (OK) button
                confirmButtonText: 'Okay',
                timerProgressBar: true,
                customClass: {
                    confirmButton: 'my-confirm-button',  // Class for the confirm button
                    actions: 'my-actions-class'  // Class for the actions container
                }
            });
            $('.swap_qty_number_'+id).val(1);
            return false;
        }
        jQuery.ajax({
            url: "{{ url('/add-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: id,
                option_id: option_id,
                // quantity: 1
                quantity: itemQuantity
            },
            success: function(response) {
                if (response.status == 'error') {
                    

                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;
                    var product_quantity = 0;

                    for (var key in cart_items) {
                        var item = cart_items[key];

                        var product_id = item.prd_id;
                        var price = parseFloat(item.price);
                        product_quantity = parseInt(item.quantity);
                        var quantity = parseInt(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = $('.similar_product_name_' + id).attr('data-title');
                    }

                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html(response.actual_stock);
                    jQuery('.swap_qty_number_'+id).val(response.actual_stock);
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: response.message,
                        timer: 3000,
                        position: 'top',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Okay',
                        timerProgressBar: true,
                        customClass: {
                            confirmButton: 'my-confirm-button',  // Class for the confirm button
                            actions: 'my-actions-class'  // Class for the actions container
                        }
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
                        var quantity = parseInt(item.quantity);

                        var subtotal = parseInt(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        
                    }
                    var product_name = $('.similar_product_name_' + id).attr('data-title');
                    // jQuery('.cart-total-number-' + id).html($('#swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html(response.actual_stock);

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
                        title:itemQuantity + 'X ' + '<span class="text-dark toast_title">'+ product_name+'</span>' + '<br/>'+ '<div class="added_tocart text-left">Added to your cart</div>',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            },
            error: function(response) {
            var error_message = response.responseJSON;
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: error_message.message,
                    timer: 3000,
                    position: 'top',
                    showConfirmButton: true,  // Show the confirm (OK) button
                    confirmButtonText: 'Okay',
                    timerProgressBar: true,
                    customClass: {
                        confirmButton: 'my-confirm-button',  // Class for the confirm button
                        actions: 'my-actions-class'  // Class for the actions container
                    }
                });
            }
        });

        return false;
    }

    $(document).on('shown.bs.modal', '#see_similar_pop_up_detail', function () {
        $('.similar-products-carousel-ai-detail').owlCarousel({
            loop: false,
            margin: 10,
            dots: false,
            nav: true,
            // autoplay: true,
            // autoplayTimeout: 3000,
            navText: [
                '<i class="fa fa-chevron-left"></i>', // Left arrow icon
                '<i class="fa fa-chevron-right"></i>' // Right arrow icon
            ],
            responsive: {
                0: {
                    items: 1,
                    nav: false
                },
                600: {
                    items: 2,
                    nav: false
                },
                1000: {
                    items: 3,
                    nav: true
                }
            }
        });
        $('.similar-products-carousel-ai-detail').trigger('refresh.owl.carousel');
    });
</script>