<input type="hidden" id="zendesk_first_name"
    value="{{auth()->user() && !empty(auth()->user()->onlycontact->firstName) ? auth()->user()->onlycontact->firstName : ''}}">
<input type="hidden" id="zendesk_last_name"
    value="{{auth()->user() && !empty(auth()->user()->onlycontact->lastName) ? auth()->user()->onlycontact->lastName : ''}}">
<input type="hidden" id="zendesk_email" value="{{auth()->user() ? auth()->user()->email : ''}}">
<div class="modal fade" id="see_similar_pop_up" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="see_similar_pop_up" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="see_similar_pop_up">Similar Products</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body similar_products_row-body  d-flex justify-content-center align-items-center p-2">
        </div>
        <div class="modal-footer p-1">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="{{ asset('//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/theme/bootstrap5/js/bootstrap.js') }}"></script>
<script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}"></script>

<script src="https://kit.fontawesome.com/ec19ec29f3.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script id="ze-snippet" src="{{asset('zendesk.js?key=c226feaf-aefa-49d4-ae97-5b83a096f475')}}"></script>
<script src="{{ asset('theme/jquery/bootstrap-tagsinput.min.js') }}"></script>
<script>
    $(document).ready(function() {
		var order_id = parseInt($('.getorderID').html());
		var currency = 'USD';
		var orderTotal = $('.getorderTotal').val();
        var isStripe = $('.isStripe').val();
		var send_to = 'AW-11475808917/uLuQCJO2t40ZEJXli-Aq';
		console.log(order_id , currency , orderTotal , send_to);
        if (parseInt(isStripe) == 1) {
            if (window.location.pathname.match('/thankyou/')) {
                gtag('event', 'conversion', {
                    'send_to': 'AW-11475808917/uLuQCJO2t40ZEJXli-Aq',
                    'value':orderTotal,
                    'currency': 'USD',
                    'transaction_id': order_id
                });
            }
        } else {
            console.log('wholesale_order');
        }	

	});
</script>
<script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>

<script>
    window.renderOptIn = function() {
        var order_Items = $('#order_Items_ty').val();
        if (order_Items == '' || order_Items == null) {
            return false;
        }
        var order_id = parseInt($('.getorderID').html());
        var contact_email = $('#order_contact_email').val();
        var orderItemsArray = JSON.parse(order_Items);
        var barcodes = orderItemsArray.map(function(item) {
            return item.product.barcode;
        });
        let currentDate = new Date();
        currentDate.setDate(currentDate.getDate() + 4);
    
        // Format the date as YYYY-MM-DD
        let year = currentDate.getFullYear();
        let month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
        let day = currentDate.getDate().toString().padStart(2, '0');
        let formattedDate = `${year}-${month}-${day}`;
    
        var products = barcodes.map(function(barcode) {
            return { "gtin": barcode };
        });
  
        window.gapi.load('surveyoptin', function() {
            window.gapi.surveyoptin.render(
            {
            // REQUIRED FIELDS
                "merchant_id": 5309938228,
                "order_id": order_id,
                "email": contact_email,
                "delivery_country": "US",
                "estimated_delivery_date": formattedDate,
    
                // OPTIONAL FIELDS
                "opt_in_style": "BOTTOM_RIGHT_DIALOG", // Adjust this according to your preference
                "products": products // Add the products array here
    
            });
        });
    }
</script>


