@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')

<div class="w-100 mb-2">
    <div class="alert alert-success alert-dismissible d-none mb-0 text-center notify_user_div_detail">
        <a href="#" onclick="hide_notify_user_div()" class="close" aria-label="close">&times;</a>
        <span class="notify_text_detail"></span>
    </div>
</div>

<div class="w-100 mx-0 row justify-content-center align-items-center" style="background-color:#008BD3;height:70px;">
    <p class="fw-bold fs-2 my-auto text-white text-center">
        PRODUCT DETAIL
    </p>
</div>

<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @yield('content')
</div>

<input type="hidden" value="{{App\Helpers\UserHelper::getUserPriceColumn()}}" id="get_column">
<input type="hidden" name="products_to_hide" id="products_to_hide"
       value="{{ htmlspecialchars(json_encode($products_to_hide)) }}">

@php
    $add_to_cart = true;
    $show_price = true;
    $auth = false;
    $paymentTerms = false;

    $contact = null;
    $get_main_contact = null;

    if (auth()->user()) {
        $auth = true;
        $contact = App\Models\Contact::where('user_id', auth()->user()->id)->first();

        if (!empty($contact)) {
            $contact_id_new = $contact->is_parent ? $contact->contact_id : $contact->parent_id;
            $get_main_contact = App\Models\Contact::where(function($q) use ($contact_id_new) {
                $q->where('contact_id', $contact_id_new)->orWhere('id', $contact_id_new);
            })->first();

            if (!empty($get_main_contact) && strtolower($get_main_contact->paymentTerms) === 'pay in advanced') {
                $paymentTerms = true;
            }
        }
    }

    if (!empty($products_to_hide) && !empty($productOption) && in_array($productOption->option_id, $products_to_hide)) {
        if (!$auth || empty($contact) || $paymentTerms) {
            $add_to_cart = false;
            $show_price = false;
        }
    }
@endphp

<input type="hidden" name="paymentTerms" id="paymentTerms" value="{{ $paymentTerms ? 1 : 0 }}">
<input type="hidden" name="auth_value" id="auth_value" value="{{ $auth ? 1 : 0 }}">

