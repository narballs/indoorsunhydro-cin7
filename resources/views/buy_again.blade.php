@include('partials.header')
@include('partials.top-bar')
@include('partials.search-bar')
{{-- <div class="mb-4 mt-2">
    <p style="line-height: 95px;"
        class="fw-bold fs-2 product-btn my-auto border-0 text-white text-center align-middle pbtn_mbl">
        PRODUCTS
    </p>
</div> --}}
<div class="w-100 mx-0 row justify-content-center align-items-center" style="background-color: #008BD3;height:70px;">
    <p class="fw-bold fs-2 my-auto border-0 text-white text-center align-middle">
       Buy Again
    </p>
</div>

<div class="container desktop-view">
    @if (session('error'))
        <div class="alert alert-danger mt-2">
            {{ session('error') }}
        </div>
    @endif
   
    <div class="row" id="product_rows">
        @if(count($products) == 0)
            <div class="col-md-12 mt-3">
                <div class="alert alert-danger">No Product Found</div>
            </div>
        @endif
        @foreach ($products as $key => $product)
            @foreach ($product->options as $option)
                @include('product_row')
            @endforeach
        @endforeach
    </div>
    <div class="row">
        <div class="container">
            <div class="col-md-6 m-auto">
                {{ $products->appends(Request::all())->links() }}
            </div>
        </div>
    </div>
</div>

{{-- moible view --}}
<div class="container mobile-view">
    
    <div class="row" id="product_rows">
        @if(count($products) == 0)
            <div class="col-md-12 mt-3">
                <div class="alert alert-danger">No Product Found</div>
            </div>
        @endif
        @foreach ($products as $key => $product)
            @foreach ($product->options as $option)
                @include('product_row')
            @endforeach
        @endforeach
        <div class="w-100 justify-content-center p-2 mt-3">
            {{ $products->appends(Request::all())->onEachSide(1)->links('pagination.front_custom_pagination') }}
        </div>
    </div>
    
    {{-- <div class="row mobile-view w-100"> --}}
        
    {{-- </div> --}}
</div>
{{-- mobile view end --}}

{{-- ipad view start --}}
<div class="container ipad-view">
    
    <div class="row" id="product_rows">
        @if(count($products) == 0)
            <div class="col-md-12 mt-3">
                <div class="alert alert-danger">No Product Found</div>
            </div>
        @endif
        @foreach ($products as $key => $product)
            @foreach ($product->options as $option)
                @include('product_row')
            @endforeach
        @endforeach
    </div>
    <div class="row ipad-view">
        <div class="container">
            <div class="col-sm-6 m-auto">
                {{ $products->appends(Request::all())->links() }}
            </div>
        </div>
    </div>
</div>
{{-- ipid view end --}}