{{-- <script src="https://apis.google.com/js/platform.js?onload=renderBadge" async defer></script>
<script>
    window.renderBadge = function() {
        var merchantId = @json(env('GOOGLE_MERCHANT_CENTER_ID'));
        var ratingBadgeContainer = document.createElement("div");
        document.body.appendChild(ratingBadgeContainer);
        window.gapi.load('ratingbadge', function() {
            window.gapi.ratingbadge.render(ratingBadgeContainer, {"merchant_id": merchantId});
        });
    }
</script> --}}
<script>
    function adding_quantity(product_id , option_id) {
        // updateBodyClickEventStatus(false);
        var plus = parseInt($('.swap_qty_number_' + product_id).val()) === '' ? 1 : parseInt($('.swap_qty_number_' + product_id).val());
        var stock_number = $('.swap_qty_number_'+product_id).attr('max');
        var result = plus + 1;
        if (result > stock_number) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Maximum stock limit reached',
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
            $('.swap_qty_number_' + product_id).val(stock_number);
            $('.cart-total-number-' + product_id).html(stock_number);
            return false;
        }
        else {
            var new_qty = $('.swap_qty_number_' + product_id).val(result);
            // increasingQuantity(product_id , option_id)
            $('.swap_qty_number_' + product_id).val(result)
        } 
    }
    function subtracting_quantity(product_id , option_id) {
        // updateBodyClickEventStatus(false);
        var minus = $('.swap_qty_number_' + product_id).val() == '' ? 1 : parseInt($('.swap_qty_number_' + product_id).val());
        if (minus > 1) {
            var result = minus - 1;
            $('.swap_qty_number_' + product_id).val(result);
            // decreasingQuantity(product_id , option_id)
        } else {
            var result = minus - 1;
            
            if (minus == 1) {
                result = 1;
                // $('.cart-total-number-' + product_id).css('display' , 'none');
                // $('.button_swap_quantity_'+product_id).addClass('d-none');
                // $('.original_cart_btn_'+product_id).removeClass('d-none');
                // $('.swap_qty_number_' + product_id).val(0);
                $('.swap_qty_number_' + product_id).val(result);
                // decreasingQuantity(product_id , option_id)
            }
            else{
                // $('.cart-total-number-' + product_id).css('display' , 'none');
                // $('.button_swap_quantity_'+product_id).addClass('d-none');
                // $('.original_cart_btn_'+product_id).removeClass('d-none');
                $('.swap_qty_number_' + product_id).val(1);
            
            }
        }
         
    }
    function increasingQuantity(id, option_id) {
        // $('#ajaxSubmit_'+id).addClass('d-none');
        // $('#button_swap_'+id).removeClass('d-none');
        // $('#swap_qty_number_'+id).val(1);
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
        jQuery.ajax({
            url: "{{ url('/update-product-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_' + id).val(),
                option_id: option_id,
                quantity: 1,
                'action': 'addition'
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
                        product_quantity = item.quantity;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    // jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
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
                    var product_quantity = 0;
                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var product_id = item.prd_id;
                        product_quantity = item.quantity;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
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
                    // Swal.fire({
                    //     toast: true,
                    //     icon: 'success',
                    //     title: jQuery('#quantity').val() + ' X ' + product_name +
                    //         ' added to your cart',
                    //     timer: 3000,
                    //     showConfirmButton: false,
                    //     position: 'top',
                    //     timerProgressBar: true
                    // });
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
    function decreasingQuantity(id, option_id) {
        // $('#ajaxSubmit_'+id).addClass('d-none');
        // $('#button_swap_'+id).removeClass('d-none');
        // $('#swap_qty_number_'+id).val(1);
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
        var stock_number = $('.swap_qty_number_'+id).attr('max');
        jQuery.ajax({
            url: "{{ url('/update-product-cart/') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                p_id: jQuery('#p_' + id).val(),
                option_id: option_id,
                quantity: 1,
                'action': 'subtraction'
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
                        product_quantity = item.quantity;
                        var price = parseFloat(item.price);
                        var quantity = parseFloat(item.quantity);
                        var subtotal = parseFloat(price * quantity);
                        var cart_total = cart_total + subtotal;
                        var total_cart_quantity = total_cart_quantity + quantity;
                        $('#subtotal_' + product_id).html('$' + subtotal);
                        var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                            .val()).innerHTML;
                    }
                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    // jQuery('.cart-total-number-' + id).html(stock_number);
                    jQuery('.cart-total-number-' + id).html(response.actual_stock);
                    jQuery('.swap_qty_number_'+id).val(response.actual_stock);
                    // Swal.fire({
                    //     toast: true,
                    //     icon: 'error',
                    //     title: jQuery('#quantity').val() > 1 ? jQuery('#quantity').val() + ' X ' + product_name : 'Product' +
                    //         ' removed from your  cart',
                    //     timer: 3000,
                    //     showConfirmButton: false,
                    //     position: 'top',
                    //     timerProgressBar: true
                    // });
                }
                if (response.status == 'success') {
                    var cart_items = response.cart_items;
                    var cart_total = 0;
                    var total_cart_quantity = 0;
                    var product_quantity = 0;
                    for (var key in cart_items) {
                        var item = cart_items[key];
                        var product_id = item.prd_id;
                        product_quantity = item.quantity;
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
                    // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                    jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
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
                    // Swal.fire({
                    //     toast: true,
                    //     icon: 'error',
                    //     title: jQuery('#quantity').val() > 1 ? jQuery('#quantity').val() + ' X ' + product_name : 'Product' +
                    //         ' removed from your  cart',
                    //     timer: 3000,
                    //     showConfirmButton: false,
                    //     position: 'top',
                    //     timerProgressBar: true
                    // });
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
    function update_qty_text(id , option_id) {
        // updateBodyClickEventStatus(false);
        var stock_number = $('.swap_qty_number_'+id).attr('max');
        var qty = parseInt($('.swap_qty_number_' + id).val()) == 0 || $('.swap_qty_number_' + id).val() === '' ? 1 : parseInt($('.swap_qty_number_' + id).val());
        var initial_free_shipping_value = parseInt($('.initial_free_shipping_value').val());
        var tax = 0;
        var tax_rate = parseFloat($('#tax_rate_number').val());
        $('.swap_qty_number_' + id).val(qty);
        if (qty > stock_number) {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Maximum stock limit reached',
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
            $('.button_swap_quantity_'+id).addClass('d-none');
            $('.original_cart_btn_'+id).removeClass('d-none');
            $('.cart-total-' + id).addClass('added-to-cart-on-change');
            $('.swap_qty_number_' + id).val(0);
            $('.cart-total-number-' + id).html(0);
            return false;
        }
        else {
            jQuery.ajax({
                url: "{{ url('/add-to-cart/') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    p_id: jQuery('#p_' + id).val(),
                    option_id: option_id,
                    quantity: qty
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
                            product_quantity = item.quantity;
                            var quantity = parseFloat(item.quantity);

                            var subtotal = parseFloat(price * quantity);
                            var cart_total = cart_total + subtotal;
                            var total_cart_quantity = total_cart_quantity + quantity;
                            $('#subtotal_' + product_id).html('$' + subtotal);
                            var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                                .val()).innerHTML;
                        }

                        // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                        // jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
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
                        var product_quantity = 0;

                        for (var key in cart_items) {
                            var item = cart_items[key];

                            var product_id = item.prd_id;
                            var price = parseFloat(item.price);
                            product_quantity = item.quantity;
                            var quantity = parseFloat(item.quantity);

                            var subtotal = parseFloat(price * quantity);
                            var cart_total = cart_total + subtotal;
                            var total_cart_quantity = total_cart_quantity + quantity;
                            $('#subtotal_' + product_id).html('$' + subtotal);
                            var product_name = document.getElementById("product_name_" + jQuery('#p_' + id)
                                .val()).innerHTML;
                        }

                        // jQuery('.cart-total-' + id).html($('#swap_qty_number_' + id).val());
                        jQuery('.cart-total-number-' + id).html($('.swap_qty_number_' + id).val());
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
                            title: qty + ' X ' + product_name +
                                ' added to your cart',
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
                    $('#top_cart_quantity').html(total_cart_quantity);
                    $('#cart_items_quantity').html(total_cart_quantity);
                    $('.cartQtyipad').html(total_cart_quantity);
                    $('.cartQtymbl').html(total_cart_quantity);
                    $('#topbar_cart_total').html('$' + parseFloat(cart_total).toFixed(2));
                    $('.topbar_cart_total_ipad').html('$'+parseFloat(cart_total).toFixed(2));
                    var total = document.getElementById('#top_cart_quantity');
                }
            });
        }
        
    }
    // function button_swap_quantity (id , option_id) {
    //     updateBodyClickEventStatus(false);
    // }
</script>

<script type="text/javascript">
    var popover = new bootstrap.Popover(document.querySelector('.cart-price'), {
        container: 'body',
        html: true
    });
    feather.replace();
</script>
<script>
    // delete employee ajax request
    $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        let csrf = '{{ csrf_token() }}';
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('secondary_user.delete') }}',
                    method: 'delete',
                    data: {
                        id: id,
                        _token: csrf
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        $('#row-' + id).remove();
                    }
                });
            }
        })
    });