<div class="row justify-content-center">
    <div class="col-md-12 col-xl-10 mt-3 mb-3">
        <div class="row justify-content-between ml-1">

            {{-- Similar products sidebar --}}
            <div class="col-md-8 col-xl-4 order-md-2 order-lg-1">
                <div class="card rounded buy_again_div px-3">
                    @include('partials.product-detail.similar-products')
                </div>
            </div>

            {{-- Product detail --}}
            <div class="col-md-12 col-xl-8 order-md-1 order-lg-2">

                {{-- Stock updated alert --}}
                @if ($customer_demand_inventory_number === 1)
                    <div class="alert alert-success alert-dismissible mb-0 unprocess_alert p-1 rounded-0">
                        <div class="d-flex justify-content-between">
                            <span class="text-dark">Stock has been updated</span>
                            <a href="#" class="close p-1" data-dismiss="alert" aria-label="close">&times;</a>
                        </div>
                    </div>
                @endif

                {{-- Free shipping exclusion --}}
                @if (!empty($productOption->products->categories))
                    @php
                        $cat = strtolower($productOption->products->categories->name ?? '');
                        $parentCat = strtolower($productOption->products->categories->parent->name ?? '');
                    @endphp
                    @if ($cat === 'grow medium' || $parentCat === 'grow medium')
                        <p class="text-dark bg-warning text-md-center border m-0 font-weight-bold">
                            This product is excluded from california free shipping promotion
                        </p>
                    @endif
                @endif

                <div class="card py-3 no-border">
                    <div class="row">

                        {{-- Images --}}
                        <div class="col-xl-4 col-md-6">
                            <div class="product-detail-new-image-div text-center d-flex justify-content-center align-items-center">
                                <div class="row w-100 justify-content-center">
                                    <div class="col-md-10 text-center">
                                        @if ($productOption->products->images)
                                            <img id="main-image" alt="{{ $productOption->products->name }}"
                                                 src="{{ $productOption->products->images }}"
                                                 class="img-fluid rounded shadow" style="object-fit:contain;" />
                                        @else
                                            <img id="main-image" alt="Not Available"
                                                 src="/theme/img/image_not_available.png"
                                                 class="img-fluid rounded shadow" style="object-fit:contain;" />
                                        @endif
                                    </div>
                                    <div class="col-md-11 mt-3">
                                        @if (!empty($enable_image_scrapping) && strtolower($enable_image_scrapping->option_value) === 'yes')
                                            @php
                                                $ai_images = isset($productOption->products->ai_image_generation)
                                                    ? $productOption->products->ai_image_generation->where('status','!=',0)
                                                    : [];
                                            @endphp
                                            @if ($ai_images->isNotEmpty())
                                                <div class="thumbnails d-flex justify-content-center flex-wrap">
                                                    @foreach($ai_images->take(5) as $ai_image)
                                                        <img src="{{ $ai_image->image_url }}" alt="AI Image" class="img-thumb"
                                                             style="width:50px;height:60px;margin:5px;cursor:pointer;border:2px solid transparent;border-radius:5px;"
                                                             onclick="document.getElementById('main-image').src=this.src;this.style.border='2px solid black';" />
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info / pricing --}}
                        <div class="col-xl-8 col-md-8 product-detail-content">
                            @php
                                $user_price_column = App\Helpers\UserHelper::getUserPriceColumn();
                                $retail_price = 0; $ai_price = 0;
                                foreach($productOption->price as $price) {
                                    $retail_price = $price->$user_price_column ?: $price->sacramentoUSD ?: $price->retailUSD;
                                    $ai_price = $price->enable_ai_price == 1 ? $price->aiPriceUSD : 0;
                                }
                                $allowAIPrice = App\Helpers\UserHelper::allowAiPricing($productOption);
                                $discount = ($retail_price > 0 && $ai_price > 0) ? (($retail_price - $ai_price) / $retail_price * 100) : 0;
                                $finalPrice = ($allowAIPrice && $ai_price > 0) ? $ai_price : $retail_price;
                            @endphp

                            {{-- Product heading --}}
                            <div class="product-detail-heading mb-2">
                                <p class="product-detail-heading-text-new">{{ $productOption->products->name }}</p>
                                <span class="sku-div">SKU: {{ $productOption->code }}</span>
                            </div>

                            {{-- Pricing --}}
                            @if ($show_price)
                                @if ($ai_price > 0 && $allowAIPrice)
                                    <div class="deal-container my-2">
                                        <p class="deal-text">Limited time deal</p>
                                        <div class="d-flex align-items-center">
                                            <span class="discount-badge">-{{ number_format($discount,2) }}%</span>
                                            <span class="text-danger product-detail-price mx-2">${{ number_format($ai_price,2) }}</span>
                                        </div>
                                        <span class="original-price">Original: ${{ number_format($retail_price,2) }}</span>
                                    </div>
                                @else
                                    <span class="text-danger product-detail-price">${{ number_format($retail_price,2) }}</span>
                                @endif
                            @endif

                            {{-- Stock & cart --}}
                            <div class="mt-3">
                                @if ($add_to_cart)
                                    @if ((empty($get_wholesale_terms) || strtolower($get_wholesale_terms) != 'pay in advanced') && auth()->user())
                                        <form id="cart" class="row">
                                            @csrf
                                            <input type="hidden" name="p_id" value="{{ $productOption->products->id }}">
                                            <input type="hidden" name="option_id" value="{{ $productOption->option_id }}">
                                            <div class="col-4">
                                                <input type="number" name="quantity" min="1" step="1" value="1"
                                                       max="{{ $productOption->stockAvailable }}"
                                                       class="form-control text-center">
                                            </div>
                                            <div class="col-8">
                                                <button type="button" class="product-detail-add-to-cart-new" id="ajaxSubmit">Add to cart</button>
                                            </div>
                                        </form>
                                    @else
                                        <button class="product-detail-call-to-order-new w-100">Call to Order</button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mt-3">
                        <h5>Description</h5>
                        <div>{!! $productOption->products->description !!}</div>
                    </div>

                    {{-- AI Assistant --}}
                    @if (!empty($ai_setting) && strtolower($ai_setting->option_value) == 'yes')
                        <div class="mt-3 card">
                            <div class="card-header">Need help? Ask about this product</div>
                            <div class="card-body">
                                <input type="text" id="ai_text_field" class="form-control" placeholder="Ask any question">
                                <p class="ai_content mt-2"></p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stock popover --}}
<div id="popover-form" class="inventory_pop_over_form">
    <form class="p-0 w-100">
        <div class="form-group" style="width:800px">
            <div style="font-size:14px;color:white;max-width:800px;">
                <span class="inventory_info">
                    @if (!empty($locations))
                        @foreach ($locations as $location)
                            {{ $location['branch_name'] }}: {{ $location['available'] >= 0 ? $location['available'] : 0 }}<br />
                        @endforeach
                    @else
                        Unable to show accurate stock levels.<br />
                    @endif
                </span>
            </div>
        </div>
    </form>
</div>

{{-- Notify user modal --}}
<div class="modal fade" id="notify_user_modal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5>Notify me when in stock</h5></div>
        <div class="modal-body">
            <input type="email" class="form-control" placeholder="Enter your email">
        </div>
    </div></div>
</div>

{{-- Bulk qty modal --}}
<div class="modal fade" id="bulk_quantity_modal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header"><h5>Bulk Quote Request</h5></div>
        <div class="modal-body">
            <textarea readonly class="form-control">{{ $productOption->products->name }} | SKU: {{ $productOption->products->code }}</textarea>
            <input type="number" class="form-control mt-2" placeholder="Quantity">
        </div>
    </div></div>
</div>

{{-- See similar modal --}}
<div class="modal fade" id="see_similar_pop_up_detail" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header"><h5>Similar Products</h5></div>
        <div class="modal-body similar_products_row-body_detail"></div>
    </div></div>
</div>



@include('partials.product-footer')
@include('partials.footer')
@include('partials.product-detail-scripts')