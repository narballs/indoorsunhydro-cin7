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
<?php //dd($location_inventories);exit;?>
<input type="hidden" value="{{App\Helpers\UserHelper::getUserPriceColumn()}}" id="get_column">

<div class="row bg-light desktop-view justify-content-center w-100">
    <div class="col-md-12 col-xl-10 col-lg-12 col-sm-12 col-xs-12 mt-3 mb-3">
        <div class="row justify-content-center ml-1">
            {{-- similar product partial --}}
            <div class="col-md-8 col-xl-4 col-xxl-4 col-lg-4 col-sm-12 col-xs-12 col-12 order-md-2 order-lg-1 order-xl-1 order-xs-2 order-2">
                <div class="card rounded buy_again_div">
                    @include('partials.product-detail.similar-products')
                </div>
            </div>
            {{-- product detail --}}
            <div class="col-md-12 col-sm-12 col-xl-8 col-xxl-8 col-lg-8 col-xs-12 col-12 order-md-1 order-lg-2 order-xl-2 order-xs-1 order-1">
                @if ($customer_demand_inventory_number === 1)
                <div class="alert alert-success alert-dismissible mb-0 unprocess_alert p-1 rounded-0">
                    <div class="d-flex justify-content-between">
                        <span class="text-dark">Stock has been updated</span>
                        <a href="#" class="close p-1" data-dismiss="alert" aria-label="close">&times;</a>
                    </div>
                </div>
                @endif
                @if (!empty($productOption->products) && !empty($productOption->products->categories) && $productOption->products->category_id != 0 && strtolower($productOption->products->categories->name) === 'grow medium')
                    <p class="text-dark bg-warning text-md-center border m-0 font-weight-bold">
                        This product is excluded from california free shipping promotion
                    </p>
                @elseif (!empty($productOption->products->categories->parent) && !empty($productOption->products->categories->parent->name) && strtolower($productOption->products->categories->parent->name) === 'grow medium')   
                    <p class="text-dark bg-warning text-md-center border m-0 font-weight-bold">
                        This product is excluded from california free shipping promotion
                    </p>
                @endif
                <div class="card py-3">
                    
                    <div class="row ms-0">
                        
                        <div class="col-xl-4 col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="images">
                                @if ($productOption->products->images)
                                <div class="text-center mt-5">
                                    <img id="main-image" src="{{$productOption->products->images}}" class="img-fluid" />
                                </div>
                                @else
                                <div class="text-center mt-5">
                                    <img id="main-image" src="/theme/img/image_not_available.png"
                                        class="img-fluid w-75 h-75" />
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-xs-12 product-detail-content">
                            <div class="product pt-2 product-detail-content1 w-100">
                                <div class="d-flex row w-100">

                                    <?php
                                        $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
                                        $retail_price = 0;
                                        foreach($productOption->price as $price) {
                                            $retail_price = $price->$user_price_column;
                                        }
                                    ?>
                                    <div class="product-detail-heading col-xl-12 col-lg-12 col-md-12 col-xs-12 mb-2"
                                        id="product_name">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <h3 class="product-detail-heading">{{$productOption->products->name}}</h3>
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
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row align-items-center">
                                            <div class="col-md-12">
                                                <span class="text-danger product-detail-price" id="product_price">
                                                    ${{number_format($retail_price, 2)}}
                                                </span>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mt-4 mb-3"> <span class="text-uppercase text-muted brand"></span>                                                
                                                    <div class="price d-flex flex-row align-items-center">
                                                        @if ($productOption->products->status != 'Inactive')
                                                            @if($stock_updated == true)
                                                                <span class="rounded-pill cursor product-detail-quantity d-flex justify-content-center align-items-center"
                                                                    data-toggle="popover-hover" data-bs-container="body" data-placement="top" data-bs-placement="top"
                                                                    data-bs-content="Top popover" style=" cursor: pointer;"><span class="stock_number">
                                                                        {{$total_stock}}</span></span>
                                                                <div>
                                                                    <small class="dis-price">&nbsp;</small>
                                                                    <span class="instock-label">IN STOCK</span>
                                                                </div>
                                                            @elseif ($productOption->stockAvailable > 0)
                                                                <span class="rounded-pill cursor product-detail-quantity d-flex justify-content-center align-items-center"
                                                                    data-toggle="popover-hover" data-bs-container="body" data-placement="top" data-bs-placement="top"
                                                                    data-bs-content="Top popover" style=" cursor: pointer;"><span class="stock_number">
                                                                        {{$productOption->stockAvailable}}</span></span>
                                                                <div>
                                                                    <small class="dis-price">&nbsp;</small>
                                                                    <span class="instock-label">IN STOCK</span>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <small class="dis-price">&nbsp;</small>
                                                                    <span class="text-danger">{{ App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT OF STOCK');
                                                                        }}</span>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div>
                                                                <small class="dis-price">&nbsp;</small><span class="text-danger">NOT AVAILABLE FOR SALE</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <button type="button" class="btn btn-light update_inventory_number" onclick="get_latest_inventory_number()"><i class="fa fa-refresh"></i><span class="mx-2">Update Stock</span></button>
                                            </div>
                                            <div class="col-md-3">
                                                @if (!empty($productOption->option1) || !empty($productOption->option2) || !empty($productOption->option3))
                                                    @php
                                                        $image_src = [];
                                                        $options_array = ['option1', 'option2', 'option3'];
                                                        $image_type_array = ['case' => 'case.png', 'pallet' => 'pallet.png', 'box' => 'box.png' , 'pack' => 'case.png' ];
                                                        foreach ($options_array as $option) {
                                                            foreach ($image_type_array as $key => $image_type) {
                                                                if (strpos(strtolower($productOption[$option]), $key) !== false) {
                                                                    $image_src[$option] = $image_type;
                                                                }
                                                            }
                                                        }

                                                    @endphp
                                                    <div class="row align-items-center">
                                                        <div class="col-md-6 text-right">
                                                            @if (isset($image_src['option1']))
                                                                <img src= "{{asset('theme/bootstrap5/images/' . $image_src['option1'] )}}" style="max-width: 40px;" />
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                        <p class="mb-0">{{ $productOption->option1 }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center">
                                                        <div class="col-md-6 text-right">
                                                            @if (isset($image_src['option2']))
                                                                <img src= "{{asset('theme/bootstrap5/images/' . $image_src['option2'] )}}" style="max-width: 40px;" />
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                        <p class="mb-0">{{ $productOption->option2 }}</p> 
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center">
                                                        <div class="col-md-6 text-right">
                                                            @if (isset($image_src['option3']))
                                                                <img src= "{{asset('theme/bootstrap5/images/' . $image_src['option3'] )}}" style="max-width: 40px;" />
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                        <p class="mb-0">{{ $productOption->option3 }}</p> 
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @if ($customer_demand_inventory_number === 1)
                                                @if ($inventory_update_time_flag == true)
                                                    @if($stock_updated)
                                                        <div class="col-md-12 mx-3">
                                                            @if (!empty($locations))
                                                                @foreach ($locations as $location)
                                                                    <div>
                                                                        <p class="mb-1"> 
                                                                            <i class="fa fa-map-marker mr-2"></i>{{$location['branch_name'] . ':'}}
                                                                            <span class="text-success">{{ $location['available'] >= 0 ? $location['available'] : 0  }}</span>
                                                                        </p>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="col-md-12 mx-3">
                                                        @if (!empty($locations))
                                                            @foreach ($locations as $location)
                                                                <div>
                                                                    <p class="mb-1"> 
                                                                        <i class="fa fa-map-marker mr-2"></i>{{$location['branch_name'] . ':'}}
                                                                        <span class="text-success">{{ $location['available'] >= 0 ? $location['available'] : 0  }}</span>
                                                                    </p>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                <div class="col-md-12 mx-3">
                                                    @if (!empty($locations))
                                                        @foreach ($locations as $location)
                                                            <div>
                                                                <p class="mb-1"> 
                                                                    <i class="fa fa-map-marker mr-2"></i>{{$location['branch_name'] . ':'}}
                                                                    <span class="text-success">{{ $location['available'] >= 0 ? $location['available'] : 0  }}</span>
                                                                </p>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <form id="cart">
                                    @csrf
                                    <div class="cart row mt-3  w-100 justify-content-between">
                                        <div class="col-md-3">
                                            <div class="quantity" style="width:144px">
                                                <input type="number" name="quantity" id="quantity" min="1"
                                                    max="{{$productOption->stockAvailable}}" step="1" value="1" class="desktopqtyprd">
                                                <input type="hidden" name="p_id" id="p_id"
                                                    value="{{$productOption->products->id}}">
                                                <input type="hidden" name="option_id" id="option_id"
                                                    value="{{$productOption->option_id}}">
                                                    <input type="hidden" name="product_slug"  id="product_slug" value="{{$productOption->products->code}}">
                                                <div class="quantity-nav">
                                                    <div class="quantity-div quantity-up"></div>
                                                    <div class="quantity-div quantity-down greyed"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8 mt-2">
                                            
                                            @if (!empty($notify_user_about_product_stock) && strtolower($notify_user_about_product_stock->option_value) === 'yes')
                                                @if ($total_stock > 0)
                                                    <button class="w-100 ml-0 button-cards product-detail-button-cards text-uppercase"
                                                        type="button" id="ajaxSubmit">
                                                        <a class="text-white">Add to cart </a>
                                                    </button>
                                                @elseif ($productOption->stockAvailable > 0)
                                                    <button class="w-100 ml-0 button-cards product-detail-button-cards text-uppercase"
                                                        type="button" id="ajaxSubmit">
                                                        <a class="text-white">Add to cart </a>
                                                    </button>
                                                @else
                                                    @if (auth()->user())
                                                        <input type="hidden" name="notify_user_email_input" class="notify_user_email_input" id="auth_user_email" value="{{auth()->user()->email}}">
                                                        <input type="hidden" name="sku" id="sku_value" class="sku_value" value="{{$productOption->products->code}}">
                                                        <input type="hidden" name="product_id" id="product_id_value" class="product_id_value" value="{{$productOption->products->id}}">
                                                        <div class="row justify-content-between align-items-center">
                                                            <div class="col-md-10">
                                                                <button class="w-100 ml-0 bg-primary button-cards product-detail-button-cards text-uppercase"
                                                                    type="button" id="" onclick="notify_user_about_product_stock()">
                                                                    <a class="text-white">Notify When in Stock </a>
                                                                </button>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="spinner-border text-primary stock_spinner d-none" role="status">
                                                                    <span class="sr-only"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <button class="w-100 ml-0 bg-primary button-cards product-detail-button-cards text-uppercase notify_popup_modal_btn"
                                                            type="button" id="notify_popup_modal" onclick="show_notify_popup_modal()">
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
                                                    <button 
                                                        class="w-100 ml-0 button-cards product-detail-button-cards text-uppercase"
                                                        type="button" id="ajaxSubmit"
                                                    >
                                                        <a class="text-white">Add to cart </a>
                                                    </button>
                                                @else
                                                    <button 
                                                        class="ml-0 w-100 button-cards product-detail-button-cards opacity-50 text-uppercase" 
                                                        type="submit"
                                                    >
                                                        <a class="text-white">Add to cart</a>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-uppercase text-muted brand"></span>
                                </form>

                                <div>
                                    <div class="price w-100">
                                        <div class="row mt-3 w-100">
                                            <div class="col-md-7">
                                                <span class="category-title-heading">Category :</span>
                                                @if($pname)
                                                <span class="category-title mt-4 ps-2">{{$pname}}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-5">
                                                <span class="category-title-heading">SKU :</span>
                                                <span class="category-title">{{$productOption->code}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 w-100">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 col-xl-3 col-lg-4">
                                                <span><strong>Weight :</strong> 
                                                    <span>{{!empty($productOption->optionWeight) ? $productOption->optionWeight . 'lbs' : ''}}</span>
                                                </span>
                                            </div>
                                            @if (!empty($productOption->products->width) && !empty($productOption->products->height) && !empty($productOption->products->length))
                                                <div class="col-md-9 col-xl-9 col-lg-8">
                                                    <span>
                                                        <strong>Dimensions : </strong> 
                                                        <span>
                                                            {{!empty($productOption->products->length) ? $productOption->products->length . ' ' .  "x" : ''}}
                                                            {{!empty($productOption->products->width) ? $productOption->products->width . ' ' . "x" : ''}}
                                                            {{!empty($productOption->products->height) ? $productOption->products->height : ''}}
                                                        </span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="product-detail-content-dec row w-100">
                                    <div class="col-md-12">
                                        <div class="category-description category-description mt-1  lh-lg"><span>Description</span></div>
                                        <div class="">
                                            <span class="about product-details-description mt-2">
                                                {{ strip_tags( $productOption->products->description ) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="popover-form">
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
                <span style="width: 800px !important">
                    @if ($customer_demand_inventory_number === 1)
                        @if ($inventory_update_time_flag == true)
                            @if (!$stock_updated)
                                Unable to show accurate stock levels.<br />
                            @endif
                            @if (!empty($locations))
                                @foreach ($locations as $location)
                                    {{ $location['branch_name'] }}: {{ $location['available'] >= 0 ? $location['available'] : 0  }}<br />
                                @endforeach
                            @else
                                Unable to show accurate stock levels.<br />
                            @endif
                        @else
                            @if (!empty($locations))
                                @foreach ($locations as $location)
                                    {{ $location['branch_name'] }}: {{ $location['available'] >= 0 ? $location['available'] : 0  }}<br />
                                @endforeach
                            @else
                                Unable to show accurate stock levels.<br />
                            @endif
                        @endif
                    @else
                        @if (!empty($locations))
                            @foreach ($locations as $location)
                                {{ $location['branch_name'] }}: {{ $location['available'] >= 0 ? $location['available'] : 0  }}<br />
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
{{-- mobile view start --}}
<div class="row bg-light mobile-view w-100">
    <div class="container">
        @if ($customer_demand_inventory_number === 1)
            <div class="w-100 ml-3">
                <div class="alert alert-success alert-dismissible mb-0 unprocess_alert p-1 rounded-0">
                    <div class="d-flex justify-content-between">
                        <span class="text-dark">Stock has been updated</span>
                        <a href="#" class="close p-1" data-dismiss="alert" aria-label="close">&times;</a>
                    </div>
                </div>
            </div>
        @endif
        <div class="w-100 ml-3">
            @if (!empty($productOption->products) && !empty($productOption->products->categories) && $productOption->products->category_id != 0 && strtolower($productOption->products->categories->name) === 'grow medium')
                <p class="text-dark bg-warning text-center border m-0" style="font-size: 12px; font-weight:500;">
                    This product is excluded from california free shipping promotion
                </p>
            @elseif (!empty($productOption->products->categories->parent) && !empty($productOption->products->categories->parent->name) && strtolower($productOption->products->categories->parent->name) === 'grow medium')   
                <p class="text-dark bg-warning text-center border m-0" style="font-size: 12px; font-weight:500;">
                    This product is excluded from california free shipping promotion
                </p>
            @endif
        </div>
        <div class="row bg-white justify-content-center">
            <div class="d-flex align-items-center justify-content-center mx-1 ml-4 p_detail_image_row">
                @if($productOption->image)
                <img id="" class="p_detail_img" src="{{$productOption->image}}" class=""/>
                @else
                <img id="" class="p_detail_img" src="/theme/img/image_not_available.png" class=""/>
                @endif
            </div>
            <div class="p_detail_content pr-0">
                <div class="product  product-detail-content1">
                    <div class="d-flex">
                        <div class="product-detail-heading w-75" id="product_name">
                            <h3 class="product-detail-heading">{{$productOption->products->name}}</h3>
                        </div>

                        <div class="w-25 text-right">
                            <span class="product-detail-price" id="product_price">
                                ${{number_format($retail_price,2)}}</span>
                        </div>

                        
                    </div>
                    {{-- <div class="d-flex w-100">
                        <button type="button" class="btn btn-info btn-sm p-1 mb-2" onclick="get_latest_inventory_number()">Get latest Inventory numbers</button>
                    </div> --}}
                    
                    <div class="row mb-3"> 
                        
                        <span class="text-uppercase text-muted brand"></span>

                        <div class="price d-flex flex-row align-items-center">
                            @if($stock_updated == true)
                                <span class="rounded-pill product-detail-quantity d-flex justify-content-center align-items-center">
                                    <span class="stock_number">{{$total_stock}}</span>
                                </span>
                                <div class="ml-2">
                                    <span class="instock-label">IN STOCK</span>
                                </div>
                            @elseif ($productOption->stockAvailable > 0)
                                <span class="rounded-pill product-detail-quantity d-flex justify-content-center align-items-center">
                                    <span class="stock_number">{{$productOption->stockAvailable}}</span>
                                </span>
                                <div class="ml-2">
                                    <span class="instock-label">IN STOCK</span>
                                </div>
                            @else
                                <div class="ml-2">
                                    <span class="text-danger instock-label">
                                        {{ App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT OF STOCK'); }}
                                    </span>
                                </div>
                            @endif
                            @if(!empty($contact_id))
                            <a style="width:20px !important;" href="javascript:void(0);" class="mx-3 subscribe">
                                <i class="fa-solid fav-{{ $productOption->option_id }} fa-heart {{ isset($user_buy_list_options[$productOption->option_id]) ? '' : 'text-muted' }} "
                                    id="{{ $productOption->option_id }}" data-toggle="popover"
                                    onclick="addToList('{{ $productOption->product_id }}', '{{ $productOption->option_id }}', '{{ isset($user_buy_list_options[$productOption->option_id]) }}')">
                                </i>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex w-100">
                        <button type="button" class="btn btn-light update_inventory_number btn-sm mb-3" onclick="get_latest_inventory_number()"><i class="fa fa-refresh"></i><span class="mx-2">Update Stock</span></button>
                    </div>
                    <div class="row">
                        @if ($customer_demand_inventory_number === 1)
                            @if ($inventory_update_time_flag == true)
                                @if($stock_updated)
                                    <div class="col-md-12">
                                        @if (!empty($locations))
                                            @foreach ($locations as $location)
                                                <div>
                                                    <p class="mb-1 instock-label"> 
                                                        <i class="fa fa-map-marker mr-2"></i>{{$location['branch_name'] . ':'}}
                                                        <span class="text-success">{{$location['available'] >= 0 ?  $location['available'] : 0}}</span>
                                                    </p>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="col-md-12">
                                    @if (!empty($locations))
                                        @foreach ($locations as $location)
                                            <div>
                                                <p class="mb-1 instock-label"> 
                                                    <i class="fa fa-map-marker mr-2"></i>{{$location['branch_name'] . ':'}}
                                                    <span class="text-success">{{$location['available'] >= 0 ?  $location['available'] : 0}}</span>
                                                </p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="col-md-12">
                                @if (!empty($locations))
                                    @foreach ($locations as $location)
                                        <div>
                                            <p class="mb-1 instock-label"> 
                                                <i class="fa fa-map-marker mr-2"></i>{{$location['branch_name'] . ':'}}
                                                <span class="text-success">{{$location['available'] >= 0 ?  $location['available'] : 0}}</span>
                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    </div>
                    @if (!empty($productOption->option1) || !empty($productOption->option2) || !empty($productOption->option3))
                        @php
                            $image_src = [];
                            $options_array = ['option1', 'option2', 'option3'];
                            $image_type_array = ['case' => 'case.png', 'pallet' => 'pallet.png', 'box' => 'box.png' , 'pack' => 'case.png' ];
                            foreach ($options_array as $option) {
                                foreach ($image_type_array as $key => $image_type) {
                                    if (strpos(strtolower($productOption[$option]), $key) !== false) {
                                        $image_src[$option] = $image_type;
                                    }
                                }
                            }

                        @endphp
                        <div class="row mt-1" style="font-size: 14px;">
                            <div class="col-4">
                                @if (isset($image_src['option1']))
                                    <img src="{{asset('theme/bootstrap5/images/' . $image_src['option1'] )}}" style="max-width: 40px;" />
                                @endif
                            </div>
                            <div class="col-8">
                                <p class="mb-0">{{ $productOption->option1 }}</p>
                            </div>
                        </div>
                        <div class="row mt-1" style="font-size: 14px;">
                            <div class="col-4">
                                @if (isset($image_src['option2']))
                                    <img src="{{asset('theme/bootstrap5/images/' . $image_src['option2'] )}}" style="max-width: 40px;" />
                                @endif
                            </div>
                            <div class="col-8">
                                <p class="mb-0">{{ $productOption->option2 }}</p>
                            </div>
                        </div>
                        <div class="row mt-1" style="font-size: 14px;">
                            <div class="col-4">
                                @if (isset($image_src['option3']))
                                    <img src="/theme/img/box_icon.png" style="max-width: 40px;" />
                                @endif
                            </div>
                            <div class="col-8">
                                <p class="mb-0">{{ $productOption->option3 }}</p>
                            </div>
                        </div>
                    @endif
                    <form id="cart">
                        @csrf
                        <div class="cart d-flex  justify-content-between align-items-center">
                            <div class="mt-3 p_detail_stock_row">
                                <div class="quantity p_detail_stock_qty qty_mbl_holder">
                                    <input type="number" name="quantity" class="mobile_qty" id="qt_mbl_number" min="1"
                                        max="{{$productOption->stockAvailable}}" step="1" value="1">
                                    <input type="hidden" name="p_id" id="p_id" value="{{$productOption->products->id}}">
                                    <input type="hidden" name="option_id" id="option_id"
                                        value="{{$productOption->option_id}}">
                                    <div class="quantity-nav">
                                        <div class="quantity-div quantity-up up_qty_mbl">
                                            <i class="fa fa-angle-up text-dark u_btn mt-1"></i>
                                        </div>
                                        <div class="quantity-div quantity-down down_qty_mbl greyed">
                                            <i class="fa fa-angle-down text-dark d_btn mt-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 p_detail_cart_row">
                                <div style="">
                                    <?php 
                                        // $enable_add_to_cart = App\Helpers\SettingHelper::enableAddToCart($productOption);
                                        $enable_add_to_cart = true;
                                    ?>
                                    @if (!empty($notify_user_about_product_stock) && strtolower($notify_user_about_product_stock->option_value) === 'yes')
                                        @if ($total_stock > 0)
                                                <button class="button-cards product-detail-button-cards text-uppercase  w-100" 
                                                type="submit" id="ajaxSubmit_mbl">
                                                Add to cart
                                            </button>
                                        @elseif ($productOption->stockAvailable > 0)
                                            <button class="button-cards product-detail-button-cards text-uppercase  w-100" 
                                                type="submit" id="ajaxSubmit_mbl">
                                                Add to cart
                                            </button>
                                        @else
                                            @if (auth()->user())
                                                <input type="hidden" name="notify_user_email_input" class="notify_user_email_input" id="auth_user_email" value="{{auth()->user()->email}}">
                                                <input type="hidden" name="sku" id="sku_value" class="sku_value" value="{{$productOption->products->code}}">
                                                <input type="hidden" name="product_id" id="product_id_value" class="product_id_value" value="{{$productOption->products->id}}">
                                                <div class="row justify-content-between align-items-center">
                                                    <div class="col-md-10">
                                                        <button class="bg-primary button-cards product-detail-button-cards ajaxSubmit_mbl"
                                                            type="button" id="" onclick="notify_user_about_product_stock()">
                                                            <a class="text-white">Notify When in Stock </a>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="spinner-border text-primary stock_spinner d-none" role="status">
                                                            <span class="sr-only"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <button class="bg-primary button-cards product-detail-button-cards notify_popup_modal_btn "
                                                    type="button" id="notify_popup_modal" onclick="show_notify_popup_modal()">
                                                    <a class="text-white">Notify When in Stock </a>
                                                </button>
                                            @endif
                                        @endif
                                    @else
                                        @if ($enable_add_to_cart)
                                            <button class="button-cards product-detail-button-cards text-uppercase ajaxSubmit_mbl w-100" type="button" id="ajaxSubmit_mbl">
                                                <a class="text-white">Add to cart</a>
                                            </button>
                                        @else
                                            <button class="button-cards product-detail-button-cards opacity-50 text-uppercase w-100" type="submit">
                                                <a class="text-white">Add to cart</a>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <span class="text-uppercase text-muted brand"></span>
                    </form>
                    <div class="mt-2 mb-2">
                        <div class="row">
                            <div class="col-12">
                                <span class="category-title-heading">Weight :</span>
                                <span class="category-title">{{!empty($productOption->optionWeight) ? $productOption->optionWeight . 'lbs' : ''}}</span>
                            </div>
                            @if (!empty($productOption->products->width) && !empty($productOption->products->height) && !empty($productOption->products->length))
                            <div class="col-12">
                                <span class="category-title-heading">
                                    Dimensions :
                                </span>
                                <span class="category-title">
                                    {{!empty($productOption->products->length) ? $productOption->products->length . ' ' .  "x" : ''}}
                                    {{!empty($productOption->products->width) ? $productOption->products->width . ' ' . "x" : ''}}
                                    {{!empty($productOption->products->height) ? $productOption->products->height : ''}}
                                </span>
                            </div> 
                            @endif
                        </div>
                        <div class="d-flex">
                            <div class="w-50">
                                <span class="category-title-heading">Category :</span>
                                @if($pname)
                                <span class="category-title">{{$pname}}</span>
                                @endif
                            </div>
                            <div class="w-50 text-center">
                                <span class="category-title-heading">SKU :</span>
                                <span class="category-title">{{$productOption->code}}
                                </span>
                            </div> 
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="row ml-2 mt-2">
                <h5 class="category-description">Description</h5>
                <p class="about product-details-description category-pra d-flex justify-content-center align-items-center">
                    {{ strip_tags( $productOption->products->description ) }}
                </p>
            </div>
        </div>
    </div>
</div>
<div class="mobile-view">
    @if (!empty($similar_products) && count($similar_products) > 0)
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
                                @if (!empty($product->categories) && ($product->categories->is_active == 1))
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
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
{{-- mobile view end --}}

{{-- ipad view start --}}
<div class="row bg-light ipad-view">
    <div class="container">
        <div class="row">
            <div class="w-100">
                <div class="images ps-2">
                    @if($productOption->image)
                    <div class="text-center">
                        <img id="main-image" src="{{$productOption->image}}" class="img-fluid" style="width: 50%;" />
                    </div>
                    @else
                    <div class="text-center">
                        <img id="main-image" src="/theme/img/image_not_available.png" class="img-fluid"
                            style="width: 50%;" />
                    </div>
                    @endif
                </div>
            </div>
            <div class="w-100 ps-5 mt-5">
                <div class="product  product-detail-content1">
                    <div class="d-flex row">
                        <div class="product-detail-heading col-xl-12 col-lg-12 col-md-12 col-xs-12" id="product_name">
                            <h3 class="product-detail-heading">{{$productOption->products->name}}</h3>
                        </div>

                        <div class="col-md-12 d-flex">
                            <span class="text-danger product-detail-price mt-4" id="product_price">
                                ${{number_format($retail_price,2)}}</span>
                        </div>
                    </div>
                    <div class=""> <span class="text-uppercase text-muted brand"></span>
                        <div class="price d-flex flex-row align-items-center mt-4">
                            @if ($productOption->stockAvailable > 0)
                                <span
                                    class="rounded-pill product-detail-quantity d-flex justify-content-center align-items-center"><span
                                        class="">{{$productOption->stockAvailable}}</span></span>
                                <div>
                                    <small class="dis-price">&nbsp;</small>
                                    <span class="instock-label">IN STOCK</span>
                                </div>
                            @else
                                <div>
                                    <small class="dis-price">&nbsp;</small>
                                    <span class="text-danger">
                                        {{ App\Helpers\SettingHelper::getSetting('out_of_stock_label', 'OUT OF STOCK'); }}
                                    </span>
                                </div>
                            @endif
                            @if(!empty($contact_id))
                                <a style="width:20px !important;" href="javascript:void(0);" class="mx-3 subscribe">
                                    <i class="fa-solid fav-{{ $productOption->option_id }} fa-heart {{ isset($user_buy_list_options[$productOption->option_id]) ? '' : 'text-muted' }} "
                                        id="{{ $productOption->option_id }}" data-toggle="popover"
                                        onclick="addToList('{{ $productOption->product_id }}', '{{ $productOption->option_id }}', '{{ isset($user_buy_list_options[$productOption->option_id]) }}')">
                                    </i>
                                </a>
                            @endif
                        </div>
                    </div>
                    @if (!empty($productOption->option1) || !empty($productOption->option2) || !empty($productOption->option3))
                        <div class="row" style="font-size: 14px;">
                            <div class="col-md-6">
                                
                            </div>
                            <div class="col-md-6">
                                <img src="/theme/img/box_icon.png" style="max-width: 40px;" />
                                <p>{{ $productOption->option1 }}</p>
                                <p>{{ $productOption->option2 }}</p>
                                <p>{{ $productOption->option3 }}</p>
                            </div>
                        </div>
                    @endif
                    <form id="cart">
                        @csrf
                        <div class="cart row  align-items-center">
                            <div class="w-50">
                                <div class="quantity" style="margin-top: 24px !important;">
                                    <input type="number" name="quantity" id="quantity" min="1"
                                        max="{{$productOption->stockAvailable}}" step="1" value="1">
                                    <input type="hidden" name="p_id" id="p_id" value="{{$productOption->products->id}}">
                                    <input type="hidden" name="option_id" id="option_id"
                                        value="{{$productOption->option_id}}">
                                    <div class="quantity-nav">
                                        <div class="quantity-div quantity-up"></div>
                                        <div class="quantity-div quantity-down"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-50">
                                <?php $enable_add_to_cart = App\Helpers\SettingHelper::enableAddToCart($productOption); ?>
                                @if ($enable_add_to_cart)
                                    <button 
                                        class="button-cards product-detail-button-cards text-uppercase" 
                                        style="font-size: 16px !important; width: 252px !important;" 
                                        type="button" 
                                        id="ajaxSubmit"
                                    >
                                    <a class="text-white">Add to cart</a></button>
                                @else
                                    <button 
                                        class="button-cards product-detail-button-cards opacity-50 text-uppercase"
                                        type="submit" 
                                        style="font-size: 16px !important; width: 130px  !important;">
                                        <a class="text-white">Add to cart</a>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <span class="text-uppercase text-muted brand"></span>
                    </form>
                    <div class="price">
                        <div class="row">
                            <div class="w-100">
                                <span class="category-title-heading">Category :</span>
                                @if($pname)
                                <span class="category-title mt-4">{{$pname}}</span>
                                @endif
                            </div>
                            <div class="w-100 mt-2">
                                <span class="category-title-heading">SKU :</span>
                                <span class="category-title">{{$productOption->code}}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="product-detail-content-dec">
                        <div class="category-description"><span>Description</span></div>
                        <span
                            class="about product-details-description category-pra d-flex justify-content-center aling-items-center">
                            {{ strip_tags( $productOption->products->description ) }}
                        </span>
                    </div>
                </div>
            </div>
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
            onclick="addToList('{{ $productOption->product_id }}', '{{ $productOption->option_id }}')">Add</button>
    </form>
</div>
{{-- ipad view end --}}

{{--  notify user pop up modal  --}}
<!-- Modal -->
<div class="modal fade notify_popup_modal_detail" id="notify_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                    <input type="hidden" name="sku" id="sku_value" class="sku_value" value="{{$productOption->products->code}}">
                    <input type="hidden" name="product_id" id="product_id_value" class="product_id_value" value="{{$productOption->products->id}}">
                    <div class="col-md-12">
                        <input type="text" name="notify_user_email" id="notify_user_email" class="form-control notify_user_email_input" placeholder="Enter your email">
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
        <button type="button" class="btn btn-secondary" onclick="notify_user_about_product_stock()">Submit</button>
        <!-- You can add additional buttons here if needed -->
    </div>
    </div>
</div>
</div>
{{--  notify user pop up modal end --}}

@include('partials.similar_products_slider')
<div class="modal fade notify_popup_modal_similar_portion" id="notify_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <input type="hidden" name="product_id" id="product_id_value" class="similar_productId_value" value="">
                        <div class="col-md-12">
                            <input type="text" name="notify_user_email" id="notify_user_email" class="form-control similar_notifyEmail_sidebar" value="" placeholder="Enter your email">
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
            <button type="button" class="btn btn-secondary" onclick="notify_user_about_product_stock_similar_portion ($('.similar_productId_value').val() , $('.similar_productSku_value').val())">Submit</button>
            <!-- You can add additional buttons here if needed -->
        </div>
        </div>
    </div>
</div>
{{--  notify user pop up modal end --}}
<script>
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
@include('partials.product-footer')
@include('partials.footer')
<style>
    .greyed {
        background: #eaeaea;
    }
    .update_inventory_number {
        color:#7bc533;
        font-family: 'poppins';
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 21px;
        letter-spacing: 0.55px;
        border:1px solid #dae0e5;
    }
    .update_inventory_number:hover {
        color:#7bc533;
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
        line-height: 21.037px; /* 150.263% */
    }
    .border-div {
        height: 1.237px;
        background: #E1E1E1;
        width: 90% !important;
    }
    .buy_again_div {
        border:1px solid rgb(234 236 240);
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
            color:#7bc533;
            font-family: 'poppins';
            font-size: 12px;
            font-style: normal;
            font-weight: 400;
            line-height: 21px;
            letter-spacing: 0.55px;
            padding: 3px;
        }
        .update_inventory_number:hover {
            color:#7bc533;
        }
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
                        imageUrl: $src,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
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
                    
                    var grand_total = 0;
                    var grand_total = parseFloat(cart_total);
                    var tax = cart_total * (tax_rate / 100);
                    var grand_total_include_tax = 0;
                    grand_total_include_tax = (tax + grand_total).toFixed(2);
                    if (grand_total <= initial_free_shipping_value) {
                        $('.promotional_banner_div_congrats').addClass('d-none');
                        $('.promotional_banner_div').removeClass('d-none');
                        $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                    } else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').removeClass('d-none');
                    }
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: jQuery('#quantity').val() + ' X ' + product_name + '<div class="text-dark fw-bold fs-5">'+ product_price +'</div>'+ '<br>' + 'added to your cart',
                        timer: 3000,
                        imageUrl: $src,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
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
                let stock = jQuery(".stock_number").html();
                let stock_number = parseInt(stock);
                if (newVal === stock_number) {
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
                let stock = jQuery(".stock_number").html();
                let stock_number = parseInt(stock);
                if (newVal !== stock_number) {
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
                var qty_stock_number = parseInt($(this).attr('max'));
                if (input_qty > qty_stock_number) {
                    $(this).val(qty_stock_number);
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: 'Quantity must be less than or equal to stock quantity',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
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
                        var quantity = parseFloat(item.quantity);
                        var subtotal = parseInt(price * quantity);
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
                        var quantity = parseFloat(item.quantity);
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
                    if (grand_total <= initial_free_shipping_value) {
                        $('.promotional_banner_div_congrats').addClass('d-none');
                        $('.promotional_banner_div').removeClass('d-none');
                        $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                    } else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').removeClass('d-none');
                    }
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        // title: jQuery('.mobile_qty').val() + ' X ' + product_name + '<div class="text-dark fw-bold fs-5">'+ product_price +'</div>'+ '<br>' + 'added to your cart',
                        timer: 2000,
                        text: 'Product added to cart',
                        // imageUrl: "{{asset('theme/img/add_to_cart_gif.gif')}}",
                        showConfirmButton: false,
                        timerProgressBar: true
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
                let stock = jQuery(".stock_number").html();
                let stock_number = parseInt(stock);
                if (newVal === stock_number) {
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
                let stock = jQuery(".stock_number").html();
                let stock_number = parseInt(stock);
                if (newVal !== stock_number) {
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
                var stock_number = $(this).attr('max');
                if (input_value >= stock_number) {
                    $(this).val(stock_number);
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: 'Quantity must be less than or equal to stock quantity',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                } else {
                    $(this).val(input_value);
                }
            });

        // });
    });
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
                        var quantity = parseFloat(item.quantity);

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

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        jQuery('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = jQuery('#prd_name_' + id).html();
                    }

                    var grand_total = 0;
                    var grand_total = parseFloat(cart_total);
                    var tax = cart_total * (tax_rate / 100);
                    var grand_total_include_tax = 0;
                    grand_total_include_tax = (tax + grand_total).toFixed(2);
                    if (grand_total <= initial_free_shipping_value) {
                        $('.promotional_banner_div_congrats').addClass('d-none');
                        $('.promotional_banner_div').removeClass('d-none');
                        $('.promotional_banner_span').html('$' + (initial_free_shipping_value - grand_total_include_tax).toFixed(2));
                    } else {
                        $('.promotional_banner_div').addClass('d-none');
                        $('.promotional_banner_div_congrats').removeClass('d-none');
                    }

                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 1 + ' X ' + product_name +
                            ' added to your cart',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);

                $('#cart_items_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });
    }
    

    
</script>

<script>
    $(document).ready(function() {
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
            retail_price = 0;
            for (var i = 0; i < productData.options.length; i++) {
                if (productData.options[i].stockAvailable > 0) {
                    stock_label = 'In Stock';
                    text_class = 'text-success';
                } else {
                    stock_label = 'Out of Stock';
                    text_class = 'text-danger';
                }
                retail_price = productData.options[i].default_price[column];

                var dataHtml = '            <div class="col-md-8 col-lg-8 col-xl-7 data-div data-div-account">';
                dataHtml += '                <div class="row">';
                dataHtml += '                    <div class="col-md-10">';
                dataHtml += '                        <p class="product_name mb-1">';
                dataHtml += '                            <a class="product_name" id="prd_name_' + productData.id + '" href="' + '/product-detail/' + productData.id + '/' + productData.options[i].option_id +'/'+ productData.code +'">' + productData.name + '</a>';
                dataHtml += '                        </p>';
                dataHtml += '                    </div>';
                dataHtml += '                    <div class="col-md-10">';
                dataHtml += '                        <p class="'+text_class+' mb-0">'+stock_label+'</p>';
                dataHtml += '                    </div>';
                dataHtml += '                    <div class="col-md-10">';
                dataHtml += '                        <p class="product_price mb-1">$' + retail_price.toFixed(2) + '</p>';
                dataHtml += '                    </div>';
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
            for (var i = 0; i < productData.options.length; i++) {
                var buttonRowHtml = '        <div class="row justify-content-center mt-4">';
                if (productData.options[i].stockAvailable > 0) {
                    buttonRowHtml += '            <div class="col-md-10">';
                    buttonRowHtml += '                <button type="button" class="buy_frequent_again_btn border-0 w-100 p-2" onclick="similar_product_add_to_cart(\'' + productData.id + '\', \'' + productData.options[i].option_id + '\')">Add to Cart</button>';
                    buttonRowHtml += '            </div>';
                    buttonRowHtml += '            <div class="col-md-10 mt-4 border-div d-flex align-items-center align-self-center"></div>';
                    buttonRowHtml += '        </div>';
                } else {
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