</script>
<script type="text/javascript">
    let dropdowns = document.querySelectorAll('.dropdown-toggle')
    dropdowns.forEach((dd) => {
        dd.addEventListener('click', function(e) {
            var el = this.nextElementSibling
            el.style.display = el.style.display === 'block' ? 'none' : 'block'
        })
    });

    function bodyClickHandler() {
        $('.added-to-cart').css('display', 'inline-flex');
        $('.button_swap_quantity').css('display', 'none');
        $('.quantity_count_circle').each(function() {
            var html = $(this);
            var spanContent = $(html).find('span');
            if (parseInt($(html).find('span').html()) > 0) {
                spanContent.parent().css('display', 'inline-flex');
            } else {
                spanContent.parent().css('display', 'none');
            }
        });
    }
    // function updateBodyClickEventStatus(newStatus) {
    //   bodyClickEventActive = newStatus;
    // }

    $(document).ready(function() {
        // $('body').click(function() {
        //     if (bodyClickEventActive) {
        //         bodyClickHandler();
        //     }

        // });

        // $('body').on('click', function() {
        //     updateBodyClickEventStatus(true);
        // });

        $(document).on('click', '#copyUrl', function() {
            $('#custom_loader').removeClass('d-none');
            let textValue = $(this).attr('data-id');
            var temp = $("<input>");
            $("body").append(temp);
            temp.val(textValue).select();
            document.execCommand("copy");
            temp.remove();
            console.timeEnd('time1');
            $('#custom_loader').addClass('d-none');
        });
        $('.login-info-box').fadeOut();
        $('.login-show').addClass('show-log-panel');
    });
    $('.login-reg-panel input[type="radio"]').on('change', function() {
        if ($('#log-login-show').is(':checked')) {
            $('.register-info-box').fadeOut();
            $('.login-info-box').fadeIn();
            $('.white-panel').addClass('right-log');
            $('.register-show').addClass('show-log-panel');
            $('.login-show').removeClass('show-log-panel');
        } else if ($('#log-reg-show').is(':checked')) {
            $('.register-info-box').fadeIn();
            $('.login-info-box').fadeOut();
            $('.white-panel').removeClass('right-log');
            $('.login-show').addClass('show-log-panel');
            $('.register-show').removeClass('show-log-panel');
        }
    });

    //set active class on click
    var path = window.location.href;
    $('.account_navigation li a').each(function() {
        if (this.href == path) {
            $(this).parent().addClass('active');
        }
    });
