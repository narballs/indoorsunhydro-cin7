@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<style>
    .call-to-order-button-product-slider {
        background-color: #008BD3 !important;
        color: #ffffff !important;
        border: 1px solid #008BD3 !important;
        font-size: 14px;
        line-height: 1.5;
        border-radius: .25rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        font-family: 'Poppins';
        font-style: normal;
    }
    .call-to-order-button-product-slider:hover {
        color: #008BD3 !important;
        background-color: #ffffff !important;
        border: 1px solid #008BD3 !important;
        font-size: 14px;
    }
</style>
@section('my-favorites-active', 'active')
<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle my-account-main-heading">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="row">
                <div class="col-md-6 py-3">
                    @include('my-account.my-account-side-bar')
                </div>
            </div>
            <div class="col-md-12 p-0">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="my_account_default_address mb-0">
                                    Favorites
                                </p>
                            </div>
                            <div class="col-md-8">
                                <div class="row justify-content-end">
                                    <div class="col-md-12 mt-3 d-flex justify-content-end align-items-center selection_button_div">
                                        {{-- @foreach ($lists as $list) --}}
                                            @if (count($lists) > 0)
                                                <button class="btn btn-success btn-sm selection_buttons" id="add_selected"
                                                    onclick="add_selected_to_cart()" type="button">
                                                    Add Selected to Cart
                                                </button>
                
                                                <button class="btn btn-success btn-sm selection_buttons " id="add_all_to_cart" type="button"
                                                    onclick="add_all_to_cart()">
                                                    Add All to Cart
                                                </button>
                                                <div>
                                                    <select id="per_page_favorite" name="per_page" class="form-select py-1" onchange="handlePerPage()">
                                                        <option value="">Per Page</option>
                                                        <option value="10" {{ $per_page }} {{ isset($per_page) && $per_page==10 ? 'selected="selected"' : ''
                                                           }}>10</option>
                                                        <option value="20" {{ $per_page }} {{ isset($per_page) && $per_page==20 ? 'selected="selected"' : ''
                                                           }}>20</option>
                                                        <option value="50" {{ $per_page }} {{ isset($per_page) && $per_page==50 ? 'selected="selected"' : ''
                                                           }}>50</option>
                                                    </select>
                                                </div>
                                            @endif
                                        {{-- @endforeach --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="fav_content">
                           @php
                                $i = ($lists->currentPage() - 1) * $lists->perPage() + 1;
                            @endphp
                            {{-- @foreach ($lists as $list) --}}
                                <div class="table-responsive">
                                    <table class="table address-table-items-data m-0 ">
                                        <thead>
                                            <tr class="table-header-background">
                                                <td class="d-flex table-row-item border-0">
                                                    <div class="custom-control custom-checkbox tabel-checkbox d-flex align-items-center pl-0">
                                                        <span class="table-row-heading-order">
                                                            <i class="fas fa-arrow-up"
                                                                style="font-size:14.5px ;"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="my_account_addresses">Product</td>
                                                <td class="my_account_addresses">Images</td>
                                                <td class="my_account_addresses">Price</td>
                                                <td class="my_account_addresses">Add To Cart</td>
                                                <td class="my_account_addresses">Actions</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($lists) > 0)
                                                @foreach ($lists as $product)
                                                    @foreach ($product->product->options as $option)
                                                        @php
                                                            $add_to_cart = true;
                                                            $show_price = true;
                                                            if (!empty($products_to_hide)) {
                                                                if (in_array($option->option_id, $products_to_hide)) {
                                                                    $add_to_cart = false;
                                                                    $show_price = false;
                                                                }
                                                            }
                                                        @endphp
                                                        <div id="">
                                                            @foreach ($option->price as $price)
                                                                <input type="hidden" value="{{ $product->product->id }}"
                                                                    id="prd_{{ $product->product->id }}">
                                                                <input type="hidden"
                                                                    value="{{ $product->product->name }}"
                                                                    id="prd_name_{{ $product->product->id }}">
                                                                <tr style="border-bottom :1px solid lightgray;"
                                                                    id="p_{{ $product->product->id }}">
                                                                    <td>
                                                                        <div
                                                                            class="custom-checkbox-input tabel-checkbox d-flex pl-0">
                                                                            <input type="checkbox"
                                                                                class="single_fav_check" name=""
                                                                                product-id="{{ $product->product->id }}"
                                                                                option-id="{{ $product->option_id }}"
                                                                                id="check_{{ $product->product->id }}_{{ $product->option_id }}"
                                                                                class="single_fav_check mt-1">
                                                                            <span class="ml-2">{{ $i++ }}</span>
                                                                    </td>
                                                                    <td
                                                                        style="border:none; vertical-align: middle; width: 26rem">
                                                                        <a href="{{ url('product-detail/' . $product->product->id . '/' . $product->option_id . '/' . $product->product->slug) }}"
                                                                            class="favorite_product_name_slug">{{ $product->product->name }}
                                                                        </a>
                                                                    </td>
                                                                    <td class="border-0 align-middle">
                                                                        <div class="my_favorite_product_img p-1"
                                                                            style="width:67px">
                                                                            @if ($product->product->images)
                                                                                <img src="{{ $product->product->images }}"
                                                                                    class="" height="50px"
                                                                                    width="58px">
                                                                            @else
                                                                                <img src=" {{ asset('theme/img/image_not_available.png') }}"
                                                                                    class="" height="50px">
                                                                            @endif
                                                                        </div>

                                                                    </td>
                                                                    <td style="border:none; vertical-align: middle">
                                                                        @if ($show_price == true)
                                                                            <span class="favorite_product_price">
                                                                                ${{ $product->sub_total }}
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td style="border:none; vertical-align: middle">
                                                                        @if ($show_price == true)
                                                                            <button type="submit"
                                                                                style="border:none; background:none;"
                                                                                onclick="add_favorite_to_cart('{{ $product->product->id }}', '{{ $product->option_id }}')">
                                                                                <img src="/theme/img/fav_cart_icon.png"
                                                                                    alt="">
                                                                            </button>
                                                                        @else
                                                                            <button class="p-2 call-to-order-button-product-slider mb-1">
                                                                                Call To Order
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                    <td style="border:none; vertical-align: middle">
                                                                        <button type="button"
                                                                            class="btn favorite_remove_btn"
                                                                            onclick="remove_from_favorite('{{ $product->id }}')"
                                                                            data-option="{{ $product->option_id }}"
                                                                            data-contact="{{ $product->buylist->contact_id }}"
                                                                            data-user="{{ $product->buylist->user_id }}"
                                                                            data-list="{{ $product->list_id }}"
                                                                            data-title="{{ $product->buylist->title }}">
                                                                            Remove
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5">
                                                        <h3 class="text-center">
                                                            There are no product(s) in your favorite list.
                                                        </h3>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            {{-- @endforeach --}}
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if (!empty($lists))
                                    {{ $lists->links('pagination.custom_pagination') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .selection_buttons ,  .selection_buttons:hover{
        background-color:#7bc533;
        border-color: #7bc533 ;   
    }
    #per_page_favorite:focus {
        box-shadow: none;
    }
</style>

@include('my-account.my-account-scripts')

@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')

<script>
    // Restore selected checkboxes on page load
    function restoreSelections() {
        const selectedData = JSON.parse(localStorage.getItem('selectedFavorites')) || {};

        $('.single_fav_check').each(function () {
            const productId = $(this).attr('product-id');
            const optionId = $(this).attr('option-id');
            const uniqueKey = `${productId}_${optionId}`;

            if (selectedData[uniqueKey]) {
                $(this).prop('checked', true);
            }
        });
    }

    // Save checkbox changes
    $(document).on('change', '.single_fav_check', function () {
        const productId = $(this).attr('product-id');
        const optionId = $(this).attr('option-id');
        const uniqueKey = `${productId}_${optionId}`;

        let selectedData = JSON.parse(localStorage.getItem('selectedFavorites')) || {};

        if ($(this).is(':checked')) {
            selectedData[uniqueKey] = {
                product_id: productId,
                option_id: optionId
            };
        } else {
            delete selectedData[uniqueKey];
        }

        localStorage.setItem('selectedFavorites', JSON.stringify(selectedData));
    });

    // Run restore on every full page load
    $(document).ready(function () {
        restoreSelections();
    });
</script>



