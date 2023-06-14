@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
<style>
    .nav .active {
        background: #F5F5F5;
        /* border-left: none !important; */
        /* color: green !important; */
        color: #008AD0 !important;
    }

    nav svg {
        max-height: 20px !important;
    }

    #spinner-global {
        display: none !important;
    }

    input[type=number]::-webkit-outer-spin-button {

        opacity: 20;

    }
</style>
<div class="mb-5">
    <p style="line-height: 95px;" class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle">
        MY ACCOUNT
    </p>
</div>
<div class="container-fluid" style="width:1621px  !important;">
    <div class="row bg-light">
        <div class="container-fluid" id="main-row">
            @include('my-account.my-account-top-bar')
            <div class="row flex-xl-nowrap p-0 m-0 mr-3">
                @include('my-account.my-account-side-bar')

                <div class="col-xl-10 col-sm-12 col-xs-12 py-3 bg-white ms-3" style="border-radius: 10px !important;" id="fav_content">
                    <?php $i = 1;?>
                    @foreach ($lists as $list)
                        @if(count($list->list_products) > 0)
                        <div class="row justify-content-end btn-row-selection" id="Collector">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 my-account-content-heading text-center">
                                        {{ $list->title }}
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-around">
                                        <button class="btn btn-info btn-sm" id="add_selected" onclick="add_selected_to_cart()" type="button">
                                            Add Selected to Cart
                                        </button>
                                    
                                        <button class="btn btn-info btn-sm" id="add_all_to_cart" type="button" onclick="add_all_to_cart()">
                                            Add All to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row" id="">
                            <h3>
                                There are no product(s) in your favorite list. 
                            </h3>
                        </div>
                        @endif
                        <div class="mt-4" id="">
                            @foreach ($list->list_products as $product)
                            @foreach ($product->product->options as $option)
                                <div id="">
                                    @foreach ($option->price as $price)
                                        <table class="table">
                                            <tbody>
                                                <input type="hidden" value="{{ $product->id }}" id="prd_{{ $product->id }}">
                                                <input type="hidden" value="{{ $product->product->name  }}" id="prd_name_{{ $product->id }}">
                                                <tr style="border-bottom :1px solid lightgray;" id="p_{{ $product->id }}">
                                                    <td style="border:none;vertical-align: middle;" width="30">
                                                        <input type="checkbox" class="single_fav_check" name="" product-id="{{$product->id}}" option-id="{{$product->option_id}}" id="check_{{$product->id}}_{{$product->option_id}}" class="single_fav_check mt-1">
                                                    </td>
                                                    <td style="border:none;vertical-align: middle;" width="30">
                                                        {{ $i++ }}
                                                    </td>
                                                    <td style="width:400px; border:none;vertical-align: middle;">
                                                        <a
                                                            href="{{ url('product-detail/' . $product->id . '/' . $product->option_id . '/' . $product->product->slug) }}">{{ $product->product->name }}
                                                        </a>
                                                    </td>
                                                    <td style="border:none;width:20%;">
                                                        @if ($product->product->images)
                                                            <img src="{{ $option->image }}" class="" width="50px" height="50px">
                                                        @else
                                                            <img src="/theme/image_not_available.png" class="" width="50px"
                                                                height="50px">
                                                        @endif
                                                    </td>
                                                    <td style="border:none; vertical-align: middle;width:10%;">
                                                        ${{ $product->sub_total }}
                                                    </td>

                                                    <td style="border:none; vertical-align: middle;width:10%;">
                                                        <button type="submit" style="border:none; background:none;" onclick="add_favorite_to_cart('{{$product->id}}', '{{$product->option_id}}')">
                                                            <i class="fa fa-cart-plus"></i>
                                                        </button>
                                                    </td>

                                                    <td style="border:none; vertical-align: middle;width:10%;">
                                                        <button type="button" style="background: none; border:none;"
                                                            onclick="remove_from_favorite('{{ $product->id }}')"
                                                            data-option="{{ $product->option_id }}" data-contact="{{ $list->contact_id }}"
                                                            data-user="{{ $list->user_id }}" data-list="{{ $product->list_id }}"
                                                            data-title="{{ $list->title }}">
                                                            <i class="fa fa-times-circle mt-1" type="button"
                                                                style="color: red;font-size: 18px;"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endforeach
                                </div>
                            @endforeach
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //main multi function 
    function add_multi_to_cart(all_fav) {
        $.ajax({
            url: "{{ url('/multi-favorites-to-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                all_fav : all_fav,
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
                        $('#subtotal_' + product_id).html('$'+subtotal);
                    }
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#cart_items_quantity').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
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
        if(selected_check.length == 0) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Please select at least one product to add it into your cart.',
                timer: 1000,
                showConfirmButton: false,
                position: 'top',
                timerProgressBar: true
            });
        }
        else {
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
                quantity: 1 , 
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
                        $('#subtotal_' + product_id).html('$'+subtotal);
                        var product_name = document.getElementById('prd_name_'+ id).value;
                    }
                    
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: quantity + ' X ' + document.getElementById('prd_name_'+ id).value + ' added to your cart',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top',
                        timerProgressBar: true
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                
                $('#cart_items_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$'+parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
        }});
    }
</script>

@include('my-account.my-account-scripts')
@include('partials.product-footer')
<!-- End of .container -->
@include('partials.footer')