</script>
</script>
<script type="text/javascript">
    $(document).ready(function() {
        // popovers initialization - on hover
        $('[data-toggle="popover-click"]').popover({
            html: true,
            trigger: 'clcik',
            placement: 'top',
            sanitize: false,
            content: function() {
                return '<img src="' + $(this).data('img') + '" />';
            }
        });

        // popovers initialization - on click
        $('[data-toggle="popover-hover"]').popover({

            html: true,
            trigger: 'hover',
            placement: 'top',
            sanitize: false,
            content: $('#popover-form').html(),
        });
        var invitation_sent = sessionStorage.getItem('invitation');
        if (invitation_sent == 1) {
            $("#additional_users").addClass("active");
            $("#additional-users").removeClass("d-none");
            $('#intro').addClass('d-none');
            $('.nav-pills #dashboard').addClass('active');
            $('#dashboard').removeClass('active');
        } else {
            $("#additional_users").removeClass("active");
        }
    });
</script>
<script>
    $(document).ready(function() {
        var input = $('.mobileFormat').html();
        var formattedInput = formatPhoneNumber(input);
        $('.mobileFormat').html(formattedInput);
    });

    function formatPhoneNumber(input) {
        // Remove all non-digit characters from the input
        var digits = input.replace(/\D/g, '');

        // Format the digits as (555) 123-4567
        var formattedInput = '';
        for (var i = 0; i < digits.length; i++) {
            if (i === 0) {
                formattedInput += '(';
            } else if (i === 3) {
                formattedInput += ') ';
            } else if (i === 6) {
                formattedInput += '-';
            }
            formattedInput += digits.charAt(i);
        }

        return formattedInput;
    }
</script>
<script>
    $(document).ready(function() {
        var list_id = $("#list_id").val();
        if (list_id == '') {
            $(".btn-add-to-cart").prop('disabled', true);
        } else {
            $(".btn-add-to-cart").prop('disabled', false);
        }
        $('body').on('click', '.btn-add-to-cart', function() {
            var id = $(this).attr('id');
            var product_id = id.replace('btn_', '');
            var row = $('#product_row_' + product_id).length;

            if (row > 0) {
                var difference = 0;
                var subtotal_before_update = parseFloat($('#subtotal_' + product_id).html());
                console.log('difference => ' + difference);
                console.log('sub total before update  => ' + subtotal_before_update);

                var retail_price = parseFloat($('#retail_price_' + product_id).html());
                var quantity = parseFloat($('#quantity_' + product_id).val());
                var subtotal = parseFloat($('#subtotal_' + product_id).html());

                quantity++;
                subtotal = retail_price * quantity;

                difference = subtotal_before_update - subtotal;

                console.log('difference => ' + difference);

                var grand_total = $('#grand_total').html();
                grand_total = parseFloat(grand_total);

                console.log('Grand Total => ' + grand_total);


                grand_total = grand_total - difference;
                $('#grand_total').html(grand_total);

                console.log('Grand Total => ' + grand_total);

                $('#quantity_' + product_id).val(quantity);
                $('#subtotal_' + product_id).html(subtotal);
                return false;
            }

            jQuery.ajax({
                url: "{{ url('admin/add-to-list') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    product_id: product_id,
                    //option_id: option_id
                },
                success: function(response) {
                    $('#product_list').append(response);
                    console.log(response);
                    var grand_total = $('#grand_total').html();
                    grand_total = parseFloat(grand_total);

                    var retail_price = $('#btn_' + product_id).attr('data-retail-price');
                    console.log(retail_price);

                    var subtotal = retail_price * 1;

                    grand_total = grand_total + subtotal;

                    $('#grand_total').html(grand_total);
                }
            });
        });
        $('.all_items:first').addClass('active');
    });
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js">
</script>
<script>
    $('#owl-carousel-landing-page').owlCarousel({
            rtl:false,
            loop:false,
            margin:10,
            dots:false,
            nav:true,
            navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
            responsive:{
                0:{
                    items:1,
                    nav:false
                },
                600:{
                    items:2,
                    slideBy:2,
                    nav:false
                },
                1000:{
                    items:3,
                    slideBy:3,
                },
            }
        });
        $('.similar_products_owl_carasoul_blog').owlCarousel({
            rtl:false,
            loop:false,
            margin:10,
            dots:false,
            nav:true,
            navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
            responsive:{
                0:{
                    items:1,
                   
                },
                600:{
                    items:2,
                    
                },
                1400:{
                    items:3,
                   
                },
            }
        });
        $(document).ready(function() {
            function calculateItemsToShow() {
            // Your logic to dynamically calculate the number of items
                var screenWidth = $(window).width();
                if (screenWidth > 0 && screenWidth < 600) {
                    return 1;
                } 
                if (screenWidth >= 600 && screenWidth < 1000) {
                    return 2;
                }
                if (screenWidth >= 1000 && screenWidth < 1200) {
                    return 3;
                }
                if (screenWidth >= 1200 && screenWidth < 1400) {
                    return 4;
                }
                if (screenWidth >= 1400 && screenWidth < 1800) {
                    return 6;
                }
                if (screenWidth >= 1800 && screenWidth < 2250) {
                    return 8;
                }
                if (screenWidth >= 2250) {
                    return 10;
                }
            }
            function updateOptions() {
                var totalItems = $('.similar_items_div').length;
                var itemsToShow = calculateItemsToShow();
                var centerItems = totalItems < itemsToShow;
                if (totalItems >= itemsToShow) {
                    $('.similar_products_owl_carasoul .owl-stage-outer').removeClass('d-flex');
                }
                $('.similar_products_owl_carasoul').owlCarousel({
                    rtl:false,
                    loop:false,
                    // center: centerItems,
                    margin:10,
                    nav:true,
                    navText: ["<i class='fas fa-angle-left'></i>", "<i class='fas fa-angle-right'></i>"],
                    responsive:{
                        0:{
                            items:calculateItemsToShow(),
                            nav:false,
                            slideBy:1,
                            dots:false,
                        },
                        600:{
                            items:calculateItemsToShow(),                    
                            nav:false,
                            slideBy:2,
                            dots:false,
                        },
                        1000:{
                            items:calculateItemsToShow(),                    
                            slideBy:3,
                        },
                        1200:{
                            items:calculateItemsToShow(),                    
                            slideBy:4,
                        },
                        1400: {
                            items:calculateItemsToShow(),                    
                            slideBy:6,
                        },
                        1800:{
                            items:calculateItemsToShow(),                    
                            slideBy:8,
                        },
                        2250:{
                            items:calculateItemsToShow(),
                            slideBy:10,
                        }
                    }
                });
            }
            updateOptions(); // Initial setup

            // Update options on window resize
            $(window).resize(function () {
                updateOptions();
            });

            var totalItems = $('.similar_items_div').length;
            var itemsToShow = calculateItemsToShow();
            var centerItems = totalItems < itemsToShow;
            if (totalItems >= itemsToShow) {
                $('.similar_products_owl_carasoul .owl-stage-outer').css('display', 'block');    
            }
        });
        function showZendesk() {
            var first_name = $('#zendesk_first_name').val();
            var last_name = $('#zendesk_last_name').val();
            var userName = first_name + ' ' + last_name;
            var userEmail = $('#zendesk_email').val();
            zE('webWidget', 'identify', {
                name: userName,
                email: userEmail
            });

            zE('webWidget', 'open');
        }
