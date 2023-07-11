@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
@section('my-favorites-active', 'active')
<div class="col-md-12 p-0">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
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
                    <div class="card-header bg-white ps-5">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="my_account_default_address mb-0">
                                    Favorites
                                </p>
                            </div>
                            <div class="col-md-8">
                                <div class="row justify-content-end">
                                    <div class="col-md-12 mt-3 d-flex justify-content-end align-items-center">
                                        {{-- @foreach ($lists as $list) --}}
                                            @if (count($lists) > 0)
                                                <button class="btn btn-success btn-sm mr-3 selection_buttons" id="add_selected"
                                                    onclick="add_selected_to_cart()" type="button">
                                                    Add Selected to Cart
                                                </button>
                
                                                <button class="btn btn-success btn-sm selection_buttons mr-3" id="add_all_to_cart" type="button"
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
                                $i = 1;
                            @endphp
                            {{-- @foreach ($lists as $list) --}}
                                <div>
                                    <table class="table address-table-items-data m-0 ">
                                        <thead>
                                            <tr class="table-header-background">
                                                <td class="d-flex table-row-item">
                                                    <div class="custom-control custom-checkbox tabel-checkbox">
                                                        {{-- <input
                                                            class="custom-control-input custom-control-input-success checkbox-table"
                                                            type="checkbox" id="selectAll" value="">
                                                        <label for="selectAll" class="custom-control-label"></label> --}}

                                                        <span class="table-row-heading-order">
                                                            <i class="fas fa-arrow-up mt-1"
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
                                                        <div id="">
                                                            @foreach ($option->price as $price)
                                                                <input type="hidden" value="{{ $product->id }}"
                                                                    id="prd_{{ $product->id }}">
                                                                <input type="hidden"
                                                                    value="{{ $product->product->name }}"
                                                                    id="prd_name_{{ $product->id }}">
                                                                <tr style="border-bottom :1px solid lightgray;"
                                                                    id="p_{{ $product->id }}">
                                                                    <td>
                                                                        <div
                                                                            class="custom-checkbox-input tabel-checkbox">
                                                                            <input type="checkbox"
                                                                                class="single_fav_check" name=""
                                                                                product-id="{{ $product->id }}"
                                                                                option-id="{{ $product->option_id }}"
                                                                                id="check_{{ $product->id }}_{{ $product->option_id }}"
                                                                                class="single_fav_check mt-1">
                                                                            {{ $i++ }}
                                                                    </td>
                                                                    <td
                                                                        style="border:none; vertical-align: middle; width: 26rem">
                                                                        <a href="{{ url('product-detail/' . $product->id . '/' . $product->option_id . '/' . $product->product->slug) }}"
                                                                            class="favorite_product_name_slug">{{ $product->product->name }}
                                                                        </a>
                                                                    </td>
                                                                    <td style="border:none">
                                                                        <div class="my_favorite_product_img p-1"
                                                                            style="width:67px">
                                                                            @if ($product->product->images)
                                                                                <img src="{{ $option->image }}"
                                                                                    class="" height="50px"
                                                                                    width="58px">
                                                                            @else
                                                                                <img src="/theme/image_not_available.png"
                                                                                    class="" height="50px">
                                                                            @endif
                                                                        </div>

                                                                    </td>
                                                                    <td style="border:none; vertical-align: middle">
                                                                        <span class="favorite_product_price">
                                                                            ${{ $product->sub_total }}
                                                                        </span>
                                                                    </td>
                                                                    <td style="border:none; vertical-align: middle">
                                                                        <button type="submit"
                                                                            style="border:none; background:none;"
                                                                            onclick="add_favorite_to_cart('{{ $product->id }}', '{{ $product->option_id }}')">
                                                                            <img src="/theme/img/fav_cart_icon.png"
                                                                                alt="">
                                                                        </button>
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
                                {{ $lists->links('pagination.custom_pagination') }}
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
<script>

    function handlePerPage() {

        
        var per_page = jQuery('#per_page_favorite').val();
        var basic_url = `/my-account/my-favorites`;
        if (per_page != '') {
        basic_url = basic_url+`?per_page=${per_page}`;
        }
        window.location.href = basic_url
    }
    //main multi function 
    function add_multi_to_cart(all_fav) {
        $.ajax({
            url: "{{ url('/multi-favorites-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                all_fav: all_fav,
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

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#cart_items_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Product(s) added to cart successfully',
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
            }
        });
    }
    //add all favorites to cart
    function add_all_to_cart() {
        var all_fav = [];
        var all_check = $('.single_fav_check');
        all_check.each(function() {
            var id = $(this).attr('id');
            var id = id.split('_');
            var product_id = id[1];
            var option_id = id[2];
            all_fav.push({
                product_id: product_id,
                option_id: option_id
            });
        });
        add_multi_to_cart(all_fav);
    }
    //add selected favorites to cart
    function add_selected_to_cart() {
        var selected_check = $('.single_fav_check:checked');
        if (selected_check.length == 0) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Please select at least one product to add it into your cart.',
                timer: 1000,
                showConfirmButton: false,
                position: 'top',
                timerProgressBar: true
            });
        } else {
            var all_fav = [];
            selected_check.each(function() {
                if ($(this).is(':checked')) {
                    var id = $(this).attr('id');
                    var id = id.split('_');
                    var product_id = id[1];
                    var option_id = id[2];
                    all_fav.push({
                        product_id: product_id,
                        option_id: option_id
                    });
                }
            });
            add_multi_to_cart(all_fav);
            setTimeout(() => {
                selected_check.prop('checked', false);
            }, 1000);
        }
    }

    function remove_from_favorite(id) {
        var product_buy_list_id = id;
        var option_id = $(this).attr('data-option');
        var contact_id = $(this).attr('data-contact');
        var user_id = $(this).attr('data-user');
        var list_id = $(this).attr('data-list');
        var title = $(this).attr('data-title');
        $.ajax({
            url: "{{ url('/delete/favorite/product') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                product_buy_list_id,
                option_id,
                contact_id,
                user_id,
                list_id,
                title
            },
            success: function(response) {
                if (response.status == 'success') {
                    wishLists();
                    Swal.fire('Success!', 'Product removed from your favorites.');
                }
            }
        });
    }

    function add_favorite_to_cart(id, option_id) {
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
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = document.getElementById('prd_name_' + id).value;
                    }

                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: quantity + ' X ' + document.getElementById('prd_name_' + id).value +
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

@include('my-account.my-account-scripts')
@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')