<script>
    function showdetails(id, option_id, slug) {
        window.location.href = '/product-detail/' + id + '/' + option_id + '/' + slug;
    }
    function updateCart(id, option_id) {
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
        // updateBodyClickEventStatus(false);
        // $('#last_button_clicked').val(id);

        // $('.cart-total-' + id).addClass('added-to-cart');
        // $('.button_swap_quantity_' + id).addClass('btn-added-to-cart');

        // //$('.quantity_count_circle').css('visibility', 'visible');
        // $('.added-to-cart').css('display', 'inline-flex');
        // $('.btn-added-to-cart').css('display', 'none');

        // $('.cart-total-' + id).css('display', 'none');
        // $('.button_swap_quantity_' + id).css('display', 'block');
        // $('.original_cart_btn_'+id).addClass('d-none');
        // $('.button_swap_quantity_'+id).removeClass('d-none');

        // $('.swap_qty_number_'+id).val(1);

        // $('.quantity_count_circle').each(function() {
        //     var html = $(this);
        //     var spanContent = $(html).find('span');
        //     if (parseInt($(html).find('span').html()) > 0) {
        //         spanContent.parent().css('display', 'inline-flex');
        //     } else {
        //         spanContent.parent().css('display', 'none');
        //     }
        // });
        var itemnumberQuantity = $('.swap_qty_number_'+id).val();
        var newValue = itemnumberQuantity.replace(/^0+/, ''); // Remove leading zeros
        if (newValue === "") {
            newValue = 0; // Handle case where all characters were zeros
        }
        $('.swap_qty_number_'+id).val(newValue);
        var itemQuantity = $('.swap_qty_number_'+id).val();
        if (parseInt(itemQuantity) <= 0 || itemQuantity === '' || itemQuantity === null) {
            Swal.fire({
                toast: false,
                icon: 'error',
                title: 'Quantity must be greater than 0 and not Empty!',
                position: 'center',
                showConfirmButton: true,  // Show the confirm (OK) button
                confirmButtonText: 'Confirm',
                timerProgressBar: false,
                allowOutsideClick: false, // Disable clicking outside to close the modal
                allowEscapeKey: false, // Disable Esc key to close the modal
                customClass: {
                        confirmButton: 'my-confirm-button',  // Class for the confirm button
                        popup: 'swal2-popup-class',  // Class for the actions container
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
                p_id: jQuery('#p_' + id).val(),
                option_id: option_id,
                quantity: itemQuantity
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
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        console.log(item.name);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    

                    Swal.fire({
                        toast: false,
                        icon: 'error',
                        title: response.message,
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                                confirmButton: 'my-confirm-button',  // Class for the confirm button
                                popup: 'swal2-popup-class',  // Class for the actions container
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
                        var quantity = parseFloat(item.quantity);

                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        console.log(item.name);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    var productName = $("#product_name_" + id).attr('data-title');
                    Swal.fire({
                        toast: false,
                        icon: 'success',
                        title: itemQuantity + 'X ' + '<span class="text-dark toast_title">'+ productName+'</span>' + '<br/>'+ '<div class="added_tocart">Added to your cart</div>',
                        // customClass: {popup: 'short-toast-popup'},
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                                confirmButton: 'my-confirm-button',  // Class for the confirm button
                                popup: 'swal2-popup-class',  // Class for the actions container
                                actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#cart_items_quantity').html(total_cart_quantity);
                $('.cartQtyipad').html(total_cart_quantity);
                $('.cartQtymbl').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                $('.topbar_cart_total_ipad').html('$'+parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });

        return false;
    }
    function update_sliderCart (id, option_id) {
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
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
                if (response.status == 'error') {
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
                        toast: false,
                        icon: 'error',
                        title: response.message,
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                                confirmButton: 'my-confirm-button',  // Class for the confirm button
                                popup: 'swal2-popup-class',  // Class for the actions container
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
                        var quantity = parseFloat(item.quantity);

                        var subtotal = parseInt(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        console.log(item.name);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
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
                        toast: false,
                        icon: 'success',
                        title: jQuery('#quantity').val() + 'X ' + '<span class="text-dark toast_title">'+ product_name+'</span>' + '<br/>'+ '<div class="added_tocart">Added to your cart</div>',
                        // customClass: {popup: 'short-toast-popup'},
                        position: 'center',
                        showConfirmButton: true,  // Show the confirm (OK) button
                        confirmButtonText: 'Confirm',
                        timerProgressBar: false,
                        allowOutsideClick: false, // Disable clicking outside to close the modal
                        allowEscapeKey: false, // Disable Esc key to close the modal
                        customClass: {
                                confirmButton: 'my-confirm-button',  // Class for the confirm button
                                popup: 'swal2-popup-class',  // Class for the actions container
                                actions: 'my-actions-class'  // Class for the actions container
                        }
                    });
                }
                $('#top_cart_quantity').html(total_cart_quantity);
                $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                var total = document.getElementById('#top_cart_quantity');
            }
        });

        return false;
    }
</script>
<script>
    jQuery('#brand').select2({
        width: '100%',
        placeholder: "Select an Option",
        allowClear: true
    });
    
</script>
@include('partials.product-footer')

@include('partials.footer')

<script>
    $(document).ready(function() {
        $('.pagination').addClass('pagination-sm');
    });
</script>


    
    
    