</script>

<!-- Modal -->
<div class="modal fade notify_popup_modal" id="notify_user_modal" tabindex="-1" role="dialog"
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
                            <input type="hidden" name="sku" id="sku_value" class="productSku_value" value="">
                            <input type="hidden" name="product_id" id="product_id_value" class="productId_value"
                                value="">
                            <div class="col-md-12">
                                <input type="text" name="notify_user_email" id="notify_user_email"
                                    class="form-control notifyEmail" value="" placeholder="Enter your email">
                                <div class="text-danger email_required_alert"></div>
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
                    onclick="notify_user_about_product_stock($('.productId_value').val() , $('.productSku_value').val())">Submit</button>
                <!-- You can add additional buttons here if needed -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade notify_popup_modal_similar" id="notify_user_modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notify User About Product Stock</h5>
                <button type="button" class="close" onclick="close_notify_user_modal_similar()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="hidden" name="sku" id="sku_value" class="productSku_value" value="">
                            <input type="hidden" name="product_id" id="product_id_value" class="productId_value"
                                value="">
                            <div class="col-md-12">
                                <input type="text" name="notify_user_email" id="notify_user_email"
                                    class="form-control similar_notifyEmail" value="" placeholder="Enter your email">
                                <div class="text-danger email_required_alert"></div>
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
                    onclick="notify_user_about_product_stock_similar($('.productId_value').val() , $('.productSku_value').val())">Submit</button>
                <!-- You can add additional buttons here if needed -->
            </div>
        </div>
    </div>
</div>
{{-- notify user pop up modal end --}}

@if (auth()->user() && empty(Session::get('company')))
    @php
        $user_companies = Session::get('companies');
    @endphp
    <script>
        $(document).ready(function() {
            $('#companySelectionModal').modal('show');
        });
    </script>

<div class="modal fade companySelectionModal" id="companySelectionModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title selection_company_heading" id="exampleModalLabel">Please Select Company first for which you want to restore the cart</h5>
            </div>
            <div class="modal-body">
                <div class="dropdown text-center">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <button class="btn btn-info dropdown-toggle select_company_button_pop_up" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Select Company
                            </button>
                            <div class="row">
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    @if ($user_companies)
                                        @foreach ($user_companies as $company)
                                            @php
                                                $contact_id = $company->contact_id ?? $company->secondary_id;
                                                $primary = $company->contact_id ? '(primary)' : '(secondary)';
                                                $disabled = $company->status == 0 ? 'disabled' : '';
                                                $disable_text = $company->status == 0 ? '(Disabled)' : '';
                                                $muted = $company->status == 0 ? 'text-muted' : '';
                                            @endphp
                                            @if($company->type != "Supplier")
                                                <a type="button"
                                                    class="dropdown-item {{ $disabled }} {{ $muted }}"
                                                    onclick="switch_company_user({{ $contact_id }})">
                                                    {{ !empty($company->company) ? $company->company : $company->firstName . ' ' . $company->lastName }}
                                                    <span style="font-size: 9px;font-family: 'Poppins';" class="{{ $muted }}">
                                                        {{ $primary }} {{ $disable_text }}
                                                    </span>
                                                </a>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    $(document).on('keydown', function(event) {
        // Check if the modal is open and the Enter key is pressed
        if ($('.notify_popup_modal').hasClass('show') && event.keyCode === 13) {
            // Prevent the default action of the Enter key
            event.preventDefault();
        }
        if ($('.notify_popup_modal_detail').hasClass('show') && event.keyCode === 13) {
            // Prevent the default action of the Enter key
            event.preventDefault();
        }
        if ($('.notify_popup_modal_similar').hasClass('show') && event.keyCode === 13) {
            // Prevent the default action of the Enter key
            event.preventDefault();
        }
        if ($('.notify_popup_modal_similar_portion').hasClass('show') && event.keyCode === 13) {
            // Prevent the default action of the Enter key
            event.preventDefault();
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newsletterDiv = document.getElementById('newsletter_div');
        if (newsletterDiv) {
            newsletterDiv.scrollIntoView({ behavior: 'smooth' });
        }
    });
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
                        $('#see_similar_pop_up').modal('hide');
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
                        $('#see_similar_pop_up').modal('show');
                        $('.similar_products_row-body').html(generateProductsHtml(response , response.products));
                    }
                } else {
                    $('#see_similar_pop_up').modal('hide');
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
                $('#see_similar_pop_up').modal('hide');
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

    // function generateProductsHtml(response , products) {
    //     var price_column = response.price_column;
    //     var htmlContent = `<div class="row">`;

    //     products.forEach(function(product , price_column) {
    //         var productHtml = `
    //             <div class="col-md-6 col-xl-6 col-lg-6 d-flex align-self-stretch mt-2 product_row_mobile_responsive pt-1 h-100">
    //                 <div class="p-2 shadow-sm w-100" style="background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125); border-radius: 0.25rem;">
    //         `;

    //         // Add subscribe button if contact_id is present
    //         if (response.contact_id) {
    //             productHtml += generateSubscribeButton(product.product_id, product.option_id , response.user_buy_list_options);
    //         }

    //         // Add image or placeholder
    //         productHtml += generateProductImage(product);
            
    //         var productName = product.products.name;  // Get the text of the product name
    //         var shortenedName = productName.length > 30 ? productName.substring(0, 20) + '...' : productName;
    //         // Add the rest of the product details
    //         productHtml += `
    //             <div class="card-body d-flex flex-column text-center mt-1 prd_mbl_card_bdy p-2">
    //                 <h5 class="card-title card_product_title tooltip-product" style="font-weight: 500; font-size: 16px;" data-title="${product.products.name}" id="product_name_${product.products.id}">
    //                     <a class="product-row-product-title" href="${window.location.origin +'/product-detail/' + product.products.id + '/' + product.option_id + '/' + product.products.slug}">
    //                         ${shortenedName}
    //                         <div class="tooltip-product-text bg-white text-primary">
    //                             <div class="tooltip-arrow"></div>
    //                             <div class="tooltip-inner bg-white text-primary">
    //                                 <span>${productName}</span>
    //                             </div>
    //                         </div>
    //                     </a>
    //                 </h5>
    //                 <input type="hidden" name="p_id" id="p_${product.products.id}" value="${product.products.id}">
    //             </div>
    //         `;
    //         if (product.show_price === true && product.default_price !== null) {
    //             if ((product.default_price[response.price_column]  != null) || (parseFloat(product.default_price[response.price_column]) > 0)) {
    //                 var formattedPrice = formatNumber(parseFloat(product.default_price[response.price_column]));
    //             } else if ((product.default_price.sacramentoUSD  != null) || (parseFloat(product.default_price.sacramentoUSD) > 0)) {
    //                 var formattedPrice = formatNumber(parseFloat(product.default_price.sacramentoUSD));
    //             } else {
    //                 var formattedPrice = formatNumber(parseFloat(product.default_price.retailUSD));
    //             }
    //             productHtml += `
    //                 <h4 class="text-uppercase mb-0 text-center p_price_resp mt-0 mb-2">
    //                     $${formattedPrice}
    //                 </h4>
    //             `;
    //         }

    //         if (product.add_to_cart == true) {
    //             productHtml += `
    //                 <div class="col-sm-12 mt-0 button_swap_quantity button_swap_quantity_${product.products.id} mb-2" id="button_swap_${product.products.id}">
    //                     <div class="input-group">
    //                         <div class="input-group-prepend custom-border qty_minus_mobile">
    //                             <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="subtracting_quantity('${product.products.id}', '${product.option_id}')"><i class="fa fa-minus minus_qty_font qty_font"></i></button>
    //                         </div>
                            
    //                         <input type="number" id="swap_qty_number_${product.products.id}" name="swap_qty_number" value="1"  class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_${product.products.id}"  style="font-weight: 500" min="1" max="${product.stockAvailable}">
    //                         <div class="input-group-prepend custom-border qty_plus_mobile">
    //                             <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="adding_quantity('${product.products.id}', '${product.option_id}')"><i class="fa fa-plus plus_qty_font qty_font"></i></button>
    //                         </div>
    //                     </div>
    //                 </div>
    //                 <button 
    //                     class="hover_effect prd_btn_resp ajaxSubmit button-cards col w-100  mb-1 original_cart_btn   original_cart_btn_${product.products.id}" 
    //                     type="submit" 
    //                     style="max-height: 46px;" id="ajaxSubmit_${product.products.id}"
    //                     onclick="updateCart('${product.products.id}', '${product.option_id}')">
    //                     Add to cart
    //                 </button>
    //             `;
    //         } else {
    //             productHtml += `
    //                 <div class="col-md-12">
    //                     <button 
    //                         class="w-100 ml-0 call-to-order-button text-uppercase" 
    //                         style="max-height: 46px;">
    //                         Call To Order
    //                     </button>
    //                 </div>
    //             `;
    //         }
            

    //         productHtml += `</div></div>`;
    //         htmlContent += productHtml;
    //     });

    //     htmlContent += `</div>`;
    //     return htmlContent;
    // }

    function generateProductsHtml(response, products) {
        var price_column = response.price_column;
        var htmlContent = `<div class="owl-carousel owl-theme similar-products-carousel-ai w-75">`;

        products.forEach(function(product) {
            var productHtml = `
                <div class="item">
                    <div class="d-flex align-self-stretch mt-2  pt-1 h-100">
                        <div class="p-2 shadow-sm w-100" style="background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125); border-radius: 0.25rem;">
                            ${response.contact_id ? generateSubscribeButton(product.product_id, product.option_id, response.user_buy_list_options) : ''}
                            ${generateProductImage(product)}

                            <!-- Product Details -->
                            <div class="card-body d-flex flex-column text-center mt-1 prd_mbl_card_bdy p-2">
                                <h5 class="card-title card_product_title tooltip-product" style="font-weight: 500; font-size: 16px;" data-title="${product.products.name}" id="product_name_${product.products.id}">
                                    <a class="product-row-product-title" href="${window.location.origin +'/product-detail/' + product.products.id + '/' + product.option_id + '/' + product.products.slug}">
                                        ${product.products.name.length > 30 ? product.products.name.substring(0, 20) + '...' : product.products.name}
                                        <div class="tooltip-product-text bg-white text-primary">
                                            <div class="tooltip-arrow"></div>
                                            <div class="tooltip-inner bg-white text-primary">
                                                <span>${product.products.name}</span>
                                            </div>
                                        </div>
                                    </a>
                                </h5>
                                <input type="hidden" name="p_id" id="p_${product.products.id}" value="${product.products.id}">
                            </div>
                            ${generatePriceAndCartButtons(product, response.price_column)}
                        </div>
                    </div>
                </div>
            `;

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
        var imageNotAvailableUrl = "{{ asset('theme/img/image_not_available.png') }}";
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

    function generatePriceAndCartButtons(product, price_column) {
        var productHtml = '';

        // Check if the price should be shown and if the default price is not null
        if (product.show_price === true && product.default_price !== null) {
            var formattedPrice;
            if (product.default_price[price_column] != null && parseFloat(product.default_price[price_column]) > 0) {
                formattedPrice = formatNumber(parseFloat(product.default_price[price_column]));
            } else if (product.default_price.sacramentoUSD != null && parseFloat(product.default_price.sacramentoUSD) > 0) {
                formattedPrice = formatNumber(parseFloat(product.default_price.sacramentoUSD));
            } else {
                formattedPrice = formatNumber(parseFloat(product.default_price.retailUSD));
            }

            productHtml += `
                <h4 class="text-uppercase mb-0 text-center p_price_resp mt-0 mb-2">
                    $${formattedPrice}
                </h4>
            `;
        }

        // Check if the product can be added to the cart
        if (product.add_to_cart === true) {
            productHtml += `
                <div class="col-sm-12 mt-0 button_swap_quantity button_swap_quantity_${product.products.id} mb-2" id="button_swap_${product.products.id}">
                    <div class="input-group">
                        <div class="input-group-prepend custom-border qty_minus_mobile">
                            <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="subtracting_quantity('${product.products.id}', '${product.option_id}')"><i class="fa fa-minus minus_qty_font qty_font"></i></button>
                        </div>
                        
                        <input type="number" id="swap_qty_number_${product.products.id}" name="swap_qty_number" value="1" class="qty_number_mobile bg-white form-control text-dark form-control-sm text-center swap_qty_number_${product.products.id}" style="font-weight: 500" min="1" max="${product.stockAvailable}">
                        <div class="input-group-prepend custom-border qty_plus_mobile">
                            <button class="p-0 bg-transparent btn-sm border-0 qty_customize_btn" id="" onclick="adding_quantity('${product.products.id}', '${product.option_id}')"><i class="fa fa-plus plus_qty_font qty_font"></i></button>
                        </div>
                    </div>
                </div>
                <button 
                    class="hover_effect prd_btn_resp ajaxSubmit button-cards col w-100  mb-1 original_cart_btn   original_cart_btn_${product.products.id}" 
                    type="submit" 
                    style="max-height: 46px;" id="ajaxSubmit_${product.products.id}"
                    onclick="updateCart('${product.products.id}', '${product.option_id}')">
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

        return productHtml;
    }


    function formatNumber(value) {
        return parseFloat(value).toFixed(2);
    }

    $(document).on('shown.bs.modal', '#see_similar_pop_up', function () {
        $('.similar-products-carousel-ai').owlCarousel({
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
        $('.similar-products-carousel-ai').trigger('refresh.owl.carousel');
    });

</script>

<!-- Floating Button -->
@php
$averageRating = App\Helpers\UserHelper::getaverageRating();
function getStars($averageRating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $averageRating) {
            $stars .= '<span class="text-warning">&#9733;</span>'; // Full star
        } else {
            $stars .= '<span class="text-muted">&#9734;</span>'; // Empty star
        }
    }
    return $stars;
}
@endphp

<!-- Floating Button -->
<button class="floating-button btn btn-primary position-fixed d-none" data-bs-toggle="modal" data-bs-target="#reviewsModal" id="floatingButton">
    <div class="rating-container">
        <span id="default_average">{{App\Helpers\UserHelper::getaverageRating() }}</span> <!-- Display average rating -->
        <span id="averageRatingStars">{!! getStars($averageRating) !!}</span> <!-- Display star rating -->
    </div>
</button>

<!-- Reviews Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewsModalLabel">Customer Reviews</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: scroll;">
                <ul class="list-group" id="reviewsList">
                    <!-- Reviews will be dynamically inserted here -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load reviews into the modal
    function loadReviews() {
        const reviewsList = document.getElementById('reviewsList');
        reviewsList.innerHTML = ''; // Clear existing reviews

        // AJAX request to fetch reviews from the Laravel backend
        $.ajax({
            url: '{{ route('get_google_reviews') }}', // Adjust the route to your setup
            method: 'GET',
            success: function(reviews) {
                // Check if reviews are returned
                if (reviews.length === 0) {
                    reviewsList.innerHTML = '<li class="list-group-item">No reviews found.</li>';
                    updateFloatingButton([]); // Update button with no reviews
                } else {
                    reviews.forEach(review => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex align-items-start';

                        // Create avatar image
                        const avatar = document.createElement('img');
                        avatar.src = review.profile_photo_url || 'default-avatar.png'; // Fallback to a default avatar
                        avatar.alt = review.author_name || 'Avatar';
                        avatar.className = 'rounded-circle me-2';
                        avatar.style.width = '50px'; // Adjust size as necessary
                        avatar.style.height = '50px'; // Adjust size as necessary

                        // Create review content
                        const contentDiv = document.createElement('div');
                        contentDiv.className = 'flex-grow-1';
                        
                        // Author name
                        const authorName = document.createElement('strong');
                        authorName.textContent = review.author_name;
                        
                        // Rating
                        const ratingStars = document.createElement('div');
                        ratingStars.innerHTML = getStars(review.rating); // Generate stars based on rating
                        
                        // Review text
                        const reviewText = document.createElement('p');
                        reviewText.textContent = review.text;

                        // Append all elements to the content div
                        contentDiv.appendChild(authorName);
                        contentDiv.appendChild(ratingStars);
                        contentDiv.appendChild(reviewText);

                        // Append avatar and content div to list item
                        li.appendChild(avatar);
                        li.appendChild(contentDiv);
                        
                        reviewsList.appendChild(li);
                    });
                    updateFloatingButton(reviews); // Update the floating button with average rating
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching reviews:', error);
                reviewsList.innerHTML = '<li class="list-group-item">Error fetching reviews. Please try again later.</li>';
                updateFloatingButton([]); // Update button with no reviews
            }
        });
    }

    // Function to generate star rating HTML
    function getStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += (i <= rating) ? '<span class="text-warning star">&#9733;</span>' : '<span class="text-muted star">&#9734;</span>'; // Full and empty stars
        }
        return stars;
    }

    // Function to calculate average rating
    function calculateAverageRating(reviews) {
        if (reviews.length === 0) return 0; // Handle case with no reviews
        const totalRating = reviews.reduce((sum, review) => sum + review.rating, 0);
        return (totalRating / reviews.length).toFixed(1); // Average rating to one decimal place
    }

    // Function to update the floating button with average rating
    function updateFloatingButton(reviews) {
        const averageRating = calculateAverageRating(reviews);
        const button = document.getElementById('floatingButton');
        const starsHTML = getStars(averageRating); // Display stars based on average rating
        document.getElementById('averageRatingStars').innerHTML = starsHTML; // Set stars in the button
    }

    // Load reviews when the modal is shown
    const reviewsModal = document.getElementById('reviewsModal');
    reviewsModal.addEventListener('show.bs.modal', loadReviews);
</script>




